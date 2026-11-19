// Admin navigation as data: the sidebar, the active state and the breadcrumb all read from here.
// A new admin section is one entry below. See ADMIN_UI.md §3.
import {
    BuildingOffice2Icon,
    BuildingLibraryIcon,
    ChatBubbleLeftRightIcon,
    DocumentTextIcon,
    GlobeAltIcon,
    InboxIcon,
    ListBulletIcon,
    MapPinIcon,
    QuestionMarkCircleIcon,
    RectangleStackIcon,
    Squares2X2Icon,
    SwatchIcon,
    TagIcon,
    UserGroupIcon,
} from '@heroicons/vue/24/outline';

export const NAV_GROUPS = [
    {
        label: 'Overview',
        items: [
            {label: 'Dashboard', route: 'dashboard', icon: Squares2X2Icon},
        ],
    },
    {
        label: 'Listings',
        items: [
            // Property images are managed from a property, so they keep "Properties" highlighted.
            {label: 'Properties', route: 'properties.index', icon: BuildingOffice2Icon, also: ['propertyImages']},
            {label: 'Property types', route: 'propertyTypes.index', icon: SwatchIcon},
            {label: 'Classifications', route: 'propertyClassifications.index', icon: TagIcon},
            {label: 'Features', route: 'propertyFeatures.index', icon: ListBulletIcon},
            {label: 'Feature types', route: 'feature-types.index', icon: RectangleStackIcon},
        ],
    },
    {
        label: 'Locations',
        items: [
            {label: 'Countries', route: 'countries.index', icon: GlobeAltIcon},
            {label: 'Cities', route: 'cities.index', icon: BuildingLibraryIcon},
            {label: 'Areas', route: 'areas.index', icon: MapPinIcon},
        ],
    },
    {
        label: 'Leads',
        items: [
            {label: 'Property leads', route: 'propertyLeads.index', icon: InboxIcon},
            {label: 'Contact messages', route: 'contactLeads.index', icon: ChatBubbleLeftRightIcon},
        ],
    },
    {
        label: 'Content',
        items: [
            {label: 'FAQs', route: 'faq.index', icon: QuestionMarkCircleIcon},
            {label: 'Site content', route: 'content.index', icon: DocumentTextIcon},
            {label: 'Brokers', route: 'broker.index', icon: UserGroupIcon},
        ],
    },
];

const resourceOf = (routeName) => String(routeName || '').split('.')[0];

/** The nav item (and its group) for a route name; 'cities.edit' → Cities. */
export function findNavItem(routeName) {
    const resource = resourceOf(routeName);
    for (const group of NAV_GROUPS) {
        const item = group.items.find(
            (entry) => resourceOf(entry.route) === resource || entry.also?.includes(resource)
        );
        if (item) return {group, item};
    }
    return null;
}

const ACTIONS = {create: 'New', edit: 'Edit', show: 'Details'};

/** Breadcrumb action label for a route name; 'cities.create' → 'New'. */
export const actionOf = (routeName) => ACTIONS[String(routeName || '').split('.')[1]] ?? '';
