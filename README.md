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
- 注册中间件`'yauth' => \Sowork\YAuth\Http\Middleware\YAuthAuthorize::class,`到`app/Http/Kernel.php` `$routeMiddleware`数组中
- 在需要权限的路由、路由组添加`yauth`中间件即可进行权限判断
- 在`app/Exceptions/Handler.php`监听`YAuthNotPermissionException`判断无权限回调

## API调用
> 建议： 权限名称以 路由名称为准，防止资源路由url相同导致的问题

- 添加权限
```
$permission=YAuth::createPermission('IySGxXZhM8Yj99qg');
$permission->item_desc = 'permission desc';
YAuth::add($permission);

$permission = YAuth::createPermission('soiyfnkodynxlldysadfc');
$permission->item_desc = 'permission desc';
YAuth::add($permission);
```
- 添加角色
```
$role=YAuth::createRole('3xhjG5l0WJifkAtt');
$role->item_desc = 'role desc';
YAuth::add($role);
```
- 修改权限/角色信息
```
$item=YAuthItem::find('wo1f7lqD5iiejB3m');
$item->item_desc = 'update role desc';
YAuth::update($item);
```
- 删除权限
```
YAuth::remove($permission)
YAuth::remove($permission, false) //第二个参数默认FALSE时表示进行软删除
```
- 给用户分配权限/角色
```
$item=YAuthItem::find('IySGxXZhM8Yj99qg');
YAuth::assign(1,$item);
```
- 给权限分配给权限
```
YAuth::addChild($permission, $permission);
```
- 给角色分配给权限
```
YAuth::addChild($role, $permission);
```
- 移除角色权限
```
$item = YAuthItem::find('3xhjG5l0WJifkAtt');
YAuth::revoke(1, $item);
YAuth::revoke(1, $item, null) // 第三个参数provider, e.g 'users' 代表users表 'xxx' 代表admins表， 默认为users
YAuth::revoke(1, $item, null, false) // 第四个参数默认为FALSE，使用软删除
```
- 获取分配给用户所有权限和角色
```
YAuth::getAssignments(1)
YAuth::getAssignments(1, null, null, false) // 第四个参数默认为false,默认表示不获取软删除的
```
- 获取分配给用户所有角色
```
YAuth::getUserRoles(1)
YAuth::getUserRoles(1, null, false) // 默认为false，表示不获取软删除
```
- 获取分配给用户所有权限
```
YAuth::getUserPermissions(1, 'provider')
YAuth::getUserPermissions(1, 'provider', false) // 默认为false，表示不获取软删除
```
- 获取分配给用户某个角色对象
```
YAuth::getUserRole(1, 'provider' '3xhjG5l0WJifkAtt')
YAuth::getUserRole(1, 'provider', '3xhjG5l0WJifkAtt', true)
```
- 获取分配给用户某个权限对象
```
YAuth::getUserPermission(1, 'provider', '3xhjG5l0WJifkAtt')
YAuth::getUserPermission(1, 'provider', '3xhjG5l0WJifkAtt', true)
```
- 获取所有items
```
YAuth::getItems()
```
- 获取所有权限
```
YAuth::getPermissions()
YAuth::getPermissions(false) // false 代表不获取软删除的
```
- 获取所有角色
```
YAuth::getRoles()
YAuth::getRoles(false) // false 代表不获取软删除的
```
- 获取单个权限对象
```
YAuth::getPermission('soiyfnkodynxlldysadfc')
YAuth::getPermission('soiyfnkodynxlldysadfc', false) // false 代表不获取软删除的
```
- 获得单个角色对象
```
YAuth::getRole('3xhjG5l0WJifkAtt')
YAuth::getRole('3xhjG5l0WJifkAtt', false) // false 代表不获取软删除的
```
- 检查某个用户是否存在权限
```
YAuth::checkAccess(1, '3xhjG5l0WJifkAtt') 
```
- 检查当前登录用户是否存在权限
```
YAuth::can('3xhjG5l0WJifkAtt')
```
- 刷新权限，默认删除、修改、增加权限不会更新缓存，需要手动调用
```
YAuth::invalidateCache();
```