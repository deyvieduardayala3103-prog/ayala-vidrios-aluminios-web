<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
        });
        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('role_id')->nullable()->constrained()->restrictOnDelete();
            $t->boolean('active')->default(true);
        });
        Schema::create('specialties', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            $t->string('image')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $t->string('name');
            $t->text('description')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('product_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_category_id')->constrained()->restrictOnDelete();
            $t->string('name');
            $t->text('description');
            $t->string('image')->nullable();
            $t->decimal('price', 12, 2)->nullable();
            $t->string('unit', 30);
            $t->boolean('available')->default(true);
            $t->timestamps();
        });
        Schema::create('projects', function (Blueprint $t) {
            $t->id();
            $t->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description');
            $t->date('completed_at')->nullable();
            $t->boolean('featured')->default(false);
            $t->boolean('published')->default(false);
            $t->timestamps();
        });
        Schema::create('project_images', function (Blueprint $t) {
            $t->id();
            $t->foreignId('project_id')->constrained()->cascadeOnDelete();
            $t->string('path');
            $t->string('alt');
            $t->timestamps();
        });
        Schema::create('customers', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone', 30)->index();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->timestamps();
        });
        Schema::create('quote_statuses', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
        });
        Schema::create('quotes', function (Blueprint $t) {
            $t->id();
            $t->uuid('reference')->unique();
            $t->foreignId('customer_id')->constrained()->restrictOnDelete();
            $t->foreignId('specialty_id')->constrained()->restrictOnDelete();
            $t->foreignId('quote_status_id')->constrained()->restrictOnDelete();
            $t->text('description');
            $t->string('zone')->nullable();
            $t->decimal('amount', 12, 2)->nullable();
            $t->text('observations')->nullable();
            $t->timestamp('consented_at');
            $t->timestamps();
            $t->index(['specialty_id', 'created_at']);
        });
        Schema::create('quote_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $t->string('work_type');
            $t->decimal('width', 10, 3)->nullable();
            $t->decimal('height', 10, 3)->nullable();
            $t->string('unit', 10)->nullable();
            $t->unsignedInteger('quantity');
            $t->timestamps();
        });
        Schema::create('quote_attachments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $t->string('path');
            $t->string('original_name');
            $t->timestamps();
        });
        Schema::create('quote_status_histories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $t->foreignId('quote_status_id')->constrained()->restrictOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->text('note')->nullable();
            $t->decimal('amount', 12, 2)->nullable();
            $t->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone', 30);
            $t->string('email')->nullable();
            $t->text('message');
            $t->timestamp('consented_at');
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
        });
        Schema::create('sales', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->restrictOnDelete();
            $t->foreignId('quote_id')->nullable()->constrained()->restrictOnDelete();
            $t->decimal('amount', 12, 2);
            $t->date('sold_at');
            $t->text('notes')->nullable();
            $t->timestamps();
        });
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'sales', 'contact_messages', 'quote_status_histories', 'quote_attachments', 'quote_items', 'quotes', 'quote_statuses', 'customers', 'project_images', 'projects', 'products', 'product_categories', 'services', 'specialties'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropConstrainedForeignId('role_id');
            $t->dropColumn('active');
        });
        Schema::dropIfExists('roles');
    }
};
