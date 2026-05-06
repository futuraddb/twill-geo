<?php


namespace futuraddb\TwillGeo\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Snippet extends Component {
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $item = null,
        public ?string $geoStructuredData = null,
    )
    {
        if ($item &&
            $item->isTranslatable() &&
            method_exists($item, 'relationLoaded') &&
            !$item->relationLoaded('translations')
        ) {
            $item->load('translations');
        }

        if ($item &&
            method_exists($item, 'getGeoStructuredData')
        ) {
            $this->geoStructuredData = $item->getGeoStructuredData();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('twill-geo::components.snippet');
    }
}
