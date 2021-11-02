<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
class Login extends Controller
{
    public function index()
    {
     	$captcha = new \ver\Captcha();	
 		 $post =$_POST;

		 		$n='';
		 		$password='';
		 		$y='';

 		 if(!empty($post)){
 		 	 	$name =$post['name'];
 		 		$pass = $post['password'];
		 		$code =$post['code'];

 				$cap = $captcha->check($code, '');
 				if($cap){
 				 	//$this->success('登录成功！');
 				 	$n = Db::name('admin')->where('username',$name)->find();
 				 	if(empty($n)){
 				 		$this->error("用户名不存在");

 				 	}else{
 				 		$sha =$n['encrypt']; //随机字符串
 				 		$password =sha1($pass.$sha);
 				 		$y = Db::name('admin')->where('password',$password)->find();

 				 		if(empty($y)){
 				 			$this->error("密码错误",'Login/index');
 				 		}else{

 				 				$id=$y['userid'];
 				 				$data['lastlogintime']=time();
 				 				$data['lastloginip'] =$_SERVER['REMOTE_ADDR'];
 				 				$data['num'] = $y['num']+1;
 				 				Db::name('admin')->where('username',$name)->update($data);

 				 
 				 			Session::set('user',$y);
 				 			$this->success('登录成功',url('Index/index',array('id'=>$id)));
 				 		}
 				 	}

 				 
 				}else{
 					$this->error("验证码错误",'Login/index');
 				}

			}



    	return view();

    }

    //生成验证码
    public function captcha(){
    	$captcha = new \ver\Captcha();
    	$captcha->length = 4;//验证码的长度
    	// $captcha->useZh = true; //使用中文验证码
       // $captcha->codeSet = '0123456789'; //使用数字
    	$captcha->useImgBg = true;
        $captcha->fontSize = 35;
    	$captcha->reset = true;
        $captcha->useCurve=false;
    	return $captcha->entry();
    }



}
