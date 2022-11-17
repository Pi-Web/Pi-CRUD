<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Form;

use Exception;
use PiWeb\PiCRUD\Component\FieldComponent;
use PiWeb\PiCRUD\Enum\Crud\CrudPageEnum;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Tools\EntityManager;
use PiWeb\PiCRUD\Event\FormEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;

final class EntityFormType extends AbstractType
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        /** @var FieldComponent $property */
        foreach ($options['configuration']->getProperties($options['crudPage']) as $property) {
            $formBuilder->add($property->name, $property->getFormType(), $property->getFormOptions());
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'configuration' => null,
            'crudPage' => null,
        ]);
    }
}
