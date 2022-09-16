<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Form;

use Exception;
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
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $properties = $this->entityManager->getEntity($options['type'])['properties'];
        foreach ($properties as $name => $property) {
            if (empty($property->form)) {
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
