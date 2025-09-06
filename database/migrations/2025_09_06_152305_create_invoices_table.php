<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->decimal('total', 12, 2)->default(0);
            $table->json('items')->nullable(); // simple array of items
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('invoices'); }
};
