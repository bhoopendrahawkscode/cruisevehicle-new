<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MediaManager extends Component
{
    public $result;
    public  $sortBy;
    public $queryString;
    public $listRoute,$order;
    public function __construct($result, $sortBy,$queryString,$listRoute,$order)
    {
        $this->result = $result;
        $this->sortBy = $sortBy;
        $this->queryString = $queryString;
        $this->listRoute = $listRoute;
        $this->order = $order;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.media-manager');
    }
}
