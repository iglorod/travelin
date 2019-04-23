<?php

namespace frontend\tests\my;
use frontend\models\Post;
use frontend\models\Repost;
use frontend\models\Profile;
use InvalidArgumentException;
use Yii;

use common\models\LoginForm;

require_once 'D:Programm\OpenServer\OSPanel\domains\travelin\frontend\models\Post.php';
require_once 'D:Programm\OpenServer\OSPanel\domains\travelin\frontend\models\Repost.php';
require_once 'D:Programm\OpenServer\OSPanel\domains\travelin\frontend\models\Profile.php';
require_once 'D:Programm\OpenServer\OSPanel\domains\travelin\common\models\LoginForm.php';
class MyUnitTest extends\PHPUnit\Framework\TestCase
{
    /**
     * @var array|string the application configuration that will be used for creating an application instance for each test.
     * You can use a string to represent the file path or path alias of a configuration file.
     * The application configuration array may contain an optional `class` element which specifies the class
     * name of the application instance to be created. By default, a [[\yii\web\Application]] instance will be created.
     */

    public function testIsAuthor(){
        $G_Post=new Post();

        $post = array();
        array_push($post,['id'=>'0','id_author'=>'1']);

        $repost = array();
        array_push($repost,['id_post'=>'0','id_user'=>'3']);
        array_push($repost,['id_post'=>'0','id_user'=>'7']);

        $this->assertTrue($G_Post->getIsOneAuthor($post, $repost));
    }

    public function testLogin(){
        $login = new LoginForm();
        $user = array();
        array_push($user,['username'=>'Test1','password'=>'test1111','rememberMe'=>true]);
        $this->assertTrue($login->loginTest($user));
    }

    public function testBan(){
        $profile=new Profile();
        
        $users = array();
        array_push($users,['ban'=>'0']);
        array_push($users,['ban'=>'1']);
        array_push($users,['ban'=>'1']);

        $this->assertEquals($profile->getCountBanned($users), 2);
    }

    public function testExpectEcho()
    {
        $this->expectOutputString('echo');
        print 'echo';
    }

    public function testCount()
    {
        $posts = array();
        array_push($posts,['id_author'=>0]);
        array_push($posts,['id_author'=>0]);

        $profile = new Profile();
        
        $this->assertEquals($profile->post_count($posts, 0), 2);
    }
}
