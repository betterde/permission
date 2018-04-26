<?php

namespace Betterde\Permission\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use Betterde\Role\Contracts\RoleContract;
use Betterde\Permission\Contracts\PermissionContract;
use Betterde\Permission\Exceptions\PermissionException;

class Permission extends Model implements PermissionContract
{
    /**
     * 定义主键字段
     *
     * @var string
     * Date: 19/04/2018
     * @author George
     */
    protected $primaryKey = 'code';

    /**
     * 禁用主键自增
     *
     * @var bool
     * Date: 19/04/2018
     * @author George
     */
    public $incrementing = false;

    /**
     * 定义可填充字段
     *
     * @var array
     * Date: 18/04/2018
     * @author George
     */
    protected $fillable = ['code', 'parent_code', 'name', 'guard', 'permissiontable_id', 'permissiontable_type'];

    /**
     * 获取所有权限
     *
     * Date: 19/04/2018
     * @author George
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function fetchAll()
    {
        if (config('permission.cache.enable')) {
            $roles = collect(Redis::connection(config('permission.cache.database'))->hvals(config('permission.cache.prefix') . ':permissions'))->map(function ($role) {
                return json_decode($role);
            });
            if ($roles->isNotEmpty()) {
                return $roles;
            }
            $roles = static::all();
            foreach ($roles as $role) {
                Redis::connection(config('permission.cache.database'))->hset(config('permission.cache.prefix') . ':permissions', $role->code, $role);
            }
            return $roles;
        }

        return static::all();
    }

    /**
     * 根据编码查询权限
     *
     * Date: 19/04/2018
     * @author George
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Collection|Model|mixed
     */
    public static function findByCode(string $code)
    {
        if (config('permission.cache.enable')) {
            $result = Redis::connection(config('permission.cache.database'))->hget(config('permission.cache.prefix') . ':permissions', $code);
            $role = json_decode($result);
            if (! $role) {
                $role = static::findOrFail($code);
            }
        } else {
            $role = static::findOrFail($code);
        }

        return $role;
    }

    /**
     * 创建权限
     *
     * Date: 19/04/2018
     * @author George
     * @param array $attributes
     * @return $this|Model
     * @throws PermissionException
     */
    public static function store(array $attributes)
    {
        try {
            $attributes['guard'] = $attributes['guard'] ?? config('auth.defaults.guard');
            $role = static::create($attributes);
            Redis::connection(config('permission.cache.database'))->hset(config('permission.cache.prefix') . ':permissions', $role->code, $role);
            return $role;
        } catch (Exception $exception) {
            throw new PermissionException($exception->getMessage(), 500);
        }
    }

    /**
     * 修改权限属性
     *
     * Date: 19/04/2018
     * @author George
     * @param string $code
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Collection|Model
     * @throws PermissionException
     */
    public static function modify(string $code, array $attributes)
    {
        try {
            $role = static::findOrFail($code);
            $role->update($attributes);
            if (! $new_code = array_get($attributes, 'code') === $code) {
                DB::table(config('authorization.relation.role_permission'))->where('permission_code', $code)->update(['role_code' => $new_code]);
                $value = Redis::connection(config('role.cache.database'))->hget(config('role.cache.prefix') . ':role_permissions', [$code]);
                Redis::connection(config('role.cache.database'))->hdel(config('role.cache.prefix') . ':role_permissions', [$code]);
                Redis::connection(config('role.cache.database'))->hset(config('role.cache.prefix') . ':role_permissions', array_get($attributes, 'code', $code), $value);
            }
            Redis::connection(config('permission.cache.database'))->hdel(config('permission.cache.prefix') . ':permissions', [$code]);
            Redis::connection(config('permission.cache.database'))->hset(config('permission.cache.prefix') . ':permissions', array_get($attributes, 'code', $code), $role);
            return $role;
        } catch (Exception $exception) {
            throw new PermissionException('更新权限失败', 500);
        }
    }

    /**
     * 删除权限
     *
     * Date: 19/04/2018
     * @author George
     * @param string $code
     * @return bool
     * @throws PermissionException
     */
    public static function remove(string $code)
    {
        try {
            $role = self::findOrFail($code);
            $role->delete();
            Redis::connection(config('role.cache.database'))->hdel(config('role.cache.prefix') . ':roles', [$code]);
            return true;
        } catch (Exception $exception) {
            throw new PermissionException('删除权限失败', 500);
        }
    }

    /**
     * 定义权限关联角色信息
     *
     * Date: 19/04/2018
     * @author George
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(RoleContract::class, config('authorization.relation.role_permission'), 'permission_code', 'role_code', 'code', 'code');
    }

    /**
     * 定义多态模型关联
     *
     * Date: 20/04/2018
     * @author George
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|mixed
     */
    public function permissiontable()
    {
        return $this->morphTo();
    }
}