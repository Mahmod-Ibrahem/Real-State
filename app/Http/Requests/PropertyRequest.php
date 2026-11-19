<?php

namespace App\Http\Requests;

use App\Enums\ListingPurpose;
use App\Enums\PropertyStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'address' => 'required|string',
            'bedrooms' => 'nullable|numeric',
            'bathrooms' => 'nullable|numeric',
            'floor' => 'nullable|string',
            'garages' => 'nullable|numeric',
            'year_built' => 'required|numeric',
            'sqft' => 'required|numeric',
            'google_map' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'locale' => 'required|string|in:en,ar',
            'country_id' => 'required|exists:countries,id',
            // The city must belong to the chosen country, and the area to the chosen city.
            'city_id' => ['required', Rule::exists('cities', 'id')->where('country_id', $this->input('country_id'))],
            'area_id' => ['required', Rule::exists('areas', 'id')->where('city_id', $this->input('city_id'))],
            'property_type_id' => 'required|exists:property_types,id',
            'property_classification_id' => 'required|exists:property_classifications,id',
            'purpose' => ['required', Rule::enum(ListingPurpose::class)],
            // Sold only for sale listings, Rented only for rent listings.
            'status' => ['required', Rule::enum(PropertyStatus::class)->only(
                PropertyStatus::allowedFor(ListingPurpose::tryFrom((string) $this->input('purpose')))
            )],
            'feature_ids' => 'required|exists:features,id',
        ];
        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048';
            $rules['propertyImages'] = 'required|array';
            $rules['propertyImages.*'] = 'required|image';
        } else {
            $rules['image'] = 'nullable|image';
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'city_id.exists' => 'The selected city is not in the selected country.',
            'area_id.exists' => 'The selected area is not in the selected city.',
            'status.Illuminate\Validation\Rules\Enum' => 'This status does not match the listing purpose (Sold is for sale listings, Rented is for rent listings).',
        ];
    }
}
