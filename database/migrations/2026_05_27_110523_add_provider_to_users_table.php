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
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider_id')->nullable()->after('password'); //If you're wondering what this after method is
            //Its just to put the columns in this order, just a neat way of doing it kind of useless though
            $table->string('provider_name')->nullable()->after('provider_id');
            $table->text('provider_token')->nullable()->after('provider_name');
            $table->text('provider_refresh_token')->nullable()->after('provider_token');

            $table->string('password')->nullable()->change(); //This already exists in the user table so im just modifying the rules
            //Password can be nullable but register method validates if not using OAuth
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //Not needed since im definetly using this but just incase a quick way of removing these
            $table->dropColumn([
                'provider_id',
                'provider_name',
                'provider_token',
                'provider_refresh_token'
            ]);
            $table->string('password')->nullable(false)->change(); 
        });
    }
};
