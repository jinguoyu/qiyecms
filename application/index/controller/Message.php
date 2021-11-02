<?php
namespace app\index\controller;
use think\Db;
use think\Controller;

class Message extends Controller
{
	public function index()
	{
		return $this->fetch();
	}

	public function save()
	{
		$post = input('post.');

		$addtime =time();
		$name = $post['name'];
		$mail = $post['mail'];
		$phone = $post['phone'];
		$address = $post['address'];
		$text = $post['text'];

		if(empty($name)){
			$this->error("名字不能为空，请重试！");
		}

		if(!preg_match("/^1[34578]\d{9}$/",$phone)){
			$this->error("请输入正确的手机号码！");
		}

		if(empty($text)){
			$this->error("留言内容不能为空！");
		}


		$info = Db::execute("insert into think_message(name,mail,phone,address,text,addtime) values('$name','$mail','$phone','$address','$text','$addtime')");

		if(empty($info)){

			$this->error("留言失败，请重试！");

		}else{

			$this->success("留言成功，我们会尽快联系你！");

		}


	}
}




?>