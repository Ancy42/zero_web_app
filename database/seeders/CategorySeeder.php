<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->delete();

        $categories = [
            'Vegetables' => [
                'Leafy Greens',
                'Root Vegetables',
                'Gourd Vegetables',
                'Mushrooms',
                'Organic Vegetables',
                'Chili & Pepper',
                'Cabbage & Cauliflower',
                'Seasonal Vegetables',
            ],
            'Fruits' => [
                'Citrus Fruits',
                'Tropical Fruits',
                'Apples & Pears',
                'Berries',
                'Melons',
                'Bananas',
                'Dates & Dry Fruits',
                'Imported Fruits',
            ],
            'Meat' => [
                'Beef',
                'Mutton',
                'Lamb',
                'Chicken (Broiler)',
                'Chicken (Deshi)',
                'Duck',
                'Turkey',
                'Processed Meat',
            ],
            'Fish' => [
                'Rui/Katla',
                'Hilsha',
                'Pangas/Telapia',
                'Shrimp & Prawns',
                'Pomfret',
                'Sea Fish',
                'Dry Fish',
                'Frozen Fish',
            ],
            'Snacks' => [
                'Chips & Crisps',
                'Nuts & Seeds',
                'Biscuits & Cookies',
                'Namkeen & Chanachur',
                'Instant Noodles',
                'Popcorn & Fryums',
                'Energy Bars',
                'Traditional Snacks',
            ],
            'Dairy' => [
                'Milk',
                'Curd/Yogurt',
                'Butter',
                'Ghee',
                'Cheese',
                'Paneer',
                'Flavored Milk',
            ],
            'Bakery' => [
                'Bread',
                'Buns & Rolls',
                'Cakes',
                'Pastries',
                'Cookies',
                'Doughnuts',
                'Croissants',
            ],
            'Frozen Foods' => [
                'Frozen Paratha & Roti',
                'Frozen Snacks',
                'Frozen Meat',
                'Frozen Vegetables',
                'Ice Cream',
                'Ready-to-Cook',
            ],
            'Beverages' => [
                'Soft Drinks',
                'Fruit Juice',
                'Tea & Coffee',
                'Mineral Water',
                'Energy Drinks',
                'Milk Drinks',
            ],
            'Spices & Condiments' => [
                'Whole Spices',
                'Ground Spices',
                'Mixed Masalas',
                'Salt',
                'Sugar & Jaggery',
                'Pickles',
                'Sauces & Ketchup',
            ],
            'Grains & Pulses' => [
                'Rice',
                'Lentils (Dal)',
                'Chickpeas',
                'Wheat & Flour',
                'Semolina & Suji',
                'Oats',
                'Barley',
            ],
        ];

        foreach ($categories as $parent => $children) {
            $category = Category::factory()->create([
                'name' => $parent,
                'parent_id' => null,
                'status' => true,
            ]);

            foreach ($children ?: [] as $child) {
                Category::factory()->create([
                    'name' => $child,
                    'parent_id' => $category->id,
                    'status' => true,
                ]);
            }
        }
    }
}
