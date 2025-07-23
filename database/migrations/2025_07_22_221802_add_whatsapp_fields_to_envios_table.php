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
        Schema::table('envios', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('cliente_id');
            $table->string('twilio_message_sid')->nullable()->after('whatsapp_number');
            $table->string('twilio_conversation_sid')->nullable()->after('twilio_message_sid');
            $table->text('whatsapp_message')->nullable()->after('twilio_conversation_sid');
            $table->json('whatsapp_responses')->nullable()->after('whatsapp_message');
            $table->timestamp('whatsapp_sent_at')->nullable()->after('whatsapp_responses');
            $table->timestamp('whatsapp_responded_at')->nullable()->after('whatsapp_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_number',
                'twilio_message_sid',
                'twilio_conversation_sid',
                'whatsapp_message',
                'whatsapp_responses',
                'whatsapp_sent_at',
                'whatsapp_responded_at'
            ]);
        });
    }
};
