<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->id('id');
            $table->string('github_id')->unique();
            $table->string('name');
            $table->string('full_name');
            $table->boolean('private')->default(false);
            $table->string('html_url');
            $table->text('description')->nullable();
            $table->boolean('fork')->default(false);
            $table->string('language')->nullable();
            $table->integer('forks_count')->default(0);
            $table->integer('stargazers_count')->default(0);
            $table->integer('watchers_count')->default(0);
            $table->boolean('archived')->default(false);
            $table->boolean('disabled')->default(false);
            $table->integer('open_issues_count')->default(0);
            $table->string('visibility')->default('public');
            $table->json('topics')->nullable();
            $table->dateTimeTz('created_repository_at');
            $table->dateTimeTz('updated_repository_at');
            $table->dateTime('approved_at')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
