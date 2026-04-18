<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MenuService;

class HomeMenu extends Component
{
    protected $menuService;
    public $categories;

    public function mount(){
        $this->menuService = new MenuService();
        $this->categories = $this->menuService->getActiveMenu();
    }

    public function render()
    {
        return view('livewire.home-menu', [
            'categories' => $this->categories,
        ]);
    }
}
