<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use Twig\Environment;

final class TemplateService
{
    public const PAGE_DASHBOARD = 'page.dashboard';

    public const FORMAT_SHOW = 'format.show';
    public const FORMAT_LIST = 'format.list';
    public const FORMAT_ADMIN = 'format.admin';
    public const FORMAT_ADD = 'format.add';
    public const FORMAT_EDIT = 'format.edit';
    public const FORMAT_ITEM = 'format.item';

    public const DEFAULTS_FORMATS = [
        self::PAGE_DASHBOARD => '@PiCRUD/dashboard.html.twig',
        self::FORMAT_SHOW => '@PiCRUD/show.html.twig',
        self::FORMAT_LIST => '@PiCRUD/list.html.twig',
        self::FORMAT_ADMIN => '@PiCRUD/admin.html.twig',
        self::FORMAT_ADD => '@PiCRUD/add.html.twig',
        self::FORMAT_EDIT => '@PiCRUD/edit.html.twig',
        self::FORMAT_ITEM => '@PiCRUD/item_%s.html.twig',
    ];

    public const OVERLOADED_FORMATS = [
        self::PAGE_DASHBOARD => 'default/dashboard.html.twig',
        self::FORMAT_SHOW => 'entities/show/%s.html.twig',
        self::FORMAT_LIST => 'entities/list/%s.html.twig',
        self::FORMAT_ADMIN => 'entities/admin/%s.html.twig',
        self::FORMAT_ADD => 'entities/add/%s.html.twig',
        self::FORMAT_EDIT => 'entities/edit/%s.html.twig',
        self::FORMAT_ITEM => 'entities/item/%s_%s.html.twig',
    ];

    public function __construct(
        private readonly Environment $environment,
    ) {
    }

    public function getTemplatePath(string $page, array $options = [], ?string $format = null): string
    {
        $overloadedTemplatePath = sprintf(self::OVERLOADED_FORMATS[$page], ...$options);

        return $this->environment->getLoader()->exists($overloadedTemplatePath) ?
            $overloadedTemplatePath :
            sprintf(self::DEFAULTS_FORMATS[$page], $format);
    }
}
