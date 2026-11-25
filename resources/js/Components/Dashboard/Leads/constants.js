import {
    ChatBubbleLeftRightIcon,
    ChatBubbleOvalLeftEllipsisIcon,
    PhoneIcon,
    TableCellsIcon,
    UserPlusIcon,
    ViewColumnsIcon,
} from '@heroicons/vue/24/outline';

// How the visitor got in touch (LeadSource on the server)
export const SOURCE_ICONS = {
    form: ChatBubbleLeftRightIcon,
    call: PhoneIcon,
    whatsapp: ChatBubbleOvalLeftEllipsisIcon,
    manual: UserPlusIcon,
};

// One-tap Call / WhatsApp leads arrive without a name
export const leadName = (lead) => lead.full_name ?? 'Anonymous visitor';

// The leads page's ?view= options
export const LEAD_VIEWS = [
    {id: 'board', name: 'Board', icon: ViewColumnsIcon},
    {id: 'table', name: 'Table', icon: TableCellsIcon},
];
