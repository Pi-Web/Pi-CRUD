<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Block;

use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Twig\Environment;
use PiWeb\PiCRUD\Tools\EntityManager;

/**
 * Class AdminMenuBlock
 * @package PiWeb\PiCRUD\Block
 */
final class AdminMenuBlock extends AbstractBlockService
{
    /**
     * AdminMenuBlock constructor.
     * @param Environment $environment
     * @param EntityManager $entityManager
     */
    public function __construct(
        Environment $environment,
        private EntityManager $entityManager
    ) {
        parent::__construct($environment);
    }

    /**
     * @param BlockContextInterface $blockContext
     * @param Response|null $response
     * @return Response
     * @throws ReflectionException
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        return $this->renderResponse('@PiCRUD/menu.html.twig', [
            'entities' => $this->entityManager->getEntities()
        ], $response);
    }
}
