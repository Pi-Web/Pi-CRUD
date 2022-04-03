<?php

declare(strict_types=1);

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

        $overloadedTemplatePath = sprintf('entities/item/%s_%s.html.twig', $settings['type'], $settings['mode']);

        return $this->renderResponse(
            $this->getTwig()->getLoader()->exists($overloadedTemplatePath) ?
                $overloadedTemplatePath :
                sprintf('@PiCRUD/item_%s.html.twig', $settings['mode']),
            [
                'type' => $settings['type'],
                'entity' => $settings['item'],
                'attr' => $settings['attr'],
            ],
            $response
        );
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
