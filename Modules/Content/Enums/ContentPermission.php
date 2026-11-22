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

    case ViewBrokers = 'view brokers';
    case CreateBrokers = 'create brokers';
    case EditBrokers = 'edit brokers';
    case DeleteBrokers = 'delete brokers';
}
