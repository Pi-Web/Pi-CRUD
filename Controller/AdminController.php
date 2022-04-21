<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use Exception;
use PiWeb\PiCRUD\Service\ConfigurationService;
use PiWeb\PiCRUD\Service\FormService;
use PiWeb\PiCRUD\Service\TemplateService;
use PiWeb\PiCRUD\Tools\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Event\QueryEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;
use PiWeb\PiBreadcrumb\Model\Breadcrumb;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AdminController
 * @package PiWeb\PiCRUD\Controller
 */
class AdminController extends AbstractController
{
    use TargetPathTrait;

    /**
     * CRUDController constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param Breadcrumb $breadcrumb
     * @param TranslatorInterface $translator
     * @param SerializerInterface $serializer
     * @param RequestStack $requestStack
     * @param ConfigurationService $configurationService
     * @param FormService $formService
     * @param TemplateService $templateService
     */
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private Breadcrumb $breadcrumb,
        private TranslatorInterface $translator,
        private SerializerInterface $serializer,
        private RequestStack $requestStack,
        private ConfigurationService $configurationService,
        private FormService $formService,
        private TemplateService $templateService,
        private EntityManager $entityManager,
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

        $groups = [];
        foreach ($entities as $entity) {
            $dashboardConfig = $entity['annotation']->dashboard;
            if (empty($dashboardConfig)) {
                continue;
            }

            $groups[$dashboardConfig['group'] ?? 'entity'][] = [
                'type' => $entity['annotation']->name,
            ];
        }


        return $this->render(
            '@PiCRUD/dashboard.html.twig',
            [
                'groups' => $groups
            ],
        );
    }
}
