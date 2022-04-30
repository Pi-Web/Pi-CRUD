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

/**
 * Class AdminController
 * @package PiWeb\PiCRUD\Controller
 */
class AdminController extends AbstractController
{
    use TargetPathTrait;

    /**
     * CRUDController constructor.
     * @param RequestStack $requestStack
     * @param TemplateService $templateService
     * @param EntityManager $entityManager
     */
    public function __construct(
        private RequestStack $requestStack,
        private TemplateService $templateService,
        private EntityManager $entityManager,
        private EntityManagerInterface $em,
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
                'count' => !empty($dashboardConfig['format']) && 'extanded' === $dashboardConfig['format'] ?
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
