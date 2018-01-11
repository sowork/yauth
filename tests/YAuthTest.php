<?php
/**
 * Created by PhpStorm.
 * User: dn
 * Date: 2017/12/21
 * Time: 16:01
 */

use Tests\TestCase;
use Sowork\YAuth\Facades\YAuth;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use \Illuminate\Foundation\Testing\DatabaseTransactions;

class YAuthTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use DatabaseTransactions;

    public function testCreatePermissions(){
        $permission=YAuth::createPermission(str_random());
        $permission->item_desc = 'permission desc';
        $this->assertTrue(YAuth::add($permission));
    }

    public function testCreateRole(){
        $role=YAuth::createRole(str_random());
        $role->item_desc = 'role desc';
        $this->assertTrue(YAuth::add($role));
    }

    public function testUpdateItem(){
        $permission=YAuth::createPermission(str_random());
        $permission->item_desc = 'permission desc';
        YAuth::add($permission);

        $permission->item_desc = 'update desc';

        $this->assertEquals(1, YAuth::update($permission));
    }

    public function testRemoveItem(){
        $permission = YAuth::createPermission(str_random());
        $permission->item_desc = 'testDeleteItem permission desc';
        YAuth::add($permission);

        $this->assertTrue(YAuth::remove($permission));

        // 测试递归删除
        $role1 = YAuth::createRole(str_random());
        $role1->item_desc = 'testDeleteItem permission desc2';
        YAuth::add($role1);

        $permission3 = YAuth::createPermission(str_random());
        $permission3->item_desc = 'testDeleteItem permission desc3';
        YAuth::add($permission3);

        $permission4 = YAuth::createPermission(str_random());
        $permission4->item_desc = 'testDeleteItem permission desc4';
        YAuth::add($permission4);

        YAuth::addChild($role1, $permission3);
        YAuth::addChild($role1, $permission4);

        YAuth::assign(1, $permission4, 'users');

        $this->assertTrue(YAuth::remove($permission4, true));
        $this->assertTrue(YAuth::remove($permission3));
    }

    public function testAddChild(){
        $permission = YAuth::createPermission(str_random());
        $permission->item_desc = 'testAddChild permission desc';
        YAuth::add($permission);

        $permission2 = YAuth::createPermission(str_random());
        $permission2->item_desc = 'testAddChild permission desc2';
        YAuth::add($permission2);

        $role = YAuth::createRole(str_random());
        $role->item_desc = 'testAddChild role desc';
        YAuth::add($role);

        $this->assertTrue(YAuth::addChild($role, $permission));

        $this->expectException(Psr\Log\InvalidArgumentException::class);
        YAuth::addChild($permission, $permission2);
    }

    public function testRemoveChild(){
        $permission = YAuth::createPermission(str_random());
        $permission->item_desc = 'testAddChild permission desc';
        YAuth::add($permission);

        $role = YAuth::createRole(str_random());
        $role->item_desc = 'testAddChild role desc';
        YAuth::add($role);

        YAuth::addChild($role, $permission);

        $this->assertEquals('2', YAuth::removeChild($role, $permission));
        $this->assertEquals('2', YAuth::removeChild($role, $permission, true));
    }

    public function testRemoveChildren(){
        $permission = YAuth::createPermission(str_random());
        $permission->item_desc = 'testAddChild permission desc';
        YAuth::add($permission);

        $permission2 = YAuth::createPermission(str_random());
        $permission2->item_desc = 'testAddChild permission desc2';
        YAuth::add($permission2);

        $role = YAuth::createRole(str_random());
        $role->item_desc = 'testAddChild role desc';
        YAuth::add($role);

        YAuth::addChild($role, $permission);
        YAuth::addChild($role, $permission2);

        $this->assertEquals('2', YAuth::removeChildren($role));
        $this->assertEquals('2', YAuth::removeChildren($role, true));
    }

    public function testRevoke(){
        $permission = YAuth::createPermission(str_random());
        $permission->item_desc = 'testAddChild permission desc';
        YAuth::add($permission);

        YAuth::assign(1, $permission, 'users');

        $this->assertEquals(1, YAuth::revoke(1, $permission));
        $this->assertEquals(1, YAuth::revoke(1, $permission, null, true));
    }

    public function testGetAssignments(){
        $result = YAuth::getAssignments(1, 'users');
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);

        $result = YAuth::getAssignments(1, 'users', NULL, true);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetUserRoles(){
        $result = YAuth::getUserRoles(1, 'users');
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);

        $result = YAuth::getUserRoles(1, 'users', true);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetUserPermissions(){
        $result = YAuth::getUserPermissions(1, 'users');
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);

        $result = YAuth::getUserPermissions(1, 'users', true);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetUserRole(){
        $name = str_random();
        $role = YAuth::createRole($name);
        $role->item_desc = 'testAddChild role desc';
        YAuth::add($role);

        $result = YAuth::getUserRole(1, 'users', $name);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);

        $result = YAuth::getUserRole(1, 'users', $name, true);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetUserPermission(){
        $name = str_random();
        $permission = YAuth::createPermission($name);
        $permission->item_desc = 'testAddChild permission desc';
        YAuth::add($permission);

        $result = YAuth::getUserRole(1, 'users', $name);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);

        $result = YAuth::getUserRole(1, 'users', $name, true);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetItems(){
        $result = YAuth::getItems();
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetPermissions(){
        $result = YAuth::getPermissions();
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);

        $result = YAuth::getPermissions(true);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetPermission(){
        $name = str_random();
        $permission = YAuth::createPermission($name);
        $permission->item_desc = 'testAddChild permission desc';
        YAuth::add($permission);

        $result = YAuth::getPermission($name);
        $this->assertTrue($result instanceof \Sowork\YAuth\YAuthItem);
        $result = YAuth::getPermission($name, true);
        $this->assertTrue($result instanceof \Sowork\YAuth\YAuthItem);
    }

    public function testGetRoles(){
        $result = YAuth::getRoles();
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);

        $result = YAuth::getRoles(true);
        $this->assertTrue($result instanceof \Illuminate\Database\Eloquent\Collection);
    }

    public function testGetRole(){
        $name = str_random();
        $role = YAuth::createRole($name);
        $role->item_desc = 'testAddChild role desc';
        YAuth::add($role);

        $result = YAuth::getRole($name);
        $this->assertTrue($result instanceof \Sowork\YAuth\YAuthItem);

        $result = YAuth::getRole($name, true);
        $this->assertTrue($result instanceof \Sowork\YAuth\YAuthItem);
    }

    public function testCheckAccess(){
        $name = str_random();
        $permission = YAuth::createPermission($name);
        $permission->item_desc = 'testAddChild permission desc';
        YAuth::add($permission);

        YAuth::invalidateCache();

        $this->assertFalse(YAuth::checkAccess(1, $name, 'users'));
        YAuth::assign(1, $permission, 'users');
        $this->assertTrue(YAuth::checkAccess(1, $name, 'users'));
    }

    public function testInvalidateCache(){
        YAuth::invalidateCache();
        $result = \Illuminate\Support\Facades\Cache::get(config('yauth.cacheKey'));
        $this->assertNull($result);
    }

}