<?php

namespace PiWeb\PiCRUD\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Event\QueryEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use PiWeb\PiCRUD\Tools\EntityManager;
use PiWeb\PiCRUD\Form\EntityFormType;
use PiWeb\PiCRUD\Event\EntityEvent;
use PiWeb\PiBreadcrumb\Model\Breadcrumb;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CRUDController extends AbstractController
{
    use TargetPathTrait;

    private array $configuration;

    private EventDispatcherInterface $dispatcher;

    private EntityManager $entityManager;

    private Breadcrumb $breadcrumb;

    private TranslatorInterface $translator;

    private SerializerInterface $serializer;

    private SessionInterface $session;

    public function __construct(array $configuration, EventDispatcherInterface $dispatcher, EntityManager $entityManager, Breadcrumb $breadcrumb, TranslatorInterface $translator, SerializerInterface $serializer, SessionInterface $session)
    {
        $this->configuration = $configuration;
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;
        $this->breadcrumb = $breadcrumb;
        $this->translator = $translator;
        $this->serializer = $serializer;
        $this->session = $session;
    }

    public function show(Request $request, string $type, int $id)
    {
        $configuration = $this->entityManager->getEntity($type);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.list.title', ['entity_label' => $type]),
            $this->generateUrl('pi_crud_list', ['type' => $type])
        );

        $entity = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->find($id);

        $this->denyAccessUnlessGranted('show', $entity);

        $this->breadcrumb->addItem($entity);

        $template = '@PiCRUD/show.html.twig';
        if ($this->get('twig')->getLoader()->exists('entities/show/' . $type . '.html.twig')) {
            $template = 'entities/show/' . $type . '.html.twig';
        }

        return $this->render($template, [
            'entity' => $entity,
            'type' => $type
        ]);
    }

    public function list(Request $request, string $type)
    {
        $configuration = $this->entityManager->getEntity($type);

        $this->denyAccessUnlessGranted('list', $type);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.list.title', ['entity_label' => $type]),
            $this->generateUrl('pi_crud_list', ['type' => $type])
        );

        $queryBuilder = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $this->dispatcher->dispatch(new QueryEvent($this->getUser(), $type, $queryBuilder), PiCrudEvents::POST_LIST_QUERY_BUILDER);

        $template = '@PiCRUD/list.html.twig';
        if ($this->get('twig')->getLoader()->exists('entities/list/' . $type . '.html.twig')) {
            $template = 'entities/list/' . $type . '.html.twig';
        }

        return $this->render($template, [
            'type' => $type,
            'configuration' => $configuration,
            'entities' => $queryBuilder->getQuery()->execute()
        ]);
    }

    public function admin(Request $request, string $type)
    {
        $configuration = $this->entityManager->getEntity($type);

        $this->denyAccessUnlessGranted('admin', $type);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.admin.title', ['entity_label' => $type]),
            $this->generateUrl('pi_crud_admin', ['type' => $type])
        );

        $queryBuilder = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $this->dispatcher->dispatch(new QueryEvent($this->getUser(), $type, $queryBuilder), PiCrudEvents::POST_ADMIN_QUERY_BUILDER);

        $template = '@PiCRUD/admin.html.twig';
        if ($this->get('twig')->getLoader()->exists('entities/admin/' . $type . '.html.twig')) {
            $template = 'entities/admin/' . $type . '.html.twig';
        }

        return $this->render($template, [
            'type' => $type,
            'configuration' => $configuration,
            'templates' => $this->configuration['templates'],
            'entities' => $queryBuilder->getQuery()->execute()
        ]);
    }

    public function add(Request $request, string $type)
    {
        $configuration = $this->entityManager->getEntity($type);

        $this->denyAccessUnlessGranted('add', $type);

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.admin.title', ['entity_label' => $type]),
            $this->generateUrl('pi_crud_admin', ['type' => $type])
        );

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.form.add.title', ['entity_label' => $type]),
            $this->generateUrl('pi_crud_add', ['type' => $type])
        );

        $entity = $this->entityManager->create($type);
        $this->dispatcher->dispatch(new EntityEvent($type, $entity, $request->query->all()), PiCrudEvents::POST_ENTITY_CREATE);

        $form = $this->createForm(EntityFormType::class, $entity, ['type' => $type]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatcher->dispatch(new GenericEvent($entity), PiCrudEvents::PRE_ENTITY_PERSIST);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->dispatcher->dispatch(new GenericEvent($entity), PiCrudEvents::POST_ENTITY_PERSIST);

            return $this->redirect($this->getTargetPath($this->session, 'main'));
        }

        $template = '@PiCRUD/add.html.twig';
        if ($this->get('twig')->getLoader()->exists('entities/add/' . $type . '.html.twig')) {
            $template = 'entities/add/' . $type . '.html.twig';
        }

        return $this->render($template, [
            'type' => $type,
            'configuration' => $configuration,
            'templates' => $this->configuration['templates'],
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    public function edit(Request $request, string $type, ?int $id)
    {
        $configuration = $this->entityManager->getEntity($type);

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.admin.title', ['entity_label' => $type]),
            $this->generateUrl('pi_crud_admin', ['type' => $type])
        );

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.form.edit.title', ['entity_label' => $type]),
            $this->generateUrl('pi_crud_add', ['type' => $type])
        );

        $entity = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->find($id);

        $this->denyAccessUnlessGranted('edit', $entity);

        $form = $this->createForm(EntityFormType::class, $entity, ['type' => $type]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatcher->dispatch(new GenericEvent($entity), PiCrudEvents::PRE_ENTITY_UPDATE);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->dispatcher->dispatch(new GenericEvent($entity), PiCrudEvents::POST_ENTITY_UPDATE);
            
            return $this->redirect($this->getTargetPath($this->session, 'main'));
        }

        $template = '@PiCRUD/edit.html.twig';
        if ($this->get('twig')->getLoader()->exists('entities/edit/' . $type . '.html.twig')) {
            $template = 'entities/edit/' . $type . '.html.twig';
        }

        return $this->render($template, [
            'type' => $type,
            'configuration' => $configuration,
            'templates' => $this->configuration['templates'],
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    public function delete(string $type, int $id)
    {
        $configuration = $this->entityManager->getEntity($type);

        $entity = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->find($id);

        $this->denyAccessUnlessGranted('delete', $entity);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirect($this->getTargetPath($this->session, 'main'));
    }

    public function all(string $type)
    {
        $configuration = $this->entityManager->getEntity($type);

        $queryBuilder = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $events = $this->serializer->serialize($queryBuilder->getQuery()->execute(), 'json', [
            'groups' => 'default'
        ]);

        return new JsonResponse($events);
    }
}
