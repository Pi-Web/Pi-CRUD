<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Form;

use Exception;
use PiWeb\PiCRUD\Component\FieldComponent;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EntityFormType extends AbstractType
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FieldComponent $property */
        foreach ($options['configuration']->getProperties($options['crudPage']) as $property) {
            $builder->add($property->getName(), $property->getFormType(), $property->getFormOptions());
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'configuration' => null,
            'crudPage' => null,
        ]);
    }
}
