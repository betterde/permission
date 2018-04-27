<?php

namespace Betterde\Permission\Contracts;

interface PermissionContract
{
    public static function fetchAll();

    public static function findByCode(string $code);

    public static function store(array $attributes);

    public static function modify(string $code, array $attributes);

    public static function remove(string $code);

    /**
     * 定义关联关系
     *
     * Date: 19/04/2018
     * @author George
     * @return mixed
     */
    public function roles();


    /**
     * 定义权限多态关系
     *
     * Date: 20/04/2018
     * @author George
     * @return mixed
     */
    public function permissiontable();
}