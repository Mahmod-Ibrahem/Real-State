import {BuildingOffice2Icon, Squares2X2Icon} from '@heroicons/vue/24/outline';

export const OWNER_NAV = {
    home: 'supplier.dashboard',
    label: 'Supplier',
    profileRoute: null,
    groups: [
        {
            label: 'My listings',
            items: [
                {label: 'Dashboard', route: 'supplier.dashboard', icon: Squares2X2Icon},
                {label: 'My properties', route: 'supplier.properties.index', icon: BuildingOffice2Icon},
            ],
        },
    ],
};
