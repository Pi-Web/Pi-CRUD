<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use PiWeb\PiCRUD\Tools\PiCrudUtils;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

final class SecurityService
{
    private const ROUTE_PERMISSIONS = [
        PiCrudUtils::ROUTE_SHOW => 'show',
        PiCrudUtils::ROUTE_LIST => 'list',
        PiCrudUtils::ROUTE_ADMIN => 'admin',
        PiCrudUtils::ROUTE_ADD => 'add',
        PiCrudUtils::ROUTE_EDIT => 'edit',
        PiCrudUtils::ROUTE_DELETE => 'delete',
    ];
    
    public function __construct(
        private readonly Security $security,
    ) {
    }

    /**
     * @param string $route
     * @param mixed|null $subject
     * @return void
     */
    public function checkPermission(string $route, mixed $subject = null): void
    {
        if (!isset(self::ROUTE_PERMISSIONS[$route])) {
            return;
        }

        if (!$this->security->isGranted(self::ROUTE_PERMISSIONS[$route], $subject)) {
            $exception = new AccessDeniedException();
            $exception->setAttributes(self::ROUTE_PERMISSIONS[$route]);
            $exception->setSubject($subject);

            throw $exception;
        }
    }
}
