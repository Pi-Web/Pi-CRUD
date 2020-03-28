<?php

namespace PiWeb\PiCRUD\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PiWeb\PiCRUD\Event\AccessEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;

class PICRUDEventSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onAccess(AccessEvent $event): void
    {
        if (!array_key_exists($event->getType(), $event->getConfiguration()['entities'])) {
            throw new NotFoundHttpException();
        }

        if (array_key_exists('permission', $event->getConfiguration()['entities'][$event->getType()])) {
            if (!$this->security->isGranted($event->getConfiguration()['entities'][$event->getType()]['permission'])) {
                throw new AccessDeniedHttpException();
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'owp_crud_admin.access' => ['onAccess']
        ];
    }
}
