<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\EventSubscriber;

use Doctrine\Persistence\ManagerRegistry;
use PiWeb\PiCRUD\Service\BreadcrumbService;
use PiWeb\PiCRUD\Service\ConfigurationService;
use PiWeb\PiCRUD\Service\SecurityService;
use PiWeb\PiCRUD\Tools\PiCrudUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class KernelRequestEventSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    public function __construct(
        private BreadcrumbService $breadcrumbService,
        private ConfigurationService $configurationService,
        private ManagerRegistry $managerRegistry,
        private SecurityService $securityService,
    ) {
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $route = $request->get('_route');
        if (empty($route) || !str_starts_with($route, 'pi_crud')) {
            return;
        }

        $type = $request->get('type');
        $configuration = $this->loadConfiguration($request, $type);

        $id = (int) $request->get('id');
        $entity = $this->loadEntity($request, $configuration, $id);

        $this->securityService->checkPermission($route, $entity ?? $type);

        if (
            in_array($route, [
                PiCrudUtils::ROUTE_SHOW,
                PiCrudUtils::ROUTE_LIST,
                PiCrudUtils::ROUTE_ADMIN,
                PiCrudUtils::ROUTE_DASHBOARD,
            ])
        ) {
            $this->saveTargetPath($request->getSession(), 'main', $request->getUri());
        }

        $this->generateBreadcrumb(
            $route,
            $type,
            $id,
            !empty($entity) && method_exists($entity, 'getSlug') ? $entity->getSlug() : null,
            (string) $entity
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    private function loadConfiguration(Request $request, ?string $type): ?array
    {
        if (!empty($type)) {
            $configuration = $this->configurationService->getEntityConfiguration($type);
            $request->attributes->add([
                'configuration' => $configuration,
            ]);
        }

        return $configuration ?? null;
    }

    private function loadEntity(Request $request, ?array $configuration, ?int $id): ?object
    {
        if (!empty($configuration) && !empty($id)) {
            $entity = $this->managerRegistry
                ->getRepository($configuration['class'])
                ->find($id);

            $request->attributes->add([
                'entity' => $entity,
            ]);
        }

        return $entity ?? null;
    }

    private function generateBreadcrumb(string $route, ?string $type, ?int $id, ?string $slug, ?string $label): void
    {
        $this->breadcrumbService->generate($route, $type, $id, $slug, $label);
    }
}
