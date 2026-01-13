<?php

namespace futuraddb\TwillGeo\Helpers;

use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fieldset;

class GeoFormFieldset {

    public static function getFieldset(
        bool $translated = false,
        string $fieldLabel = 'GEO'
    ): Fieldset
    {
        return Fieldset::make()->title($fieldLabel)->id('geo_fieldset')
            ->closed()
            ->fields([
                BladePartial::make()
                    ->view('twill-geo::admin.partials.form.schema_structured_data_wrapper')
                    ->withAdditionalParams([
                        'fieldLabel' => $fieldLabel,
                        'translated' => $translated,
                ]),
            ]);
    }

}
