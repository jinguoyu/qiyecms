<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use \think\Request;


 Class Music extends Common
 {
 	public function index() {
		$adpage = $this->adpage;//分页时每页的条数
 		$list = Db::name('music') -> order('sort desc,id desc') -> paginate($adpage) -> each(function($item, $key) {
 			$item['addtime'] = date('Y-m-d H:i:s', $item['addtime']);
 			return $item;
 		});
 		$page = $list->render();
 		$this -> assign('list', $list); //文章
 		$this -> assign('page', $page);//分页
 		return $this -> fetch();
 	}

 	public function add(){
 		$data = $_POST;
 		//当$data为空时就是刚打开页面的时候，当$data不为空时就是有post提交的时候
 		if(empty($data)) {
  			return $this->fetch();
 		}

 		if($data['title'] == '') {
 			$this->error('标题不能为空','music/add');
 		}
 		if($data['content'] == '') {
 			$this->error('文章内容不能为空','music/add');
 		}

		//如果发布时间为空，就添加上当前时间
		if($data['addtime'] == '') {
			$data['addtime'] = time();
		}
		else {
				$data['addtime'] = strtotime($data['addtime']);
			}
			$data['updatetime'] = time();
			$getnum = Db::name('music') -> insert($data);

			if($getnum > 0) {
				$this -> success('内容添加成功', 'music/index');
			}
			else {
				$this -> error('内容添加失败');
			}
 		}


 	public function eidt(){
 		$id = input('param.id');
 		$data = Db::name('music') -> where('id', $id) -> find();
 		$data['addtime'] = date('Y-m-d H:i:s', $data['addtime']);
 		$this -> assign('data', $data);
 		return $this -> fetch();
 	}

 	public function save(){
 		$data = $_POST;
 		$riqi = date("Ymd", time());
		$data['addtime'] = strtotime($data['addtime']);
		$data['updatetime'] = time();
		$getnum = Db::name('music') -> update($data);
		if($getnum > 0) {
				$this -> success('内容修改成功', 'music/index');
			}else{
				$this -> error('内容修改失败');
			}	

 	}

 	//单个文章的删除
 	public function delete(){
 		$id = input('param.id');
 		$num = Db::name('music')->where('id',$id)->delete();
 		if($num>0){
 			$this->success("文章删除成功",'music/index');
 		}else{
 			$this->error("文章删除失败，请重试");
 		}
	}

	//批量删除
	public function modelete(){
			$ids =$_POST['id'];

			for($i=0;$i<count($ids);$i++){
				$id =$ids[$i];
				$num = Db::name('music')->where('id',$id)->delete();
				if($num<1){
					$this->error('批量删除错误');
				}
			}
			$this->success("批量删除成功",'music/index');

	}

	//批量排序
	public function sorts(){

		$data =$_POST;//获取from的提交数据

		$sort =$data['sort'];

		foreach($sort as $k=>$v){
			$info['sort']=(int)$v;
			 $num = Db::name('music')->where('id',$k)->update($info);

			
			if($num===false){

				$this->error("排序修改错误");
			}

		}
		$this->success("排序修改成功",'music/index');
	}





}
