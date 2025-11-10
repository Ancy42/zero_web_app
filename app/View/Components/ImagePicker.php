<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ImagePicker extends Component
{
    public string $name;
    public bool $multiple;
    public string $imagePath;
    public string|null $value;

    /**
     * Create a new component instance.
     */

    public function __construct(string $name, bool $multiple = false, string|null $value = null)
    {
        $this->name = $name;
        $this->multiple = $multiple;
        $this->value = $value;

        $imagePath = asset('default/default.jpg');
        if($value && Storage::exists($value)){
            $imagePath = Storage::url($value);
        }elseif($value && asset($value)){
            $imagePath =asset($value);
        }
        $this->imagePath = $imagePath;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.image-picker');
    }
}
