<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PrimaryButton extends Component
{
    public $color,$name;
    public $customColors = ['white', 'black', 'transparent', 'mainwhitebg', 'darthmouthgreen', 'seagreen', 'lemonchiffon', 'ashgray'];
    /**
     * Create a new component instance.
     */
    public function __construct($color,$name)
    {
        $this->color = $color;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.primary-button');
    }
}
