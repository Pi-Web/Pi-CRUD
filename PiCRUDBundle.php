<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD;

use PiWeb\PiCRUD\DependencyInjection\StructuredDataTransformerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PiCRUDBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new StructuredDataTransformerCompilerPass());
    }
}
