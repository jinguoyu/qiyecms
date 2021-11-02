<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;
class Index extends Controller
{
    public function index()
    {
		$user =session::get('user'); //获取session中的user信息。

		 $id =input('param.id'); //传递过来的id

		 if(!empty($user) && $id==$user['userid']){


		 	$data =Db::name('admin')->where('userid',$id)->find();
		 	$data['lastlogintime']=date('Y-m-d H:i:s',$data['lastlogintime']);
	    	$info  = [
						'运行环境'     => PHP_OS.' '.$_SERVER["SERVER_SOFTWARE"],
						'PHP运行方式'  => php_sapi_name(),
						'php的版本'		=>phpversion(),
						'上传附件限制' => ini_get('upload_max_filesize'),
					    ];
  
			$newsnum =Db::name('news')->count();
			$catnum =DB::name('column')->count();
			 

			$this->assign('catnum',$catnum);
			$this->assign('newsnum',$newsnum);
			$this->assign('server',$info);
		 	$this->assign('user',$data);
    		return $this->fetch();

		 }else{
		 	$this->error("请先登录",'Login/index');
		 }



    }

    public function logout(){
    	session('user',null);
    	$this->redirect('Login/index');



    }
}
