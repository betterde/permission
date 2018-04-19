## 使用说明

#### 发布配置文件到项目
```
php artisan vendor:publish --tag=permission
```
#### 配置文件

文件所在目录：项目根目录下的 config/permission.php

```php

<?php

return [
    // 自定义模型
    'model' => Betterde\Role\Models\Permission::class,
    // 自定义数据表
    'table' => 'permission',
    // 自定义缓存
    'cache' => [
        // 是否开启缓存
        'enable' => true,
        // 缓存命名空间前缀
        'prefix' => 'betterde',
        // 缓存的数据库配置
        'database' => 'cache'
    ]
];
```

#### 常用命令

```
// 缓存系统权限到Redis
php artisan role:cache
```

```
// 清空缓存
php artisan role:flush
```

#### 自定义模型

如果需要自定义模型，只需要替换配置文件中 `model` 的指向，新的模型需要实现 `Betterde\Role\Contracts\PermissionContract` 这个接口中的方法！

如果需要自定义表，只需要替换配置文件中的 `table` 即可!