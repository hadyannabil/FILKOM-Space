<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique();           // e.g. #REQ-2024-1847
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();

            $table->string('event_name');
            $table->string('event_type')->default('Academic Event'); // Academic | Organization | Other
            $table->string('pic_name');
            $table->string('pic_email')->nullable();
            $table->unsignedInteger('attendees');
            $table->text('notes')->nullable();

            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('approval_letter')->nullable();    // path to uploaded PDF

            // Status: pending | approved | rejected | cancelled | completed
            $table->string('status')->default('pending');
            $table->text('rejection_reason')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
