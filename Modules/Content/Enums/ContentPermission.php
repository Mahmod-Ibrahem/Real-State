<?php

namespace Modules\Content\Enums;

use App\Enums\Concerns\IsPermission;

enum ContentPermission: string
{
    use IsPermission;

    case ViewFaqs = 'view faqs';
    case CreateFaqs = 'create faqs';
    case EditFaqs = 'edit faqs';
    case DeleteFaqs = 'delete faqs';
}
