<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use PiWeb\PiCRUD\Service\StructuredDataService;
use PiWeb\PiCRUD\Service\TemplateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class BlockController extends AbstractController
{
    use TargetPathTrait;

    /**
     * BlockController constructor.
     */
    public function __construct(
        private readonly TemplateService $templateService,
        private readonly StructuredDataService $structuredDataService,
    ) {
    }

    public function itemBlockAction(
        Request $request,
        string $type,
        string $format,
    ): Response {
        return $this->render(
            $this->templateService->getTemplatePath(TemplateService::FORMAT_ITEM, [$type, $format], $format),
            [
                'entity' => $request->attributes->get('entity'),
                'type' => $type,
                'attr' => $request->query->get('attr'),
                'structuredData' => $this->structuredDataService->getStructuredData($request->attributes->get('entity')),
            ]
        );
    }
}
