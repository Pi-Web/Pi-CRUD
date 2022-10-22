<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Config;

enum PiCrudRoute: string
{
    case DASHBOARD = 'pi_crud_dashboard';
    case SHOW = 'pi_crud_show';
    case LIST = 'pi_crud_list';
    case ADMIN = 'pi_crud_admin';
    case ADD = 'pi_crud_add';
    case EDIT = 'pi_crud_edit';
    case DELETE = 'pi_crud_delete';
}