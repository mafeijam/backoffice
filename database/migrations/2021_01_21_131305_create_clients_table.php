<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('status');
            $table->json('data');
            $table->json('meta_data');
            $table->softDeletes();
            $table->timestamps();

            $table->index('uuid');
        });

        Schema::create('pending_clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('status');
            $table->json('data');
            $table->json('meta_data');
            $table->softDeletes();
            $table->timestamps();

            $table->index('uuid');
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('client_uuid')->constrained('clients', 'uuid');
            $table->string('number');
            $table->string('type');
            $table->string('status');
            $table->json('data');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['number', 'type']);
        });

        Schema::create('client_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('client_uuid')->constrained('clients', 'uuid');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->foreignId('approved_by')->constrained('users', 'id');
            $table->json('data');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('client_logs');
    }
}
