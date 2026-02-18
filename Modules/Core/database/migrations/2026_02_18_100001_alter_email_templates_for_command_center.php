<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->longText('content_html')->nullable()->after('subject');
            $table->text('variables_hint')->nullable()->after('content_html');
            $table->boolean('is_html')->default(true)->after('variables_hint');
        });

        $this->backfillContentHtml();

        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('body_html');
        });
    }

    private function backfillContentHtml(): void
    {
        $rows = \DB::table('email_templates')->get();
        foreach ($rows as $row) {
            \DB::table('email_templates')->where('id', $row->id)->update([
                'content_html' => $row->body_html ?? '',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->longText('body_html')->nullable()->after('subject');
        });
        $rows = \DB::table('email_templates')->get();
        foreach ($rows as $row) {
            \DB::table('email_templates')->where('id', $row->id)->update([
                'body_html' => $row->content_html ?? '',
            ]);
        }
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['content_html', 'variables_hint', 'is_html']);
        });
    }
};
