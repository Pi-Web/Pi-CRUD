<?php

namespace PiWeb\PiCRUD\Block;

use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ItemBlock
 * @package PiWeb\PiCRUD\Block
 */
final class ItemBlock extends AbstractBlockService
{
    /**
     * @param BlockContextInterface $blockContext
     * @param Response|null $response
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        $settings = $blockContext->getSettings();

        $template = '@PiCRUD/item_' . $settings['mode'] . '.html.twig';
        if ($this->getTwig()->getLoader()->exists('entities/item/' . $settings['type'] . '_' . $settings['mode'] . '.html.twig')) {
            $template = 'entities/item/' . $settings['type'] . '_' . $settings['mode'] . '.html.twig';
        }

        return $this->renderResponse($template, [
            'type' => $settings['type'],
            'entity' => $settings['item'],
            'attr' => $settings['attr'],
        ], $response);
    }

    /**
     * @param OptionsResolver $resolver
     */
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
