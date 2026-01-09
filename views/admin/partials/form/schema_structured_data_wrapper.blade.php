@php
    $fieldName = $fieldName ?? 'geo';
    $fieldLabel = $fieldLabel ?? 'Structured data';
    $translated = $translated ?? false;
@endphp
@formField('input', [
    'name' => $fieldName,
    'label' => $fieldLabel,
    'type' => 'textarea',
    'rows' => 10,
    'translated' => $translated,
])
@formField('twill-geo::schema_structured_data', [
    'targetFieldName' => $fieldName,
    'translated' => $translated,
])
