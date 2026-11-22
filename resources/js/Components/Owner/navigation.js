import {BuildingOffice2Icon, Squares2X2Icon} from '@heroicons/vue/24/outline';

export const OWNER_NAV = {
    home: 'owner.dashboard',
    label: 'Owner',
    profileRoute: null,
    groups: [
        {
            label: 'My listings',
            items: [
                {label: 'Dashboard', route: 'owner.dashboard', icon: Squares2X2Icon, badge: 'unread_notifications'},
                {label: 'My properties', route: 'owner.properties.index', icon: BuildingOffice2Icon},
            ],
        },
    ],
};
