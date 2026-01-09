@if($translated)
    <a17-locale
        type="a17-schema-structured-data"
        :attributes="{
            targetFieldName: '{{$targetFieldName}}',
            apiKey: '{{config('twill-geo.openai_api_key')}}',
            llmModel: '{{config('twill-geo.llmModel')}}',
            inStore: 'value'
        }"
    ></a17-locale>
@else
    <a17-schema-structured-data
        target-field-name="{{$targetFieldName}}"
        api-key="{{config('twill-geo.openai_api_key')}}"
        llm-model="{{config('twill-geo.llmModel')}}"
        in-store="value"
    ></a17-schema-structured-data>
@endif
