<?php

namespace PiWeb\PiCRUD\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Event\AccessEvent;
use Symfony\Component\EventDispatcher\GenericEvent;

class CRUDController extends AbstractController
{
    use TargetPathTrait;

    private $configuration;

    private $dispatcher;

    public function __construct(array $configuration, EventDispatcherInterface $dispatcher)
    {
        $this->configuration = $configuration;
        $this->dispatcher = $dispatcher;
    }

    public function show(int $id)
    {
        $this->dispatcher->dispatch(new AccessEvent($this->getUser(), $type, 'list', $this->configuration), 'owp_crud_admin.action.list');

        $entity = $this->getDoctrine()
            ->getRepository($this->configuration['entities'][$type]['class'])
            ->find($id);

        return $this->render($type . '/show.html.twig', [
            'entity' => $entity,
        ]);
    }

    public function list(string $type)
    {
        $this->dispatcher->dispatch(new AccessEvent($this->getUser(), $type, 'list', $this->configuration), 'owp_crud_admin.action.list');

        $entities = $this->getDoctrine()
            ->getRepository($this->configuration['entities'][$type]['class'])
            ->findAll();

        return $this->render('@OwpCrudAdmin/list.html.twig', [
            'type' => $type,
            'configuration' => $this->configuration['entities'][$type],
            'entities' => $entities
        ]);
    }

    public function form(Request $request, string $type, ?int $id)
    {
        $this->dispatcher->dispatch(new AccessEvent($this->getUser(), $type, 'form', $this->configuration), 'owp_crud_admin.access');

        if (empty($id)) {
            $entity = new $this->configuration['entities'][$type]['class']();
        } else {
            $entity = $this->getDoctrine()
                ->getRepository($this->configuration['entities'][$type]['class'])
                ->find($id);
        }

        $form = $this->createForm($this->configuration['entities'][$type]['formClass'], $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatcher->dispatch(new GenericEvent($entity), ($entity->getId() ? 'app.entity.pre_update' : 'app.entity.pre_persist'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();
        }

        return $this->render($type . '/form.html.twig', [
            'type' => $type,
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    public function delete(int $id)
    {
        $this->dispatcher->dispatch(new AccessEvent($this->getUser(), $type, 'delete', $this->configuration), 'owp_crud_admin.access');

        $entity = $this->getDoctrine()
            ->getRepository($this->configuration['entities'][$type]['class'])
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirect($this->getTargetPath($this->get('session'), 'main'));
    }
}
