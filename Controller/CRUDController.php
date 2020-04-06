<?php

namespace PiWeb\PiCRUD\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Event\AccessEvent;
use PiWeb\PiCRUD\Event\QueryEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use PiWeb\PiCRUD\Tools\EntityManager;
use PiWeb\PiCRUD\Form\EntityFormType;
use PiWeb\PiCRUD\Event\FormEvent;
use PiWeb\PiCRUD\Event\EntityEvent;

class CRUDController extends AbstractController
{
    use TargetPathTrait;

    private $configuration;

    private $dispatcher;

    private EntityManager $entityManager;

    public function __construct(array $configuration, EventDispatcherInterface $dispatcher, EntityManager $entityManager)
    {
        $this->configuration = $configuration;
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;
    }

    public function show(string $type, int $id)
    {
        $configuration = $this->entityManager->getEntity($type);

        $entity = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->find($id);

        return $this->render($type . '/show.html.twig', [
            'entity' => $entity,
        ]);
    }

    public function list(Request $request, string $type)
    {
        $configuration = $this->entityManager->getEntity($type);

        $queryBuilder = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $this->dispatcher->dispatch(new QueryEvent($this->getUser(), $type, $queryBuilder), PiCrudEvents::POST_LIST_QUERY_BUILDER);

        return $this->render($template = $this->configuration['templates']['list'], [
            'type' => $type,
            'configuration' => $configuration,
            'templates' => $this->configuration['templates'],
            'entities' => $queryBuilder->getQuery()->execute()
        ]);
    }

    public function admin(Request $request, string $type)
    {
        $configuration = $this->entityManager->getEntity($type);

        $queryBuilder = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $this->dispatcher->dispatch(new QueryEvent($this->getUser(), $type, $queryBuilder), PiCrudEvents::POST_ADMIN_QUERY_BUILDER);

        return $this->render($this->configuration['templates']['admin'], [
            'type' => $type,
            'configuration' => $configuration,
            'templates' => $this->configuration['templates'],
            'entities' => $queryBuilder->getQuery()->execute()
        ]);
    }

    public function form(Request $request, string $type, ?int $id)
    {
        $configuration = $this->entityManager->getEntity($type);

        if (empty($id)) {
            $entity = $this->entityManager->create($type);
            $this->dispatcher->dispatch(new EntityEvent($type, $entity, $request->query->all()), PiCrudEvents::POST_ENTITY_CREATE);
        } else {
            $entity = $this->getDoctrine()
                ->getRepository($configuration['class'])
                ->find($id);
        }

        $form = $this->createForm(EntityFormType::class, $entity, ['type' => $type]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatcher->dispatch(new GenericEvent($entity), ($entity->getId() ? PiCrudEvents::PRE_ENTITY_UPDATE : PiCrudEvents::PRE_ENTITY_PERSIST));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();
        }

        return $this->render('@PiCRUD/form.html.twig', [
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

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirect($this->getTargetPath($this->get('session'), 'main'));
    }
}
