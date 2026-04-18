<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\AddOn;
use Illuminate\Support\Facades\DB;

class AddOnProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $category = Category::firstOrCreate([
                'name' => 'Burgers',
            ], [
                'slug' => 'burgers',
            ]);

            $product = Product::firstOrCreate([
                'name' => 'Classic Cheese Burger',
            ], [
                'category_id' => $category->id,
                'slug' => 'classic-cheese-burger',
                'description' => 'A timeless burger with cheddar cheese.',
                'price' => 199.00,
                'mrp' => 249.00,
                'image' => null,
                'is_available' => true,
            ]);

            $addonCheese = AddOn::firstOrCreate([
                'name' => 'Extra Cheese',
            ], [
                'category' => 'Toppings',
                'price' => 20.00,
                'image' => null,
                'is_active' => true,
            ]);

            $addonOnion = AddOn::firstOrCreate([
                'name' => 'Fried Onions',
            ], [
                'category' => 'Toppings',
                'price' => 15.00,
                'image' => null,
                'is_active' => true,
            ]);

            $addonSauce = AddOn::firstOrCreate([
                'name' => 'Garlic Sauce',
            ], [
                'category' => 'Sauces',
                'price' => 10.00,
                'image' => null,
                'is_active' => true,
            ]);

            $product->addons()->syncWithoutDetaching([
                $addonCheese->id => ['is_required' => false, 'min_selection' => 0, 'max_selection' => 2],
                $addonOnion->id => ['is_required' => false, 'min_selection' => 0, 'max_selection' => 1],
                $addonSauce->id => ['is_required' => false, 'min_selection' => 0, 'max_selection' => 1],
            ]);
        });
    }
}
