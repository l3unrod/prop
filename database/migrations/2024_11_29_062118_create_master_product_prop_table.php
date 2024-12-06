<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
   Schema::create('master_product_prop', function (Blueprint $table) {
       $table->id();
       $table->string('CustID', 100);
       $table->string('StatusID', 100);
       $table->string('StatusDes', 100); 
       $table->string('Remark', 100);
       $table->string('ProductID', 100);
       $table->string('ProductName', 100);
       $table->string('Target', 100);
       $table->string('Add', 100);
       $table->string('CurrentMonth', 100);
       $table->string('Recommend', 100);
       $table->dateTime('Create_Date');
       $table->timestamps();
   });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_product_prop');
    }
};
