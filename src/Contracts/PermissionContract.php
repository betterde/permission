<?php

namespace Betterde\Permission\Contracts;

/**
 * Interface PermissionContract
 * @package Betterde\Permission\Contracts
 * Date: 19/04/2018
 * @author George
 */
interface PermissionContract
{
    /**
     * 获取所有权限信息
     *
     * Date: 19/04/2018
     * @author George
     * @return mixed
     */
    public static function fetchAll();

    /**
     * 根据编码获取权限信息
     *
     * Date: 19/04/2018
     * @author George
     * @param string $code
     * @return mixed
     */
    public static function findByCode(string $code);

    /**
     * 创建权限
     *
     * Date: 19/04/2018
     * @author George
     * @param array $attributes
     * @return mixed
     */
    public static function store(array $attributes);

    /**
     * 更新权限信息
     *
     * Date: 19/04/2018
     * @author George
     * @param string $code
     * @param array $attributes
     * @return mixed
     */
    public static function modify(string $code, array $attributes);

    /**
     * 删除权限信息
     *
     * Date: 19/04/2018
     * @author George
     * @param string $code
     * @return mixed
     */
    public static function remove(string $code);

    /**
     * 定义关联关系
     *
     * Date: 19/04/2018
     * @author George
     * @return mixed
     */
    public function roles();
}