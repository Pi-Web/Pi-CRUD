<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use PiWeb\PiBreadcrumb\Model\Breadcrumb;
use PiWeb\PiCRUD\Config\PiCrudRoute;
use PiWeb\PiCRUD\Tools\PiCrudUtils;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BreadcrumbService
{
    public function __construct(
        private readonly Breadcrumb $breadcrumb,
        private readonly TranslatorInterface $translator,
        private readonly RouterInterface $router,
    ) {
    }

    public function generate(
        string $route,
        ?string $entityType,
        ?int $entityId,
        ?string $entitySlug,
        ?string $entityLabel
    ): void {
        switch ($route) {
            case PiCrudUtils::ROUTE_DASHBOARD:
                $this->generateDashboard();
                break;
            case PiCrudUtils::ROUTE_SHOW:
                $this->generateList($entityType);
                $this->generateShow($entityType, $entityId, $entitySlug, $entityLabel);
                break;
            case PiCrudUtils::ROUTE_LIST:
                $this->generateList($entityType);
                break;
            case PiCrudUtils::ROUTE_ADMIN:
                $this->generateDashboard();
                $this->generateAdmin($entityType);
                break;
            case PiCrudUtils::ROUTE_ADD:
                $this->generateDashboard();
                $this->generateAdmin($entityType);
                $this->generateAdd($entityType);
                break;
            case PiCrudUtils::ROUTE_EDIT:
                $this->generateDashboard();
                $this->generateAdmin($entityType);
                $this->generateEdit($entityType, $entityId);
                break;
        }
    }

    private function generateDashboard(): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.dashboard.title'),
            $this->router->generate(PiCrudRoute::DASHBOARD->value)
        );
    }

    private function generateAdmin(string $entityType): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.admin.title', ['entity_label' => $entityType]),
            $this->router->generate(PiCrudRoute::ADMIN->value, ['type' => $entityType])
        );
    }

    private function generateAdd(string $entityType): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.form.add.title', ['entity_label' => $entityType]),
            $this->router->generate(PiCrudRoute::ADD->value, ['type' => $entityType])
        );
    }

    private function generateEdit(string $entityType, int $entityId): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.form.edit.title', ['entity_label' => $entityType]),
            $this->router->generate(PiCrudRoute::EDIT->value, ['type' => $entityType, 'id' => $entityId])
        );
    }

    private function generateList(string $entityType): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.list.title', ['entity_label' => $entityType]),
            $this->router->generate(PiCrudRoute::LIST->value, ['type' => $entityType])
        );
    }

    private function generateShow(string $entityType, int $entityId, string $entitySlug, string $entityLabel): void
    {
        $this->breadcrumb->addItem(
            $entityLabel,
            $this->router->generate(PiCrudRoute::SHOW->value, [
                'type' => $entityType,
                'id' => $entityId,
                'slug' => $entitySlug,
            ])
        );
    }
}
