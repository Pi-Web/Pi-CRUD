<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use Doctrine\ORM\EntityManagerInterface;
use PiWeb\PiCRUD\Service\TemplateService;
use PiWeb\PiCRUD\Tools\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class AdminController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly TemplateService $templateService,
        private readonly EntityManager $entityManager,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function index(Request $request): Response
    {
        $this->saveTargetPath($this->requestStack->getSession(), 'main', $request->getUri());

        $entities = $this->entityManager->getEntities();

        $items = [];
        foreach ($entities as $entity) {
            $dashboardConfig = $entity['annotation']->dashboard;
            if (empty($dashboardConfig)) {
                continue;
            }

            $items[$entity['annotation']->dashboard['order'] ?? $entity['annotation']->name] = [
                'name' => $entity['annotation']->name,
                'options' => $dashboardConfig,
                'count' => !empty($dashboardConfig['format']) && 'extended' === $dashboardConfig['format'] ?
                    $this->em->getRepository($entity['class'])->count([]) :
                    0,
            ];
        }

        ksort($items, SORT_NATURAL);

        return $this->render(
            $this->templateService->getTemplatePath(TemplateService::PAGE_DASHBOARD),
            [
                'items' => $items,
            ],
        );
    }
}
