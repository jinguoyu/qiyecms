<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use \think\Request;


 Class HanDance extends Common
 {
 	public function index(){
		$adpage =$this->adpage;//分页时每页的条数
		$list = Db::name('handance')->order('id desc')->where('catid',1)->paginate($adpage)->each(function($item, $key){
		$item['addtime'] = date('Y-m-d H:i:s',$item['addtime']);
		return $item;
		});
		$page = $list->render();
 		$this->assign('list',$list); //文章
 		$this->assign('page', $page);//分页
 		 return $this->fetch();
 	}

 	public function add() {
		return $this -> fetch();
 	}

 	public function eidt(){
 		$id = input('param.id');
 		$data = Db::name('handance')->where('id',$id)->find();
 		$this->assign('data',$data);
 		return $this->fetch('han_dance/add');
 	}

 	public function delete(){
 		$id = input('param.id');
 		$num = Db::name('handance')->where('id',$id)->delete();
 		if($num>0){
 			$this->success("删除成功",'han_dance/index');
 		}else{
 			$this->error("删除失败，请重试");
 		}
	}


	/*
	*修改数据
	**/
	public function save(){
		$data = $_POST;
		if($data['title'] == '') {
 			$this->error('标题不能为空', 'han_dance/add');
 		}
		$data['addtime'] = time();
		$data['catid'] = 1;		
		if(isset($data['id']))
			$getnum = Db::name('handance')->update($data);
		else
			$getnum = Db::name('handance')->insert($data);

		if($getnum>0){
			$this->success('操作成功','han_dance/index');
		}else{
			$this->error('操作失败');
		}
 	}
}
