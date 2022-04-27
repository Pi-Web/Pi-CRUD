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
use PiWeb\PiBreadcrumb\Model\Breadcrumb;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdminController
 * @package PiWeb\PiCRUD\Controller
 */
class AdminController extends AbstractController
{
    use TargetPathTrait;

    /**
     * CRUDController constructor.
     * @param Breadcrumb $breadcrumb
     * @param TranslatorInterface $translator
     * @param RequestStack $requestStack
     * @param TemplateService $templateService
     * @param EntityManager $entityManager
     */
    public function __construct(
        private Breadcrumb $breadcrumb,
        private TranslatorInterface $translator,
        private RequestStack $requestStack,
        private TemplateService $templateService,
        private EntityManager $entityManager,
        private EntityManagerInterface $em,
    ) {
    }

    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_WEBMASTER');

        $this->saveTargetPath($this->requestStack->getSession(), 'main', $request->getUri());

        $this->breadcrumb->addItem(
            $this->translator->trans('pi_crud.dashboard.title'),
            $this->generateUrl('pi_crud_dashboard')
        );

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
