<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Sample products
        $products = [
            [
                'name' => 'Classic Blue Jeans',
                'code' => '0013',
                'price' => 35.00,
                'stock' => 50,
                'category' => 'Jeans',
                'image' => 'https://via.placeholder.com/150'
            ],
            [
                'name' => 'Red Maxi Dress',
                'code' => '101',
                'price' => 50.00,
                'stock' => 30,
                'category' => 'Dresses',
                'image' => 'https://via.placeholder.com/150'
            ],
            [
                'name' => 'White Polo Shirt',
                'code' => '201',
                'price' => 20.00,
                'stock' => 100,
                'category' => 'Shirts',
                'image' => 'https://via.placeholder.com/150'
            ],
            [
                'name' => 'Yellow Sport Shirt',
                'code' => '202',
                'price' => 15.00,
                'stock' => 75,
                'category' => 'Shirts',
                'image' => 'https://via.placeholder.com/150'
            ],
            [
                'name' => 'Beige Pants',
                'code' => '301',
                'price' => 45.00,
                'stock' => 40,
                'category' => 'Jeans',
                'image' => 'https://via.placeholder.com/150'
            ],
            [
                'name' => 'White Jeans',
                'code' => '302',
                'price' => 25.00,
                'stock' => 60,
                'category' => 'Jeans',
                'image' => 'https://via.placeholder.com/150'
            ],
            [
                'name' => 'Black Jeans',
                'code' => '303',
                'price' => 30.00,
                'stock' => 45,
                'category' => 'Jeans',
                'image' => 'https://via.placeholder.com/150'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
