<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

final class PiCrudEvents
{
    public const POST_LIST_QUERY_BUILDER    = 'pi_crud.post_list_query_builder';
    public const POST_ADMIN_QUERY_BUILDER   = 'pi_crud.post_admin_query_builder';
    public const POST_FILTER_QUERY_BUILDER  = 'pi_crud.post_filter_query_builder';

    public const POST_LIST_QUERY_RESULT    = 'pi_crud.post_list_query_result';

    public const POST_FORM_BUILDER_ADD      = 'pi_crud.post_form_builder_add';
    public const POST_SEARCH_BUILDER_ADD    = 'pi_crud.post_search_builder_add';

    public const POST_ENTITY_CREATE         = 'pi_crud.post_entity_create';
    public const PRE_ENTITY_PERSIST         = 'app.entity.pre_persist';
    public const PRE_ENTITY_UPDATE          = 'app.entity.pre_update';
    public const POST_ENTITY_PERSIST        = 'app.entity.post_persist';
    public const POST_ENTITY_UPDATE         = 'app.entity.post_update';
}
