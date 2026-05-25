<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id')->unique(); // e.g. DOC-00001
            $table->string('first_name');
            $table->string('last_name');
            $table->string('specialization');
            $table->string('qualification');
            $table->string('experience_years');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('license_number')->unique();
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->json('available_days')->nullable(); // ["Monday","Tuesday",...]
            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
