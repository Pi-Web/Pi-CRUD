<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use PiWeb\PiCRUD\Entity\Log;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class ExceptionEventSubscriber
 * @package PiWeb\PiCRUD\EventSubscriber
 */
class ExceptionEventSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function onException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $log = new Log();
        $log
            ->setCode($exception->getCode())
            ->setTitle($exception->getMessage())
            ->setContent($exception->getTraceAsString());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onException',
        ];
    }
}
