<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Vesp\Services\Migration;

class Users extends Migration
{
    // Метод up проводит миграцию и создаёт 2 таблицы
    public function up(): void
    {
        $this->schema->create(
            'user_roles',
            static function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->unique();
                $table->json('scope');
                $table->timestamps();
            }
        );

        $this->schema->create(
            'users',
            static function (Blueprint $table) {
                $table->increments('id');
                $table->string('username')->unique();
                $table->string('password');
                $table->string('fullname')->nullable();
                $table->string('email')->nullable();
                $table->char('phone', 12)->nullable();
                $table->string('telegram')->nullable();
                $table->integer('role_id')->unsigned()->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();

                $table->foreign('role_id')
                    ->references('id')->on('user_roles')
                    ->onUpdate('restrict')
                    ->onDelete('set null');
            }
        );

        $this->schema->create(
            'user_tokens',
            function (Blueprint $table) {
                $table->string('token')->primary();
                $table->integer('user_id')->unsigned();
                $table->boolean('active')->default(true);
                $table->string('ip', 16)->nullable();
                $table->timestamp('valid_till')->nullable()->index();
                $table->timestamps();

                $table->index(['token', 'user_id', 'active']);

                $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onUpdate('restrict')
                    ->onDelete('cascade');
            }
        );
    }

    public function down(): void
    {
        $this->schema->drop('user_tokens');
        $this->schema->drop('users');
        $this->schema->drop('user_roles');
    }
}