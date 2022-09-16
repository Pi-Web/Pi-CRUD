<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use PiWeb\PiCRUD\Event\QueryResultEvent;
use PiWeb\PiCRUD\Service\FormService;
use PiWeb\PiCRUD\Service\StructuredDataService;
use PiWeb\PiCRUD\Service\TemplateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Event\QueryEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

final class CRUDController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly array $configuration,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly SerializerInterface $serializer,
        private readonly FormService $formService,
        private readonly TemplateService $templateService,
        private readonly ManagerRegistry $managerRegistry,
        private readonly StructuredDataService $structuredDataService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function show(Request $request, string $type): Response
    {
        $entity = $request->attributes->get('entity');

        return $this->render(
            $this->templateService->getTemplatePath(TemplateService::FORMAT_SHOW, [$type]),
            [
                'configuration' => $request->attributes->get('configuration'),
                'entity' => $entity,
                'type' => $type,
                'structuredData' => $this->structuredDataService->getStructuredData($entity),
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function list(Request $request, string $type): Response
    {
        $configuration = $request->attributes->get('configuration');

        $queryBuilder = $this->managerRegistry
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $searchForm = $configuration['annotation']->search ?
            $this->formService->getSearchForm($request, $type, $queryBuilder) :
            null;

        $event = new QueryEvent($this->getUser(), $type, $queryBuilder);
        $this->dispatcher->dispatch($event, PiCrudEvents::POST_LIST_QUERY_BUILDER);

        $entities = $event->getQueryBuilder()->getQuery()->execute();

        $resultEvent = new QueryResultEvent($type, $entities);
        $this->dispatcher->dispatch($resultEvent, PiCrudEvents::POST_LIST_QUERY_RESULT);

        return $this->render(
            $this->templateService->getTemplatePath(TemplateService::FORMAT_LIST, [$type]),
            [
                'type' => $type,
                'configuration' => $configuration,
                'entities' => $resultEvent->getResults(),
                'templates' => $this->configuration['templates'],
                'searchForm' => $searchForm instanceof FormInterface ?
                    $searchForm->createView() :
                    null,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function admin(Request $request, string $type): Response
    {
        $configuration = $request->attributes->get('configuration');

        $queryBuilder = $this->managerRegistry
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $event = new QueryEvent($this->getUser(), $type, $queryBuilder);
        $this->dispatcher->dispatch($event, PiCrudEvents::POST_ADMIN_QUERY_BUILDER);

        return $this->render(
            $this->templateService->getTemplatePath(TemplateService::FORMAT_ADMIN, [$type]),
            [
                'type' => $type,
                'configuration' => $configuration,
                'templates' => $this->configuration['templates'],
                'entities' => $event->getQueryBuilder()->getQuery()->execute()
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function add(Request $request, string $type): Response
    {
        $form = $this->formService->getAdminForm($request, $type);

        return $form instanceof FormInterface ?
            $this->render(
                $this->templateService->getTemplatePath(TemplateService::FORMAT_ADD, [$type]),
                [
                    'type' => $type,
                    'configuration' => $request->attributes->get('configuration'),
                    'templates' => $this->configuration['templates'],
                    'entity' => $form->getData(),
                    'form' => $form->createView(),
                ]
            ) :
            $this->redirect($this->getTargetPath($request->getSession(), 'main'));
    }

    /**
     * @throws Exception
     */
    public function edit(Request $request, string $type): Response
    {
        $entity = $request->attributes->get('entity');

        $form = $this->formService->getAdminForm($request, $type, $entity);

        return $form instanceof FormInterface ?
            $this->render(
                $this->templateService->getTemplatePath(TemplateService::FORMAT_EDIT, [$type]),
                [
                    'type' => $type,
                    'configuration' => $request->attributes->get('configuration'),
                    'templates' => $this->configuration['templates'],
                    'entity' => $entity,
                    'form' => $form->createView(),
                ]
            ) :
            $this->redirect($this->getTargetPath($request->getSession(), 'main'));
    }

    public function delete(Request $request): RedirectResponse
    {
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->remove($request->attributes->get('entity'));
        $entityManager->flush();

        return $this->redirect($this->getTargetPath($request->getSession(), 'main'));
    }

    public function all(Request $request): JsonResponse
    {
        return new JsonResponse($this->serializer->serialize(
            $this->managerRegistry
                ->getRepository($request->attributes->get('configuration')['class'])
                ->createQueryBuilder('entity')
                ->getQuery()
                ->execute(),
            'json',
            [
                'groups' => 'default'
            ]
        ));
    }
}
