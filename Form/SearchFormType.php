<?php

namespace PiWeb\PiCRUD\Form;

use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Tools\EntityManager;
use PiWeb\PiCRUD\Event\FormEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;

/**
 * Class SearchFormType
 * @package PiWeb\PiCRUD\Form
 */
class SearchFormType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * EntityFormType constructor.
     * @param EntityManager $entityManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManager $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $properties = $this->entityManager->getEntity($options['type'])['properties'];
        foreach ($properties as $name => $property) {
            if (empty($property->search)) {
                continue;
            }

            $builder->add($name);

            $this->dispatcher->dispatch(new FormEvent($name, $property, $builder, $options), PiCrudEvents::POST_SEARCH_BUILDER_ADD);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => null,
        ]);
    }
}
