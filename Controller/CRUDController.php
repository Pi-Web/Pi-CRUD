<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use Exception;
use PiWeb\PiCRUD\Service\ConfigurationService;
use PiWeb\PiCRUD\Service\FormService;
use PiWeb\PiCRUD\Service\TemplateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PiWeb\PiCRUD\Event\QueryEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;
use PiWeb\PiBreadcrumb\Model\Breadcrumb;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CRUDController
 * @package PiWeb\PiCRUD\Controller
 */
class CRUDController extends AbstractController
{
    use TargetPathTrait;

    /**
     * CRUDController constructor.
     * @param array $configuration
     * @param EventDispatcherInterface $dispatcher
     * @param Breadcrumb $breadcrumb
     * @param TranslatorInterface $translator
     * @param SerializerInterface $serializer
     * @param SessionInterface $session
     * @param ConfigurationService $configurationService
     * @param FormService $formService
     * @param TemplateService $templateService
     */
    public function __construct(
        private array $configuration,
        private EventDispatcherInterface $dispatcher,
        private Breadcrumb $breadcrumb,
        private TranslatorInterface $translator,
        private SerializerInterface $serializer,
        private SessionInterface $session,
        private ConfigurationService $configurationService,
        private FormService $formService,
        private TemplateService $templateService,
    ) {
    }

    /**
     * @param Request $request
     * @param string $type
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function show(Request $request, string $type, int $id): Response
    {
        $configuration = $this->configurationService->getEntityConfiguration($type);

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.list.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_list', ['type' => $type])
        );

        $entity = $this->getDoctrine()
          ->getRepository($configuration['class'])
          ->find($id);

        $this->denyAccessUnlessGranted('show', $entity);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
          $entity,
          $this->generateUrl('pi_crud_show', ['type' => $type, 'id' => $id])
        );

        return $this->render(
            $this->templateService->getPath(TemplateService::FORMAT_SHOW, $type),
            [
                'entity' => $entity,
                'type' => $type
            ]
        );
    }

    /**
     * @param Request $request
     * @param string $type
     * @return Response
     * @throws Exception
     */
    public function list(Request $request, string $type): Response
    {
        $this->denyAccessUnlessGranted('list', $type);

        $configuration = $this->configurationService->getEntityConfiguration($type);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.list.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_list', ['type' => $type])
        );

        $queryBuilder = $this
            ->getDoctrine()
            ->getRepository($configuration['class'])
            ->createQueryBuilder('entity');

        $searchForm = $configuration['annotation']->search ?
            $this->formService->getSearchForm($request, $type, $queryBuilder) :
            null;

        $event = new QueryEvent($this->getUser(), $type, $queryBuilder);
        $this->dispatcher->dispatch($event, PiCrudEvents::POST_LIST_QUERY_BUILDER);

        return $this->render(
            $this->templateService->getPath(TemplateService::FORMAT_LIST, $type),
            [
                'type' => $type,
                'configuration' => $configuration,
                'entities' => $event->getQueryBuilder()->getQuery()->execute(),
                'templates' => $this->configuration['templates'],
                'searchForm' => $searchForm instanceof FormInterface ?
                    $searchForm->createView() :
                    null,
            ]
        );
    }

    /**
     * @param Request $request
     * @param string $type
     * @return Response
     * @throws Exception
     */
    public function admin(Request $request, string $type): Response
    {
        $this->denyAccessUnlessGranted('admin', $type);

        $configuration = $this->configurationService->getEntityConfiguration($type);

        $this->saveTargetPath($this->session, 'main', $request->getUri());

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.admin.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_admin', ['type' => $type])
        );

        $queryBuilder = $this->getDoctrine()
          ->getRepository($configuration['class'])
          ->createQueryBuilder('entity');

        $event = new QueryEvent($this->getUser(), $type, $queryBuilder);
        $this->dispatcher->dispatch($event, PiCrudEvents::POST_ADMIN_QUERY_BUILDER);

        return $this->render(
            $this->templateService->getPath(TemplateService::FORMAT_ADMIN, $type),
            [
                'type' => $type,
                'configuration' => $configuration,
                'templates' => $this->configuration['templates'],
                'entities' => $event->getQueryBuilder()->getQuery()->execute()
            ]
        );
    }

    /**
     * @param Request $request
     * @param string $type
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request, string $type): RedirectResponse|Response
    {
        $configuration = $this->configurationService->getEntityConfiguration($type);

        $this->denyAccessUnlessGranted('add', $type);

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.admin.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_admin', ['type' => $type])
        );

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.form.add.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_add', ['type' => $type])
        );

        $form = $this->formService->getAdminForm($request, $type);

        return $form instanceof FormInterface ?
            $this->render(
                $this->templateService->getPath(TemplateService::FORMAT_ADD, $type),
                [
                    'type' => $type,
                    'configuration' => $configuration,
                    'templates' => $this->configuration['templates'],
                    'entity' => $form->getData(),
                    'form' => $form->createView(),
                ]
            ) :
            $this->redirect($this->getTargetPath($this->session, 'main'));
    }

    /**
     * @param Request $request
     * @param string $type
     * @param int|null $id
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(Request $request, string $type, ?int $id): RedirectResponse|Response
    {
        $configuration = $this->configurationService->getEntityConfiguration($type);

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.admin.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_admin', ['type' => $type])
        );

        $this->breadcrumb->addItem(
          $this->translator->trans('pi_crud.form.edit.title', ['entity_label' => $type]),
          $this->generateUrl('pi_crud_add', ['type' => $type])
        );

        $entity = $this->getDoctrine()
          ->getRepository($configuration['class'])
          ->find($id);

        $this->denyAccessUnlessGranted('edit', $entity);

        $form = $this->formService->getAdminForm($request, $type, $entity);

        return $form instanceof FormInterface ?
            $this->render(
                $this->templateService->getPath(TemplateService::FORMAT_EDIT, $type),
                [
                    'type' => $type,
                    'configuration' => $configuration,
                    'templates' => $this->configuration['templates'],
                    'entity' => $entity,
                    'form' => $form->createView(),
                ]
            ) :
            $this->redirect($this->getTargetPath($this->session, 'main'));
    }

    /**
     * @param string $type
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(string $type, int $id): RedirectResponse
    {
        $configuration = $this->configurationService->getEntityConfiguration($type);

        $entity = $this->getDoctrine()
            ->getRepository($configuration['class'])
            ->find($id);

        $this->denyAccessUnlessGranted('delete', $entity);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirect($this->getTargetPath($this->session, 'main'));
    }

    /**
     * @param string $type
     * @return JsonResponse
     * @throws Exception
     */
    public function all(string $type): JsonResponse
    {
        $configuration = $this->configurationService->getEntityConfiguration($type);

        return new JsonResponse($this->serializer->serialize(
            $this
                ->getDoctrine()
                ->getRepository($configuration['class'])
                ->createQueryBuilder('entity')
                ->getQuery()
                ->execute(),
            'json',
            [
                'groups' => 'default'
            ]
        ));
    }
}
