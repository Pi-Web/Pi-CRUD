<?php

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

final class PiCrudEvents
{
    public const POST_LIST_QUERY_BUILDER =      'pi_crud.post_list_query_builder';
    public const POST_ADMIN_QUERY_BUILDER =     'pi_crud.post_admin_query_builder';
}
