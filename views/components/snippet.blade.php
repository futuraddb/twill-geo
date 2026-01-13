@if($item)
    <script type="application/ld+json">
        {!! $item->getGeoStructuredData() !!}
    </script>
@endif
