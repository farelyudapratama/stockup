<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StockChart extends Component
{
    public $chartData;
    public $dates;

    public function __construct($chartData, $dates)
    {
        $this->chartData = $chartData;
        $this->dates = $dates;
    }

    public function render(): View|Closure|string
    {
        return view('components.stock-chart');
    }
}
