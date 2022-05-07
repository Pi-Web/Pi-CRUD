<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use PiWeb\PiBreadcrumb\Model\Breadcrumb;
use PiWeb\PiCRUD\Tools\PiCrudUtils;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class BreadcrumbService
 * @package PiWeb\PiCRUD\Service
 */
final class BreadcrumbService
{
    public function __construct(
        private Breadcrumb $breadcrumb,
        private TranslatorInterface $translator,
        private RouterInterface $router,
    ) {
    }

    /**
     * @param string $route
     * @param string|null $entityType
     * @param int|null $entityId
     * @param string|null $entitySlug
     * @param string|null $entityLabel
     * @return void
     */
    public function generate(string $route, ?string $entityType, ?int $entityId, ?string $entitySlug, ?string $entityLabel): void
    {
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

    /**
     * @return void
     */
    private function generateDashboard(): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.dashboard.title'),
            $this->router->generate('pi_crud_dashboard')
        );
    }

    /**
     * @param string $entityType
     * @return void
     */
    private function generateAdmin(string $entityType): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.admin.title', ['entity_label' => $entityType]),
            $this->router->generate('pi_crud_admin', ['type' => $entityType])
        );
    }

    /**
     * @param string $entityType
     * @return void
     */
    private function generateAdd(string $entityType): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.form.add.title', ['entity_label' => $entityType]),
            $this->router->generate('pi_crud_add', ['type' => $entityType])
        );
    }

    /**
     * @param string $entityType
     * @param int $entityId
     * @return void
     */
    private function generateEdit(string $entityType, int $entityId): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.form.edit.title', ['entity_label' => $entityType]),
            $this->router->generate('pi_crud_edit', ['type' => $entityType, 'id' => $entityId])
        );
    }

    /**
     * @param string $entityType
     * @return void
     */
    private function generateList(string $entityType): void
    {
        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.list.title', ['entity_label' => $entityType]),
            $this->router->generate('pi_crud_list', ['type' => $entityType])
        );
    }

    /**
     * @param string $entityType
     * @param int $entityId
     * @param string $entitySlug
     * @param string $entityLabel
     * @return void
     */
    private function generateShow(string $entityType, int $entityId, string $entitySlug, string $entityLabel): void
    {
        $this->breadcrumb->addItem(
            $entityLabel,
            $this->router->generate('pi_crud_show', ['type' => $entityType, 'id' => $entityId, 'slug' => $entitySlug])
        );
    }
}
