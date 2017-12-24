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

class YAuthItemTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use DatabaseTransactions;

    public function testCreatePermissions(){
        $permission=YAuth::createPermission('IySGxXZhM8Yj99qg');
        $permission->item_desc = 'permission desc';
        $this->assertTrue(YAuth::add($permission));

        $permission = YAuth::createPermission('soiyfnkodynxlldysadfc');
        $permission->item_desc = 'permission desc';
        $this->assertTrue(YAuth::add($permission));
    }

    public function testCreateRole(){
        $role=YAuth::createRole('3xhjG5l0WJifkAtt');
        $role->item_desc = 'role desc';
        $this->assertTrue(YAuth::add($role));
    }

    public function testUpdateItem(){
        $permission=YAuth::createPermission('IySGxXZhM8Yj99qg');
        $permission->item_desc = 'permission desc';
        YAuth::add($permission);

        $permission->item_desc = 'permission desc4';
        $this->assertEquals(1, YAuth::update($permission));
    }

    public function testDeleteItem(){
        $permission=YAuth::createPermission('IySGxXZhM8Yj99qg');
        $permission->item_desc = 'permission desc';
        YAuth::add($permission);

        $this->assertEquals(1, YAuth::remove($permission));
    }

//    public function testAddChild(){
//        Mockery::mock('Sowork\YAuth\YAuthItem')
//            ->shouldReceive('find')
//    }

}