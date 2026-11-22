<?php

namespace Modules\Property\Services;

use Modules\Property\Exceptions\PropertyNotApproved;
use Modules\Property\Models\Property;

final class PropertyPublicationService
{
    public function publish(Property $property): void
    {
        $this->ensurePublishable($property);

        $property->update(['is_published' => true]);
    }

    public function unpublish(Property $property): void
    {
        $property->update(['is_published' => false]);
    }

    public function ensurePublishable(Property $property): void
    {
        if (!$property->isApproved()) {
            throw PropertyNotApproved::cannotPublish();
        }
    }
}
