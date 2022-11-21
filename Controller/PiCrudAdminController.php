<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PiWeb\PiCRUD\Config\EntityConfigInterface;
use PiWeb\PiCRUD\Enum\Crud\CrudPageEnum;
use PiWeb\PiCRUD\Enum\Crud\EntityOptionEnum;
use PiWeb\PiCRUD\Service\FormService;
use PiWeb\PiCRUD\Service\StructuredDataService;
use PiWeb\PiCRUD\Service\TemplateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;

final class PiCrudAdminController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly SerializerInterface $serializer,
        private readonly FormService $formService,
        private readonly TemplateService $templateService,
        private readonly StructuredDataService $structuredDataService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function list(Request $request, string $type): Response
    {
        /** @var EntityConfigInterface $configuration */
        $configuration = $request->attributes->get('configuration');

        $queryBuilder = $this->entityManager
            ->getRepository($configuration->getEntityClass())
            ->createQueryBuilder('entity');

        if (0 < $pagination = $configuration->getOption(EntityOptionEnum::PAGINATION)) {
            $paginationData = [
                'page' => $request->query->get('_page', 1),
                'total' => (clone $queryBuilder)
                    ->select('count(entity)')
                    ->getQuery()
                    ->getSingleScalarResult(),
                'byPage' => $pagination,
            ];

            $queryBuilder
                ->setFirstResult($pagination * ($request->query->get('_page', 1) - 1))
                ->setMaxResults($pagination);
        }

        return $this->render(
            $configuration->getTemplate(CrudPageEnum::ADMIN_LIST),
            [
                'page' => CrudPageEnum::ADMIN_LIST,
                'configuration' => $configuration,
                'entities' => $queryBuilder
                    ->getQuery()
                    ->execute(),
                'pagination' => $paginationData ?? [],
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function add(Request $request, string $type): Response
    {
        $configuration = $request->attributes->get('configuration');

        $form = $this->formService->getAdminForm($request, $configuration, CrudPageEnum::ADMIN_ADD);

        return $form instanceof FormInterface ?
            $this->render(
                $configuration->getTemplate(CrudPageEnum::ADMIN_ADD),
                [
                    'page' => CrudPageEnum::ADMIN_EDIT,
                    'configuration' => $configuration,
                    'entity' => $form->getData(),
                    'form' => $form->createView(),
                ]
            ) :
            $this->redirect($this->getTargetPath($request->getSession(), 'main'));
    }

    /**
     * @throws Exception
     */
    public function edit(Request $request, string $type): Response
    {
        $entity = $request->attributes->get('entity');
        $configuration = $request->attributes->get('configuration');

        $form = $this->formService->getAdminForm($request, $configuration, CrudPageEnum::ADMIN_EDIT, $entity);

        return $form instanceof FormInterface ?
            $this->render(
                $configuration->getTemplate(CrudPageEnum::ADMIN_EDIT),
                [
                    'page' => CrudPageEnum::ADMIN_EDIT,
                    'configuration' => $configuration,
                    'entity' => $entity,
                    'form' => $form->createView(),
                ]
            ) :
            $this->redirect($this->getTargetPath($request->getSession(), 'main'));
    }
}
