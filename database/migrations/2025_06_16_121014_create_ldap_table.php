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
        Schema::create('ldap', function (Blueprint $table) {
            $table->id('ldap_id');
            $table->string('ldap_server', 120);
            $table->string('ldap_user', 120);
            $table->string('ldap_pass', 120);
            $table->string('ldap_tree', 120);
            $table->string('ldap_filter', 200);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ldap');
    }
};