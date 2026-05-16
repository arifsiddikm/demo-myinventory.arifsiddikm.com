<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('item_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->string('supplier')->nullable();
            $table->decimal('price_per_unit', 15, 2)->nullable();
            $table->date('received_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('item_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->integer('quantity');
            $table->string('purpose')->nullable();
            $table->string('recipient')->nullable();
            $table->date('issued_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('item_id')->constrained()->onDelete('restrict');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('borrower_name')->nullable();
            $table->integer('quantity');
            $table->date('borrow_date');
            $table->date('expected_return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'overdue'])->default('pending');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('borrowings');
        Schema::dropIfExists('stock_outs');
        Schema::dropIfExists('stock_ins');
    }
};
