<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->string('recipient_email')->nullable()->after('user_id');
            $table->text('smtp_response')->nullable()->after('status');
            $table->text('error_details')->nullable()->after('smtp_response');
            $table->longText('body_snapshot')->nullable()->after('error_details');
        });

        $this->backfillRecipientAndError();

        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn(['recipient', 'error_message']);
        });
    }

    private function backfillRecipientAndError(): void
    {
        $rows = \DB::table('email_logs')->get();
        foreach ($rows as $row) {
            $updates = ['recipient_email' => $row->recipient ?? ''];
            if (isset($row->error_message)) {
                $updates['error_details'] = $row->error_message;
            }
            \DB::table('email_logs')->where('id', $row->id)->update($updates);
        }
    }

    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->string('recipient')->nullable()->after('user_id');
            $table->text('error_message')->nullable()->after('status');
        });
        $rows = \DB::table('email_logs')->get();
        foreach ($rows as $row) {
            \DB::table('email_logs')->where('id', $row->id)->update([
                'recipient' => $row->recipient_email ?? '',
                'error_message' => $row->error_details ?? null,
            ]);
        }
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn(['recipient_email', 'smtp_response', 'error_details', 'body_snapshot']);
        });
    }
};
