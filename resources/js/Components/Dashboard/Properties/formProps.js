export const PROPERTY_FORM_PROPS = {
    property_types: {type: Object, required: true},
    classifications: {type: Object, required: true},
    countries: {type: Object, required: true},
    cities: {type: Object, required: true},
    areas: {type: Object, required: true},
    purposes: {type: Array, required: true},
    statuses: {type: Array, required: true},
    installment_frequencies: {type: Array, required: true},
    features: {type: Object, required: true},
};

export const EDIT_PROPERTY_PROPS = {
    ...PROPERTY_FORM_PROPS,
    property: {type: Object, required: true},
    gallery: {type: Array, required: true},
    review_statuses: {type: Array, required: true},
};
