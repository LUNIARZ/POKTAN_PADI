<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_email_unique');
                $table->dropColumn('email');
            });
        }

        if (Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }

        Schema::dropIfExists('password_reset_tokens');

        if (Schema::hasColumn('users', 'nomor_hp') && DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nomor_hp', 15)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('email', 150)->nullable()->unique()->after('username');
            });
        }

        if (! Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dateTime('email_verified_at')->nullable()->after('password_updated_at');
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email', 150)->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (Schema::hasColumn('users', 'nomor_hp') && DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nomor_hp', 25)->nullable()->change();
            });
        }
    }
};
