public function up(): void
{
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