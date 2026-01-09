<?php

namespace futura\TwillGeo\Helpers;

use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fieldset;

class GeoFormFieldset {

    public static function getFieldset(
        bool $translated = false,
        string $fieldName = 'geo',
        string $fieldLabel = 'GEO'
    ): Fieldset
    {
        return Fieldset::make()->title($fieldLabel)->id($fieldName)
//            ->closed()
            ->fields([
                BladePartial::make()
                    ->view('twill-geo::admin.partials.form.schema_structured_data_wrapper')
                    ->withAdditionalParams([
                        'fieldName' => $fieldName,
                        'fieldLabel' => $fieldLabel,
                        'translated' => $translated,
                ]),
            ]);
    }

}
