<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PiWeb\PiCRUD\Event\PiCrudEvents;
use PiWeb\PiCRUD\Event\QueryEvent;
use PiWeb\PiCRUD\Event\QueryResultEvent;
use PiWeb\PiCRUD\Service\FormService;
use PiWeb\PiCRUD\Service\StructuredDataService;
use PiWeb\PiCRUD\Service\TemplateService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;

final class CRUDController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly array $configuration,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly SerializerInterface $serializer,
        private readonly FormService $formService,
        private readonly TemplateService $templateService,
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
     * @throws InvalidArgumentException
     */
    public function list(Request $request, string $type): Response
    {
        $configuration = $request->attributes->get('configuration');

        $queryBuilder = $this->entityManager
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $searchForm = isset($configuration['annotation']['search']) && $configuration['annotation']['search'] ?
            $this->formService->getSearchForm($request, $type, $queryBuilder) :
            null;

        $queryEvent = new QueryEvent($this->getUser(), $type, $queryBuilder);
        $this->eventDispatcher->dispatch($queryEvent, PiCrudEvents::POST_LIST_QUERY_BUILDER);

        $entities = $queryEvent->getQueryBuilder()->getQuery()->execute();

        $queryResultEvent = new QueryResultEvent($type, $entities);
        $this->eventDispatcher->dispatch($queryResultEvent, PiCrudEvents::POST_LIST_QUERY_RESULT);

        return $this->render(
            $this->templateService->getTemplatePath(TemplateService::FORMAT_LIST, [$type]),
            [
                'type' => $type,
                'configuration' => $configuration,
                'entities' => $queryResultEvent->getResults(),
                'templates' => $this->configuration['templates'],
                'searchForm' => $searchForm instanceof FormInterface ?
                    $searchForm->createView() :
                    null,
            ]
        );
    }

    public function delete(Request $request): RedirectResponse
    {
        $this->entityManager->remove($request->attributes->get('entity'));
        $this->entityManager->flush();

        return $this->redirect($this->getTargetPath($request->getSession(), 'main'));
    }

    public function all(Request $request): JsonResponse
    {
        return new JsonResponse($this->serializer->serialize(
            $this->entityManager
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
