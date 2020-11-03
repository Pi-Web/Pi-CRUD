<?php

namespace PiWeb\PiCRUD\Controller;

use Exception;
use PiWeb\PiCRUD\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Event\FilterEvent;
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

/**
 * Class CRUDController
 * @package PiWeb\PiCRUD\Controller
 */
class CRUDController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @var array
     */
    private array $configuration;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @var Breadcrumb
     */
    private Breadcrumb $breadcrumb;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * CRUDController constructor.
     * @param array $configuration
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManager $entityManager
     * @param Breadcrumb $breadcrumb
     * @param TranslatorInterface $translator
     * @param SerializerInterface $serializer
     * @param SessionInterface $session
     */
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

    /**
     * @param Request $request
     * @param string $type
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function show(Request $request, string $type, int $id)
    {
        try {
            $configuration = $this->entityManager->getEntity($type);
        }
        catch (Exception $e) {
            throw $this->createNotFoundException();
        }

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

    /**
     * @param Request $request
     * @param string $type
     * @return Response
     * @throws Exception
     */
    public function list(Request $request, string $type)
    {
        try {
            $configuration = $this->entityManager->getEntity($type);
        }
        catch (Exception $e) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted('list', $type);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.list.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_list', ['type' => $type])
        );

        $queryBuilder = $this->getDoctrine()
          ->getRepository($configuration['class'])
          ->createQueryBuilder('entity');

        $searchEntity = $this->entityManager->create($type);
        $searchForm = null;
        $searchForm = $this->createForm(SearchFormType::class, $searchEntity, ['type' => $type]);
        $searchForm->handleRequest($request);
        foreach ($searchForm->all() as $field) {
            if (!empty($searchEntity->{'get' . $field->getName()}())) {
                $operator = $field->getConfig()->getOption('attr')['operator'];

                $expression = $queryBuilder->expr()->orX('entity.' . $field->getName().' ' . $operator . ' :'.$field->getName());

                $event = new FilterEvent($this->getUser(), $type, $queryBuilder, $expression, $field->getName());
                $this->dispatcher->dispatch($event, PiCrudEvents::POST_FILTER_QUERY_BUILDER);

                $queryBuilder->andWhere($event->getComposite());
                $queryBuilder->setParameter(
                  $field->getName(),
                  $searchEntity->{'get'.$field->getName()}()
                );
            }
        }

        $event = new QueryEvent($this->getUser(), $type, $queryBuilder);
        $this->dispatcher->dispatch($event, PiCrudEvents::POST_LIST_QUERY_BUILDER);

        $template = '@PiCRUD/list.html.twig';
        if ($this->get('twig')->getLoader()->exists('entities/list/' . $type . '.html.twig')) {
            $template = 'entities/list/' . $type . '.html.twig';
        }

        return $this->render($template, [
          'type' => $type,
          'configuration' => $configuration,
          'entities' => $event->getQueryBuilder()->getQuery()->execute(),
          'templates' => $this->configuration['templates'],
          'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param string $type
     * @return Response
     * @throws Exception
     */
    public function admin(Request $request, string $type)
    {
        try {
            $configuration = $this->entityManager->getEntity($type);
        }
        catch (Exception $e) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted('admin', $type);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.admin.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_admin', ['type' => $type])
        );

        $queryBuilder = $this->getDoctrine()
          ->getRepository($configuration['class'])
          ->createQueryBuilder('entity');

        $event = new QueryEvent($this->getUser(), $type, $queryBuilder);
        $this->dispatcher->dispatch($event, PiCrudEvents::POST_ADMIN_QUERY_BUILDER);

        $template = '@PiCRUD/admin.html.twig';
        if ($this->get('twig')->getLoader()->exists('entities/admin/' . $type . '.html.twig')) {
            $template = 'entities/admin/' . $type . '.html.twig';
        }

        return $this->render($template, [
          'type' => $type,
          'configuration' => $configuration,
          'templates' => $this->configuration['templates'],
          'entities' => $event->getQueryBuilder()->getQuery()->execute()
        ]);
    }

    /**
     * @param Request $request
     * @param string $type
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request, string $type)
    {
        try {
            $configuration = $this->entityManager->getEntity($type);
        }
        catch (Exception $e) {
            throw $this->createNotFoundException();
        }

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

    /**
     * @param Request $request
     * @param string $type
     * @param int|null $id
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(Request $request, string $type, ?int $id)
    {
        try {
            $configuration = $this->entityManager->getEntity($type);
        }
        catch (Exception $e) {
            throw $this->createNotFoundException();
        }

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

    /**
     * @param string $type
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(string $type, int $id)
    {
        try {
            $configuration = $this->entityManager->getEntity($type);
        }
        catch (Exception $e) {
            throw $this->createNotFoundException();
        }

        $entity = $this->getDoctrine()
          ->getRepository($configuration['class'])
          ->find($id);

        $this->denyAccessUnlessGranted('delete', $entity);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirect($this->getTargetPath($this->session, 'main'));
    }

    /**
     * @param string $type
     * @return JsonResponse
     * @throws Exception
     */
    public function all(string $type)
    {
        try {
            $configuration = $this->entityManager->getEntity($type);
        }
        catch (Exception $e) {
            throw $this->createNotFoundException();
        }

        $queryBuilder = $this->getDoctrine()
          ->getRepository($configuration['class'])
          ->createQueryBuilder('entity');

        $events = $this->serializer->serialize($queryBuilder->getQuery()->execute(), 'json', [
          'groups' => 'default'
        ]);

        return new JsonResponse($events);
    }
}
