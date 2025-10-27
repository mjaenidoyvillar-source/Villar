<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories (using firstOrCreate to prevent duplicates)
        $electronics = Category::firstOrCreate(
            ['name' => 'Electronics'],
            ['description' => 'Electronic devices and gadgets including smartphones, laptops, tablets, and accessories.']
        );

        $furniture = Category::firstOrCreate(
            ['name' => 'Furniture'],
            ['description' => 'Home and office furniture including chairs, desks, tables, and storage solutions.']
        );

        $groceries = Category::firstOrCreate(
            ['name' => 'Groceries'],
            ['description' => 'Food items, beverages, and household essentials for daily consumption.']
        );

        // Create products (prices in Philippine Peso, using firstOrCreate to prevent duplicates)
        Product::firstOrCreate(
            ['name' => 'iPhone 15 Pro'],
            [
                'quantity' => 25,
                'price' => 79999.00,
                'category_id' => $electronics->id
            ]
        );

        Product::firstOrCreate(
            ['name' => 'MacBook Air M2'],
            [
                'quantity' => 12,
                'price' => 95999.00,
                'category_id' => $electronics->id
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Samsung Galaxy S24'],
            [
                'quantity' => 8,
                'price' => 63999.00,
                'category_id' => $electronics->id
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Office Chair'],
            [
                'quantity' => 15,
                'price' => 23999.00,
                'category_id' => $furniture->id
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Standing Desk'],
            [
                'quantity' => 5,
                'price' => 47999.00,
                'category_id' => $furniture->id
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Coffee Table'],
            [
                'quantity' => 0,
                'price' => 15999.00,
                'category_id' => $furniture->id
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Organic Coffee Beans'],
            [
                'quantity' => 50,
                'price' => 1999.00,
                'category_id' => $groceries->id
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Whole Grain Bread'],
            [
                'quantity' => 3,
                'price' => 399.00,
                'category_id' => $groceries->id
            ]
        );

        // Get products for transactions
        $iphone = Product::where('name', 'iPhone 15 Pro')->first();
        $macbook = Product::where('name', 'MacBook Air M2')->first();
        $chair = Product::where('name', 'Office Chair')->first();
        $coffee = Product::where('name', 'Organic Coffee Beans')->first();

        // Create sample transactions
        Transaction::create([
            'buyer_name' => 'John Smith',
            'buyer_email' => 'john.smith@email.com',
            'buyer_phone' => '+1-555-0123',
            'product_id' => $iphone->id,
            'quantity_purchased' => 2,
            'unit_price' => $iphone->price,
            'total_amount' => $iphone->price * 2,
            'status' => 'completed',
            'notes' => 'Customer requested express shipping'
        ]);

        Transaction::create([
            'buyer_name' => 'Sarah Johnson',
            'buyer_email' => 'sarah.j@email.com',
            'buyer_phone' => '+1-555-0456',
            'product_id' => $macbook->id,
            'quantity_purchased' => 1,
            'unit_price' => $macbook->price,
            'total_amount' => $macbook->price,
            'status' => 'pending',
            'notes' => 'Waiting for payment confirmation'
        ]);

        Transaction::create([
            'buyer_name' => 'Mike Wilson',
            'buyer_email' => 'mike.wilson@email.com',
            'product_id' => $chair->id,
            'quantity_purchased' => 3,
            'unit_price' => $chair->price,
            'total_amount' => $chair->price * 3,
            'status' => 'completed',
            'notes' => 'Bulk order for office setup'
        ]);

        Transaction::create([
            'buyer_name' => 'Emily Davis',
            'buyer_email' => 'emily.davis@email.com',
            'buyer_phone' => '+1-555-0789',
            'product_id' => $coffee->id,
            'quantity_purchased' => 5,
            'unit_price' => $coffee->price,
            'total_amount' => $coffee->price * 5,
            'status' => 'cancelled',
            'notes' => 'Customer cancelled due to shipping delay'
        ]);

        Transaction::create([
            'buyer_name' => 'David Brown',
            'buyer_email' => 'david.brown@email.com',
            'product_id' => $iphone->id,
            'quantity_purchased' => 1,
            'unit_price' => $iphone->price,
            'total_amount' => $iphone->price,
            'status' => 'pending',
            'notes' => 'New customer, first purchase'
        ]);
    }
}
