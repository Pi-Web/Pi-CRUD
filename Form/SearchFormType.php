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

final class SearchFormType extends AbstractType
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'type' => null,
        ]);
    }
}
