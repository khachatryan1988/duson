<?php

namespace App\View\Components\Sections;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextBlock extends Component
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
    public function render(): View|Closure|string
    {
        return view('components.sections.text-block')->with([
            'title' => tr($this->section['title']),
            'content' => tr($this->section['body'])
        ]);
    }
}
