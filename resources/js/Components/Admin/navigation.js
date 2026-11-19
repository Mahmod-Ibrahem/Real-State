// Admin navigation as data: the sidebar, the active state and the breadcrumb all read from here.
// A new admin section is one entry below. See ADMIN_UI.md §3.
import {
    BuildingOffice2Icon,
    BuildingLibraryIcon,
    ChatBubbleLeftRightIcon,
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
            {label: 'Dashboard', route: 'admin.dashboard', icon: Squares2X2Icon},
        ],
    },
    {
        label: 'Listings',
        items: [
            {label: 'Properties', route: 'admin.properties.index', icon: BuildingOffice2Icon},
            {label: 'Property types', route: 'admin.property-types.index', icon: SwatchIcon},
            {label: 'Classifications', route: 'admin.property-classifications.index', icon: TagIcon},
            {label: 'Features', route: 'admin.property-features.index', icon: ListBulletIcon},
            {label: 'Feature types', route: 'admin.feature-types.index', icon: RectangleStackIcon},
        ],
    },
    {
        label: 'Locations',
        items: [
            {label: 'Countries', route: 'admin.countries.index', icon: GlobeAltIcon},
            {label: 'Cities', route: 'admin.cities.index', icon: BuildingLibraryIcon},
            {label: 'Areas', route: 'admin.areas.index', icon: MapPinIcon},
        ],
    },
    {
        label: 'Leads',
        items: [
            {label: 'Property leads', route: 'admin.property-leads.index', icon: InboxIcon},
            {label: 'Contact messages', route: 'admin.contact-leads.index', icon: ChatBubbleLeftRightIcon},
        ],
    },
    {
        label: 'Content',
        items: [
            {label: 'FAQs', route: 'admin.faqs.index', icon: QuestionMarkCircleIcon},
            {label: 'Brokers', route: 'admin.brokers.index', icon: UserGroupIcon},
        ],
    },
];

// Admin route names are "admin.<resource>.<action>" (CLAUDE.md, Routing)
const segmentsOf = (routeName) => String(routeName || '').replace(/^admin\./, '').split('.');
const resourceOf = (routeName) => segmentsOf(routeName)[0];

/** The nav item (and its group) for a route name; 'admin.cities.edit' → Cities. */
export function findNavItem(routeName) {
    const resource = resourceOf(routeName);
    for (const group of NAV_GROUPS) {
        const item = group.items.find(
            (entry) => resourceOf(entry.route) === resource
        );
        if (item) return {group, item};
    }
    return null;
}

const ACTIONS = {create: 'New', edit: 'Edit'};

/** Breadcrumb action label for a route name; 'admin.cities.create' → 'New'. */
export const actionOf = (routeName) => ACTIONS[segmentsOf(routeName)[1]] ?? '';
