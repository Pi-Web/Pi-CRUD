<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component;

final class ActionComponent implements Component
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/action_%s.html.twig';

    # Actions
    public const ACTION_PREVIEW = 'preview';
    public const ACTION_EDIT = 'edit';
    public const ACTION_DELETE = 'delete';

    public function __construct(
        public readonly string $type,
        public readonly string $class = '',
        protected readonly ?string $template = null,
    ) {
    }

    public function getTemplate(): string
    {
        return $this->template ?? sprintf(self::DEFAULT_TEMPLATE, $this->type);
    }
}