public function up(): void
{
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