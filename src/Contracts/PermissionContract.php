<?php

namespace Betterde\Permission\Contracts;

interface PermissionContract
{
    public static function fetchAll();

    public static function findByCode(string $code);

    public static function store(array $attributes);

    public static function modify(string $code, array $attributes);

    public static function remove(string $code);
}