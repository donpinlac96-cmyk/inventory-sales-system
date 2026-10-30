public function up(): void
{
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