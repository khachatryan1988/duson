<?php

namespace App\View\Components\Sections;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Outl1ne\NovaMediaHub\Models\Media;

class HeroBanner extends Component
{
    public $section;

    /**
     * Create a new component instance.
     */
    public function __construct($section)
    {
        $this->section = $section;
    }

    /**
     * Get the view / contents that represent the component.
     */
//    public function render(): View|Closure|string
//    {
//        return view('components.sections.hero-banner')->with([
//            'image' => getImage($this->section['image']),
//            'title' => !empty($this->section['title']) ? tr($this->section['title']) : '',
//            'cta_url' => !empty($this->section['cta_url']) ? tr($this->section['cta_url']) : '',
//            'cta_text' => !empty($this->section['cta_text']) ? tr($this->section['cta_text']) : '',
//        ]);
//    }
    public function render(): View|Closure|string
    {
        return view('components.sections.hero-banner')->with([
            'slides' => $this->section['slides'] ?? [],
        ]);
    }
}
