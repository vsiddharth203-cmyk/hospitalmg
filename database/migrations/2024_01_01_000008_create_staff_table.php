<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('staff_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('role'); // Nurse, Lab Technician, Receptionist, Admin, etc.
            $table->string('department');
            $table->string('phone');
            $table->string('email')->unique();
            $table->date('join_date');
            $table->decimal('salary', 10, 2)->default(0);
            $table->enum('shift', ['morning', 'afternoon', 'night', 'rotating'])->default('morning');
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('head_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
        Schema::dropIfExists('departments');
    }
};
