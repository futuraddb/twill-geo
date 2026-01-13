<?php

namespace futura\TwillGeo\Repositories\Behaviors;

use futura\TwillGeo\Models\GeoStructuredData;

trait HandleGeoStructuredData
{
    /**
     * Hook into Twill's afterSave repository method.
     */
    public function afterSaveHandleGeoStructuredData($object, $fields): void
    {
        $fieldName = $object::getTwillModelFieldNameForGeoStructuredData();

        if (isset($fields[$fieldName])) {
            GeoStructuredData::updateOrCreate(
                [
                    'model_id' => $object->id,
                    'model_type' => get_class($object),
                ],
                [
                    'structured_data' => is_array($fields[$fieldName])
                        ? json_encode($fields[$fieldName])
                        : $fields[$fieldName],
                ]
            );
        }
    }

    /**
     * Hook into Twill's getFormFields repository method
     * to populate the field when editing.
     */
    public function getFormFieldsHandleGeoStructuredData($object, $fields): array
    {
        $fieldName = $object::getTwillModelFieldNameForGeoStructuredData();

        $geoData = GeoStructuredData::where('model_id', $object->id)
            ->where('model_type', get_class($object))
            ->first();

        if ($geoData) {
            if ($object->isTranslatable()) {
                $fields['translations'][$fieldName] = json_decode($geoData->structured_data, true);
            } else {
                $fields[$fieldName] = $geoData->structured_data;
            }
        }

        return $fields;
    }

    /**
     * Clean up data when the main model is deleted
     */
    public function afterDeleteHandleGeoStructuredData($object): void
    {
        GeoStructuredData::where('model_id', $object->id)
            ->where('model_type', get_class($object))
            ->delete();
    }
}
