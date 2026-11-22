<?php

namespace Modules\Property\Services;

use App\Traits\ImagesUtility;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyImages;

final class PropertyGalleryService
{
    use ImagesUtility;

    private const IMAGE_TYPE = 'property';

    /** @param list<UploadedFile> $images */
    public function add(Property $property, array $images): int
    {
        foreach ($images as $image) {
            $property->propertyImages()->create(['image' => $this->storeImage($image, self::IMAGE_TYPE)]);
        }

        return count($images);
    }

    /** @param list<int> $imageIds */
    public function remove(Property $property, array $imageIds): int
    {
        return $property->propertyImages()->whereIn('id', $imageIds)->get()
            ->each(function (PropertyImages $image): void {
                Storage::delete($this->getRelativePath($image->image));
                $image->delete();
            })
            ->count();
    }
}
