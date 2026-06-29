<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('username', 100)->nullable()->unique();
            $table->string('nomor_hp', 15)->nullable()->unique();
            $table->char('nik', 16)->nullable()->unique();
            $table->text('alamat')->nullable();
            $table->string('nama_lokasi')->nullable();
            $table->string('foto_profil', 500)->nullable();
            $table->enum('peran', ['admin', 'petani', 'pembeli'])->default('petani');
            $table->enum('status', ['aktif', 'menunggu', 'nonaktif'])->default('menunggu');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('password');
            $table->dateTime('password_updated_at')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
            $table->index('name');
            $table->index(['peran', 'status']);
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
