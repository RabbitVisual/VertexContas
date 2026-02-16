<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Makes inspections support Ticket OR Chat flow.
     */
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->foreignId('ticket_id')->nullable()->change();
            $table->foreignId('conversation_id')->nullable()->after('ticket_id')
                ->constrained('vertex_chat_conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->foreignId('ticket_id')->nullable(false)->change();
        });
    }
};
