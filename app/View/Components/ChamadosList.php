<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChamadosList extends Component
{
    /**
     * Create a new component instance.
     */

     public $chamados;  
    public function __construct($chamados)
    {
        $this->chamados = $chamados;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chamados-list');
    }
}
