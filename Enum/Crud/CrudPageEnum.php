<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Enum\Crud;

enum CrudPageEnum: string
{
    case ALL = 'pi_crud.all';

    # Admin pages
    case ADMIN = 'pi_crud.admin';
    case ADMIN_LIST = 'pi_crud.admin.list';
    case ADMIN_FORM = 'pi_crud.admin.form';
    case ADMIN_ADD = 'pi_crud.admin.add';
    case ADMIN_EDIT = 'pi_crud.admin.edit';
}