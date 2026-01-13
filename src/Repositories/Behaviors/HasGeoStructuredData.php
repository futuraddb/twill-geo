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
            $structuredData = json_decode($structuredData, true);
            $locale = app()->getLocale();

            if (is_array($structuredData) && isset($structuredData[$locale])) {
                return $structuredData[$locale];
            }

            return null;
        }

        return $structuredData;
    }

    public static function getTwillModelFieldNameForGeoStructuredData(): string
    {
        return 'twill_field_geo_structured_data';
    }


}
