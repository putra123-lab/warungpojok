
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number')->unique();

            $table->foreignId('dining_table_id')
                ->constrained('dining_tables')
                ->restrictOnDelete();

            $table->string('customer_name');
            $table->text('notes')->nullable();

            $table->string('status')->default('menunggu');

            $table->foreignId('sale_id')
                ->nullable()
                ->unique()
                ->constrained('sales')
                ->nullOnDelete();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
