<?php
namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{
	 //注册页
   public function signUp()
   {
   	  $this->display();// view/Login.signup.html
   }

   //接受注册信息，保存到数据库
   public function register()
   {
   	  $data = $_POST;

   	  //密码是否为空
   	  if ( empty($data['uname']) || empty($data['upwd'])) {
   	  	$this->error('账号和密码不能为空~');
   	  }
   	  //密码是否一致
   	  if ($data['upwd'] !== $data['reupwd']) {
   	  	$this->error('密码不一致');
   	  }

   	  
   	  $data['upwd'] = password_hash($data['upwd'],PASSWORD_DEFAULT);
   	  $data['created_at'] = time();
   	  $data['auth'] = 3;

   	  $row = M('bbs_user')->add($data);

   	  if ($row) {
   	  	$this->success('注册成功！','/');
   	  }else{
   	  	$this->error('注册失败！');
   	  }



   	  
   }

   public function dologin()
   {
   	$uname = $_POST['uname'];
   	$upwd = $_POST['upwd'];
    
    $user = M('bbs_user')->where("uname='$uname'")->find();

    if ($user && password_verify($upwd,$user['upwd']) ) {
        $_SESSION['userInfo'] = $user;
        $_SESSION['flag'] = true;


    	$this->success('登录成功！','/');
    }else{
    	$this->error('账号或密码错误！');
    }

   }

   //退出
   public function loginout()
   {

   	$_SESSION['flag'] = false;
   	$this->success('正在退出...','/');  
   }


}

  