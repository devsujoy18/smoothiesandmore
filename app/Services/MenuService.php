<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Create a new class instance.
     */
    public function getActiveMenu()
    {
        /*return Cache::remember('home_menu', 3600, function () {
            return Category::with(['products' => function ($query) {
                    $query->where('is_available', true);
                }])
                ->orderBy('sort_no')
                ->get();
        });
        */
        
        return Category::with(['products' => function ($query) {
                    $query->where('is_available', true)
                            ->orderBy('sort_no');
            }])
            ->orderBy('sort_no')
            ->get();
    }
}
