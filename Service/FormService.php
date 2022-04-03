<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PiWeb\PiCRUD\Event\EntityEvent;
use PiWeb\PiCRUD\Event\FilterEvent;
use PiWeb\PiCRUD\Event\PiCrudEvents;
use PiWeb\PiCRUD\Form\EntityFormType;
use PiWeb\PiCRUD\Form\SearchFormType;
use PiWeb\PiCRUD\Tools\EntityManager as PiCrudEntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 * Class FormService
 * @package PiWeb\PiCRUD\Service
 */
final class FormService
{
    public function __construct(
        private PiCrudEntityManager $piCrudEntityManager,
        private FormFactoryInterface $formFactory,
        private EventDispatcherInterface $dispatcher,
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getSearchForm(Request $request, string $type, QueryBuilder $queryBuilder): FormInterface
    {
        $searchEntity = $this->piCrudEntityManager->create($type);

        $searchForm = $this->formFactory->create(SearchFormType::class, $searchEntity, ['type' => $type]);
        $searchForm->handleRequest($request);

        foreach ($searchForm->all() as $field) {
            if (!empty($searchEntity->{'get' . $field->getName()}())) {
                $operator = $field->getConfig()->getOption('attr')['operator'] ?? '=';

                $expression = $queryBuilder
                    ->expr()
                    ->orX(sprintf('entity.%s %s :%s', $field->getName(), $operator, $field->getName()));

                $event = new FilterEvent($this->security->getUser(), $type, $queryBuilder, $expression, $field->getName());
                $this->dispatcher->dispatch($event, PiCrudEvents::POST_FILTER_QUERY_BUILDER);

                $queryBuilder->andWhere($event->getComposite());
                $queryBuilder->setParameter(
                    $field->getName(),
                    $searchEntity->{'get'.$field->getName()}()
                );
            }
        }

        return $searchForm;
    }

    public function getAdminForm(Request $request, string $type, $entity = null): ?FormInterface
    {
        $isNew = false;

        if (empty($entity)) {
            $entity = $this->piCrudEntityManager->create($type);
            $this->dispatcher->dispatch(new EntityEvent($type, $entity, $request->query->all()), PiCrudEvents::POST_ENTITY_CREATE);
            $isNew = true;
        }

        $form = $this->formFactory->create(EntityFormType::class, $entity, ['type' => $type]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatcher->dispatch(
                new GenericEvent($entity),
                $isNew ? PiCrudEvents::PRE_ENTITY_PERSIST : PiCrudEvents::PRE_ENTITY_UPDATE
            );

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->dispatcher->dispatch(
                new GenericEvent($entity),
                $isNew ? PiCrudEvents::POST_ENTITY_PERSIST : PiCrudEvents::POST_ENTITY_UPDATE
            );

            return null;
        }

        return $form;
    }
}
