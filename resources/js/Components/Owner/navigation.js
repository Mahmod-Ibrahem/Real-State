import {BuildingOffice2Icon, InboxIcon, Squares2X2Icon} from '@heroicons/vue/24/outline';

export const OWNER_NAV = {
    home: 'supplier.dashboard',
    label: 'Supplier',
    profileRoute: 'supplier.profile.show',
    groups: [
        {
            label: 'My listings',
            items: [
                {label: 'Dashboard', route: 'supplier.dashboard', icon: Squares2X2Icon},
                {label: 'My properties', route: 'supplier.properties.index', icon: BuildingOffice2Icon},
                {label: 'Leads', route: 'supplier.leads.index', icon: InboxIcon},
            ],
        },
    ],
};
