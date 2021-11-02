<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use \think\Request;


 Class Photo extends Common
 {

 	public function index(){
		$adpage =$this->adpage;//分页时每页的条数
		$list = Db::name('photo')->order('id desc')->paginate($adpage)->each(function($item, $key){
			$id = $item['nameid'];
			$column_id = Db::name('name')->where('id',$id)->find();
			$item['name']=$column_id['name'];
			$item['addtime'] = date('Y-m-d H:i:s',$item['addtime']);
			return $item;
		});
		$page = $list->render();
 		$this->assign('list',$list); //文章
 		$this->assign('page', $page);//分页
 		 return $this->fetch();
 	}

	/*
	*添加相册
	*/
 	public function add(){
 		$data = $_POST;

 		if(empty($data)){
 			$names = Db::name('name')->select();
 			$this->assign('names',$names);
  			return $this->fetch();			
 		}else{
 			/*
 			*增加判断，如果标题为空时报错。
 			*/
 			if($data['title']==''){
 				$this->error('标题不能为空','photo/add');
 			}
			//如果发布时间为空，就添加上当前时间
			if($data['addtime']==''){
				$data['addtime']=time();
			}else{
				$data['addtime']=strtotime($data['addtime']);				
			}

			$getnum = Db::name('photo')->insert($data);
	
			if($getnum>0){
				$this->success('添加成功','photo/index');
			}else{
				$this->error('添加失败');
			}	
 		}
 	}


 	public function eidt(){
 		$id = input('param.id');

 		$data = Db::name('photo')->where('id',$id)->find();
 		$data['addtime']=date('Y-m-d H:i:s',$data['addtime']);

 		$names = Db::name('name')->select();

 		$this->assign('names',$names);
 		$this->assign('data',$data);
 		return $this->fetch();
 	}

 	//单个文章的删除
 	public function delete(){
 		// $id = input('param.id');
 		// $num = Db::name('photo')->where('id',$id)->delete();
 		// if($num>0){
 			// $this->success("删除成功",'photo/index');
 		// }else{
 			// $this->error("删除失败，请重试");
 		// }
        echo '相册删除了就没有了';
	}



	/*
	*修改数据
	**/
	public function save(){
		$data = $_POST;
		$data['addtime']=strtotime($data['addtime']);
		$getnum = Db::name('photo')->update($data);
		if($getnum>0){
			$this->success('修改成功','photo/index');
		}else{
			$this->error('修改失败');
		}		

 
 }
 
 /*
*添加相册所属的名字
**/
 public function name() {
	 $data['name'] = input('param.name');
	if(!empty($data['name'])) {
		Db::name('name')->insert($data);
		$this->success("名字添加成功",'photo/index');
	}
	else 
		return $this->fetch();		
	 
 
 }
 

}
