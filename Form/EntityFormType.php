<?php

namespace PiWeb\PiCRUD\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Tools\EntityManager;
use PiWeb\PiCRUD\Event\FormEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;

class EntityFormType extends AbstractType
{

    private array $configuration;

    private EntityManager $entityManager;

    private EventDispatcherInterface $dispatcher;

    public function __construct(array $configuration, EntityManager $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->configuration = $configuration;
        $this->dispatcher = $dispatcher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $properties = $this->entityManager->getEntity($options['type'])['properties'];
        foreach ($properties as $name => $property) {
            if ($property->form === null) {
                continue;
            }

            $builder->add($name);

            $this->dispatcher->dispatch(new FormEvent($name, $property, $builder, $options), PiCrudEvents::POST_FORM_BUILDER_ADD);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => null,
        ]);
    }
}
