<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Session;

class Common extends Controller
{

	public function __construct()
	{
		parent::__construct();
		$user = session::get('user');

		if($user==null){

			$this->error('请先登录','Login/index');
		}

 		$ad=Db::name('webset')->where('id',1)->find();
 		$this->adpage =$ad['adpage'];//每页显示的条数
 		$this->drafttime = $ad['drafttime']; 



			


	}

	//无极限分类
	public function get_cates($data,$pid=0,$html="└─",$level=0){  
		$cate =array();
		$level++;
		foreach($data as $v){
		if ($v['pid'] == $pid){
				$v['html'] = str_repeat($html,$level-1);
				$v['level']=$level;
				$cate[]=$v;
				$cate = array_merge($cate, $this->get_cates($data, $v['id'], $html, $level));
			}

		}

		return $cate;
	} 
}