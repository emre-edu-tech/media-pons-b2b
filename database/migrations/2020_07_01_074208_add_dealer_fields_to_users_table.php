<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDealerFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->after('name')->nullable();
            $table->string('business_registration_form')->after('photo')->nullable();
            $table->string('id_card')->after('photo')->nullable();
            $table->string('tax_number')->after('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('business_registration_form');
            $table->dropColumn('id_card');
            $table->dropColumn('tax_number');
        });
    }
}
