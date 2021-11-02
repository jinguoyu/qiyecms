<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use \think\Request;


 Class ShortFilm extends Common
 {

 	public function index(){
		$adpage =$this->adpage;//分页时每页的条数
		$list = Db::name('short_film')->order('id desc')->paginate($adpage)->each(function($item, $key){
		$item['addtime'] = date('Y-m-d H:i:s',$item['addtime']);
		return $item;
		});
		$page = $list->render();
 		$this->assign('list',$list); //文章
 		$this->assign('page', $page);//分页
 		 return $this->fetch();
 	}

 	public function add() {
 		$data = $_POST;
        
 		if(empty($data)) {
  			return $this -> fetch();
 		}
        else {
            if($data['title'] == '') {
 				$this->error('标题不能为空', 'short_film/add');
 			}
			$data['addtime'] = time();
			$getnum = Db::name('short_film')->insert($data);
            if($getnum>0) {
                $this->success('添加成功','short_film/index');
			 }else{
				$this->error('添加失败');
			 }
 		}
 	}


 	public function eidt(){
 		$id = input('param.id');
 		$data = Db::name('short_film')->where('id',$id)->find();
 		$this->assign('data',$data);
 		return $this->fetch();
 	}

 	public function delete(){
 		$id = input('param.id');
 		$num = Db::name('short_film')->where('id',$id)->delete();
 		if($num>0){
 			$this->success("删除成功",'short_film/index');
 		}else{
 			$this->error("删除失败，请重试");
 		}
	}


	/*
	*修改数据
	**/
	public function save(){
		$data = $_POST;
		$data['addtime']=time();
		$getnum = Db::name('short_film')->update($data);
		if($getnum>0){
			$this->success('修改成功','short_film/index');
		}else{
			$this->error('修改失败');
		}
 }
 

}
