import {ChatBubbleLeftRightIcon, ChatBubbleOvalLeftEllipsisIcon, PhoneIcon, TableCellsIcon, ViewColumnsIcon} from '@heroicons/vue/24/outline';

// How the visitor got in touch (LeadSource on the server)
export const SOURCE_ICONS = {
    form: ChatBubbleLeftRightIcon,
    call: PhoneIcon,
    whatsapp: ChatBubbleOvalLeftEllipsisIcon,
};

// The leads page's ?view= options
export const LEAD_VIEWS = [
    {id: 'board', name: 'Board', icon: ViewColumnsIcon},
    {id: 'table', name: 'Table', icon: TableCellsIcon},
];
