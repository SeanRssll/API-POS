<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
    Schema::create('users', function (Blueprint $table) {
        $table->id();

        // Role relationship
        $table->foreignId('role_id')
            ->constrained('roles')
            ->onDelete('restrict');

        
        // EmployeeId
        $table->string('employee_id', 14)->nullable()->unique();

        // Personal information
        $table->string('f_name');
        $table->string('m_name')->nullable();
        $table->string('l_name');
        $table->string('gender');
        // Address
        $table->string('house_number')->nullable();
        $table->string('street')->nullable();
        $table->string('barangay')->nullable();
        $table->string('city')->nullable();
        $table->string('province')->nullable();
        $table->string('postal_code', 20)->nullable();

        // Contact and profile
        $table->date('birthday')->nullable();
        $table->string('contact_num', 20)->nullable();
        $table->string('profile_photo')->nullable();

        // Authentication
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->date('date_hire')->nullable();
        $table->boolean('is_active')->default(true);

        $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
