<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use Doctrine\ORM\EntityManagerInterface;
use PiWeb\PiCRUD\Service\StructuredDataService;
use PiWeb\PiCRUD\Service\TemplateService;
use PiWeb\PiCRUD\Tools\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class BlockController
 * @package PiWeb\PiCRUD\Controller
 */
class BlockController extends AbstractController
{
    use TargetPathTrait;

    /**
     * BlockController constructor.
     */
    public function __construct(
        private TemplateService $templateService,
        private StructuredDataService $structuredDataService,
    ) {
    }

    public function itemBlockAction(
        Request $request,
        int $id,
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
