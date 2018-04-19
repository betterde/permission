<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->string('code')->primary()->comment('权限编码');
            $table->string('parent_code')->index()->nullable()->comment('依赖权限');
            $table->string('name')->comment('权限名称');
            $table->string('guard')->comment('守卫');
            $table->unsignedInteger('permissiontable_id')->nullable()->comment('权限关联的资源ID');
            $table->string('permissiontable_type')->nullable()->comment('权限关联的资源类型');
            $table->index(['permissiontable_id', 'permissiontable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
