<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Config;

use PiWeb\PiCRUD\Component\ActionComponent;
use PiWeb\PiCRUD\Component\FieldComponent;
use PiWeb\PiCRUD\Enum\Crud\CrudPageEnum;
use PiWeb\PiCRUD\Enum\Crud\EntityOptionEnum;
use PiWeb\PiCRUD\Exception\ComponentException;
use PiWeb\PiCRUD\Factory\ComponentFactory;

class PiCrudEntityConfig implements EntityConfigInterface
{
    protected string $entityName;
    protected string $entityClass;

    protected array $classMetadata;
    protected array $propertiesReflectionAttributes;

    protected array $options;

    public function __construct(
        protected array $templates = [],
        protected array $properties = [],
        protected array $actions = [],
        private readonly ?ComponentFactory $componentFactory = null,
    ) {
    }

    public function initConfig(array $classMetadata = [], array $propertiesReflectionAttributes = []): void
    {
        if (!empty($classMetadata)) {
            $this->classMetadata = $classMetadata;
        }

        if (!empty($propertiesReflectionAttributes)) {
            $this->propertiesReflectionAttributes = $propertiesReflectionAttributes;
        }

        $this->entityName = $this->classMetadata['name'];
        $this->entityClass = $this->classMetadata['class'];

        $this->options = [
            EntityOptionEnum::PAGINATION->value => self::OPTION_PAGINATION,
        ];

        $this->templates = [
            CrudPageEnum::ADMIN_LIST->value => self::TEMPLATE_ADMIN_LIST,
            CrudPageEnum::ADMIN_ADD->value => self::TEMPLATE_ADMIN_ADD,
            CrudPageEnum::ADMIN_EDIT->value => self::TEMPLATE_ADMIN_EDIT,
        ];

        $this->configure();
    }

    public function configure(): void
    {
        foreach ($this->propertiesReflectionAttributes as $propertyName => $propertyReflectionAttributes) {
            try {
                $this->configureProperty(CrudPageEnum::ALL, $propertyName);
            } catch (ComponentException) {}
        }

        $this
            ->addAction(CrudPageEnum::ALL, new ActionComponent(
                type: ActionComponent::ACTION_EDIT,
            ))
            ->addAction(CrudPageEnum::ALL, new ActionComponent(
                type: ActionComponent::ACTION_DELETE,
            ))
        ;
    }

    /**
     * @throws ComponentException
     */
    protected function configureProperty(CrudPageEnum $crudPage, string $propertyName, ?FieldComponent $fieldComponent = null): self
    {
        /** @var FieldComponent $fieldComponent */
        $fieldComponent = $this->componentFactory->configure(
            $propertyName,
            $fieldComponent,
            $this->propertiesReflectionAttributes[$propertyName] ?? []
        );
        $this->addProperty($crudPage, $fieldComponent);

        return $this;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function setEntityName(string $entityName): self
    {
        $this->entityName = $entityName;

        return $this;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getOption(EntityOptionEnum $entityOption): mixed
    {
        return $this->options[$entityOption->value] ?? null;
    }

    public function addOption(EntityOptionEnum $entityOption, mixed $option): self
    {
        $this->options[$entityOption->value] = $option;

        return $this;
    }

    public function getClassMetadata(): array
    {
        return $this->classMetadata;
    }

    public function setClassMetadata(array $classMetadata): self
    {
        $this->classMetadata = $classMetadata;

        return $this;
    }

    public function getPropertiesReflectionAttributes(): array
    {
        return $this->propertiesReflectionAttributes;
    }

    public function setPropertiesReflectionAttributes(array $propertiesReflectionAttributes): self
    {
        $this->propertiesReflectionAttributes = $propertiesReflectionAttributes;
        return $this;
    }

    public function getTemplates(): array
    {
        return $this->templates;
    }

    public function setTemplates(array $templates): self
    {
        $this->templates = $templates;
        return $this;
    }

    public function getTemplate(CrudPageEnum $crudPage): string
    {
        return $this->templates[$crudPage->value];
    }

    public function addTemplate(CrudPageEnum $crudPage, string $template): self
    {
        $this->templates[$crudPage->value] = $template;

        return $this;
    }

    public function getProperties(CrudPageEnum $crudPage): array
    {
        return $this->properties[$crudPage->value] ?? [];
    }

    private function addProperty(CrudPageEnum $crudPage, FieldComponent $property): self
    {
        if (CrudPageEnum::ALL === $crudPage) {
            $this->addProperty(CrudPageEnum::ADMIN, $property);
        } elseif (CrudPageEnum::ADMIN === $crudPage) {
            $this->addProperty(CrudPageEnum::ADMIN_LIST, $property);
            $this->addProperty(CrudPageEnum::ADMIN_FORM, $property);
        } elseif (CrudPageEnum::ADMIN_FORM === $crudPage) {
            $this->addProperty(CrudPageEnum::ADMIN_EDIT, $property);
            $this->addProperty(CrudPageEnum::ADMIN_ADD, $property);
        } else {
            $this->properties[$crudPage->value][] = $property;
        }

        return $this;
    }

    public function getActions(CrudPageEnum $crudPage): array
    {
        return $this->actions[$crudPage->value] ?? [];
    }

    protected function addAction(CrudPageEnum $crudPage, ActionComponent $action): self
    {
        if (CrudPageEnum::ALL === $crudPage) {
            $this->addAction(CrudPageEnum::ADMIN, $action);
        } elseif (CrudPageEnum::ADMIN === $crudPage) {
            $this->addAction(CrudPageEnum::ADMIN_LIST, $action);
            $this->addAction(CrudPageEnum::ADMIN_FORM, $action);
        } elseif (CrudPageEnum::ADMIN_FORM === $crudPage) {
            $this->addAction(CrudPageEnum::ADMIN_EDIT, $action);
            $this->addAction(CrudPageEnum::ADMIN_ADD, $action);
        } else {
            $this->actions[$crudPage->value][] = $action;
        }

        return $this;
    }
}
