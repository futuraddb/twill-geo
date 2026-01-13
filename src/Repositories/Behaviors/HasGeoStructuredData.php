<?php

namespace futuraddb\TwillGeo\Repositories\Behaviors;

use futuraddb\TwillGeo\Models\GeoStructuredData;

trait HasGeoStructuredData {

    public function getGeoStructuredData(): ?string
    {
        $structuredData = GeoStructuredData::query()
            ->where('model_id', $this->id)
            ->where('model_type', get_class($this))
            ->first()
            ?->structured_data;

        if ($this->isTranslatable()) {
            return json_decode($structuredData, true)[app()->getLocale()];
        }

        return $structuredData;
    }

    public static function getTwillModelFieldNameForGeoStructuredData(): string
    {
        return 'twill_field_geo_structured_data';
    }


}
