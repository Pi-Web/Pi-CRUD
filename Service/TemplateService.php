<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use Twig\Environment;

/**
 * Class FormService
 * @package PiWeb\PiCRUD\Service
 */
final class TemplateService
{
    public const PAGE_DASHBOARD = 'page.dashboard';

    public const FORMAT_SHOW = 'format.show';
    public const FORMAT_LIST = 'format.list';
    public const FORMAT_ADMIN = 'format.admin';
    public const FORMAT_ADD = 'format.add';
    public const FORMAT_EDIT = 'format.edit';

    public const DEFAULTS_FORMATS = [
        self::PAGE_DASHBOARD => '@PiCRUD/dashboard.html.twig',
        self::FORMAT_SHOW => '@PiCRUD/show.html.twig',
        self::FORMAT_LIST => '@PiCRUD/list.html.twig',
        self::FORMAT_ADMIN => '@PiCRUD/admin.html.twig',
        self::FORMAT_ADD => '@PiCRUD/add.html.twig',
        self::FORMAT_EDIT => '@PiCRUD/edit.html.twig',
    ];

    public const OVERLOADED_FORMATS = [
        self::PAGE_DASHBOARD => 'default/dashboard.html.twig',
        self::FORMAT_SHOW => 'entities/show/%s.html.twig',
        self::FORMAT_LIST => 'entities/list/%s.html.twig',
        self::FORMAT_ADMIN => 'entities/admin/%s.html.twig',
        self::FORMAT_ADD => 'entities/add/%s.html.twig',
        self::FORMAT_EDIT => 'entities/edit/%s.html.twig',
    ];

    public function __construct(
        private Environment $environment,
    ) {
    }

    public function getTemplatePath(string $page, ?string $type = null): string
    {
        $overloadedTemplatePath = empty($type) ?
            self::OVERLOADED_FORMATS[$page] :
            sprintf(self::OVERLOADED_FORMATS[$page], $type);

        return $this->environment->getLoader()->exists($overloadedTemplatePath) ?
            $overloadedTemplatePath :
            self::DEFAULTS_FORMATS[$page];
    }
}
