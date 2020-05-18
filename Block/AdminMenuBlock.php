<?php

namespace PiWeb\PiCRUD\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Twig\Environment;
use PiWeb\PiCRUD\Tools\EntityManager;

final class AdminMenuBlock extends AbstractBlockService
{
    private array $configuration;

    private EntityManager $entityManager;

    public function __construct(Environment $environment, EntityManager $entityManager, array $configuration = [])
    {
        parent::__construct($environment);

        $this->configuration = $configuration;
        $this->entityManager = $entityManager;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        return $this->renderResponse('@PiCRUD/menu.html.twig', [
            'entities' => $this->entityManager->getEntities()
        ], $response);
    }
}
