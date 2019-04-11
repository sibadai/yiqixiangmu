<?php
namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller
{
   //登录页面
   public function login()
   {
   	  $this->display(); // view/Login/login.html
     
   	
   }

   //接收登录页面账号和密码等信息，进行验证
   public function dologin()
   {

   	$uname = $_POST['uname'];
   	$upwd = $_POST['upwd'];
    $code = $_POST['code'];


    $verify = new \Think\Verify();
    if (!$verify->check($code) ) {
      $this->error('验证码不对！');
    }


    $brr['uname'] = [EQ,"$uname"];
    $brr['auth']  = [LT,3];
    //查找结果为管理员或者超级管理员，若非其一，登录失败
    
   	$user = M('bbs_user')->where($brr)->find();
    

   	if ($user && password_verify($upwd,$user['upwd'] ) ) {
        //保存当前登录成功的用户信息
        $_SESSION['userInfo'] = $user;

        //是否登录 true 登录成功 false 没有登录
        $_SESSION['flag'] = true;

   		$this->success('登录成功','/index.php?m=admin&c=index&a=index');
   	}else{
   		$this->error('账号或密码错误或非管理员登录~');
   	}

   }
   //退出
   public function outlogin()
   {
   	$_SESSION['userInfo'] = NULL;
   	$_SESSION['flag'] = false;

   	$this->success('正在退出...','/index.php?m=admin&c=login&a=login');

   }


   public function code()
   {
       $config = array(
                 'fontSize' => 20, //验证码字体大小
                 'length' => 3,   //验证码位数
                 'useNoise' => false, //关闭验证码杂点
                 'useCurve' => false,
                 'imageW' => 120,
                 'imageH' => 40,
                 );

       $Verify = new \Think\Verify( $config );
       $Verify->entry();
   }
}