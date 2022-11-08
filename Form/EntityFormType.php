<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Form;

use Exception;
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
        $properties = $this->entityManager->getEntity($options['type'])['properties'];
        foreach ($properties as $name => $property) {
            if (empty($property['form'])) {
                continue;
            }

            $formBuilder->add($name);

            $this->eventDispatcher->dispatch(new FormEvent($name, $property, $formBuilder, $options), PiCrudEvents::POST_FORM_BUILDER_ADD);
        }
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'type' => null,
        ]);
    }
}
