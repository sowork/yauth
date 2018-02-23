# yauth 
> larave5 RBAC权限管理，多表用户权限判断

## 安装
- composer安装 `composer require sowork/yauth dev-master`
- 注册:在`config/app.php`文件中`providers`数组中注册提供者 `Sowork\YAuth\YAuthServiceProvider::class,`，在`config/app.php`文件`aliases`数组中注册`'YAuth' => Sowork\YAuth\YAuthServiceProvider::class,`
    
## 资源发布
- 默认 `php artisan vendor:publish` 会发布配置文件和数据库迁移文件，单独发布如下。
- 数据库表迁移 `php artisan migrate`
- 如果你不想使用默认yauth的数据库表，或者想基于yauth表进行修改，需要在`AppServiceProvider`的`register`方法中调用`YAuth::ignoreMigrations();`使用`php artisan vendor:publish --tag=yauth-migrations`导出默认迁移表
- 配置文件发布 `php artisan vendor:publish --tag=yauth-config`

## 中间件


## API调用