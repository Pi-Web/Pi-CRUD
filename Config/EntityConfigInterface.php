<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Config;

use PiWeb\PiCRUD\Component\ActionComponent;
use PiWeb\PiCRUD\Component\FieldComponent;
use PiWeb\PiCRUD\Enum\Crud\CrudPageEnum;
use PiWeb\PiCRUD\Enum\Crud\EntityOptionEnum;

interface EntityConfigInterface
{
    const OPTION_PAGINATION = 20;

    const TEMPLATE_ADMIN_LIST = '@PiCRUD/admin/list.html.twig';
    const TEMPLATE_ADMIN_ADD = '@PiCRUD/admin/add.html.twig';
    const TEMPLATE_ADMIN_EDIT = '@PiCRUD/admin/edit.html.twig';

    public function getEntityName(): string;

    public function getEntityClass(): string;

    public function getOption(EntityOptionEnum $entityOption): mixed;

    public function getTemplate(CrudPageEnum $crudPage): string;

    /**
     * @return FieldComponent[]
     */
    public function getProperties(CrudPageEnum $crudPage): array;

    /**
     * @return ActionComponent[]
     */
    public function getActions(CrudPageEnum $crudPageEnum): array;
}
