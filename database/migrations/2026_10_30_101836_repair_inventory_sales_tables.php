<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create products table if missing
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();

                $table->foreignId('category_id')
                    ->constrained('categories')
                    ->restrictOnDelete();

                $table->string('product_code')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('stock_quantity')->default(0);
                $table->integer('low_stock_threshold')->default(5);

                $table->timestamps();
            });
        }

        // Create sales table if missing
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')
                    ->constrained('users')
                    ->restrictOnDelete();

                $table->string('sale_number')->unique();
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->timestamp('sold_at')->useCurrent();

                $table->timestamps();
            });
        }

        // Create sale_items table if missing
        if (!Schema::hasTable('sale_items')) {
            Schema::create('sale_items', function (Blueprint $table) {
                $table->id();

                $table->foreignId('sale_id')
                    ->constrained('sales')
                    ->cascadeOnDelete();

                $table->foreignId('product_id')
                    ->constrained('products')
                    ->restrictOnDelete();

                $table->integer('quantity');
                $table->decimal('unit_price', 10, 2);
                $table->decimal('subtotal', 10, 2);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally leave existing data and tables untouched.
    }
};