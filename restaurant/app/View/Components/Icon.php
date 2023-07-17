<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icon extends Component
{

    private const PATH_TO_ICON = "images/icons/";


    public string $path;
    public string $imgClasses = "w-7 h-7";
    public string $divClasses = "w-fit h-fit m-0 p-0 flex justify-center items-center";
    public string $alt = "icon ";

    /**
     * Create a new component instance.
     */
    public function __construct(
        $name,
        $imgClasses = "",
        $divClasses = "",
    )
    {
        $this->path = self::PATH_TO_ICON . $name . ".svg";
        $this->alt .= $name;

        if ($imgClasses) {
            $this->imgClasses = $imgClasses;
        }

        if ($divClasses) {
            $this->divClasses = $divClasses;
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.icon');
    }
}
