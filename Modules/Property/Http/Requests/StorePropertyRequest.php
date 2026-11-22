<?php

namespace Modules\Property\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Property\Enums\ListingPurpose;
use Modules\Property\Enums\PropertyStatus;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'address' => ['required', 'string'],
            'bedrooms' => ['nullable', 'numeric'],
            'bathrooms' => ['nullable', 'numeric'],
            'floor' => ['nullable', 'string'],
            'garages' => ['nullable', 'numeric'],
            'year_built' => ['required', 'numeric'],
            'sqft' => ['required', 'numeric'],
            'google_map' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'locale' => ['required', 'string', 'in:en,ar'],
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', Rule::exists('cities', 'id')->where('country_id', $this->input('country_id'))],
            'area_id' => ['required', Rule::exists('areas', 'id')->where('city_id', $this->input('city_id'))],
            'property_type_id' => ['required', 'exists:property_types,id'],
            'property_classification_id' => ['required', 'exists:property_classifications,id'],
            'purpose' => ['required', Rule::enum(ListingPurpose::class)],
            'status' => ['required', Rule::enum(PropertyStatus::class)->only(
                PropertyStatus::allowedFor(ListingPurpose::tryFrom((string) $this->input('purpose')))
            )],
            'feature_ids' => ['required', 'exists:features,id'],
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg,webp', 'max:2048'],
            'gallery_images' => ['required', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,png,jpeg,gif,svg,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'city_id.exists' => __('The selected city is not in the selected country.'),
            'area_id.exists' => __('The selected area is not in the selected city.'),
            'status.Illuminate\Validation\Rules\Enum' => __('This status does not match the listing purpose (Sold is for sale listings, Rented is for rent listings).'),
        ];
    }
}
