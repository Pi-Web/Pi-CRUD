<?php

namespace PiWeb\PiCRUD\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use App\Repository\MenuRepository;
use Twig\Environment;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ItemBlock extends AbstractBlockService
{
    private $menuRepository;

    private $configuration;

    public function __construct(Environment $environment, array $configuration = [])
    {
        parent::__construct($environment);

        $this->configuration = $configuration;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        $settings = $blockContext->getSettings();

        $template = '@PiCRUD/item_' . $settings['mode'] . '.html.twig';
        if ($this->getTwig()->getLoader()->exists('entities/item/' . $settings['type'] . '_' . $settings['mode'] . '.html.twig')) {
            $template = 'entities/item/' . $settings['type'] . '_' . $settings['mode'] . '.html.twig';
        }

        return $this->renderResponse($template, [
            'type' => $settings['type'],
            'configuration' => $this->configuration['entities'],
            'entity' => $settings['item'],
            'attr' => $settings['attr'],
        ], $response);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'item' => null,
            'type' => null,
            'mode' => 'default',
            'attr' => ['class' => 'col-12 col-md-6 col-lg-4']
        ]);
    }
}
