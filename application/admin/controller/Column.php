<?php
namespace app\admin\controller;
use think\Db;
use think\Session;


 Class Column extends Common
 {
 	public function index(){
 		
 		$cat = Db::name('column')->select();
 		$cate = $this->get_cates($cat);
 		$this->assign('cate',$cate);

 		return $this->fetch();
 	}

 	public function add(){

 		$data = $_POST;
 		$cat = Db::name('column')->select();
 		$cate = $this->get_cates($cat);

 		$this->assign('cate',$cate);
 		return $this->fetch();

 	}

//为了避免修改信息时把上级栏目改成自己的子栏目。所以当有子栏目时，不允许修改上级栏目
public function edit(){
	//如果post为空没说明是访问修改页面，post不为空，将post提交的信息在数据库修改
	$post = $_POST;
	if(empty($post)){
		$cat = Db::name('column')->select();
 		$cate = $this->get_cates($cat);

		$id = input('param.id');
		$data = Db::name('column')->where('id',$id)->find();
		$this->assign('data',$data);
		$this->assign('cate',$cate);

		return $this->fetch();

		
	}else{
		$catid = $post['id']; 
		$pid = $post['pid']; //提交过来的上级栏目id
		$peid = $post['peid'];//原来的上级栏目id
		$a = Db::name('column')->where('pid',$catid)->select();
		if($peid !=$pid && !empty($a)){
			$this->error("该栏目存在子栏目，无法修改上级栏目");
		}else{
			unset($post['peid']);
			$num = Db::name('column')->where('id',$catid)->update($post);
			if($num>0){
				$this->success('修改成功','column/index');
			}else{
				$this->error('栏目修改失败');

			}
		}

	}


} 

public function delete(){
	$id = input('param.id');//获取到传递过来的栏目id

	//先查询数据库，判断一下是否有子栏目，如果有的话就报错无法删除

	$info = Db::name('column')->where('pid',$id)->select();
	if(!empty($info)){
		$this->error('该栏目存在子栏目，无法删除');
	}
	$news = Db::name('news')->where('catid',$id)->select();
	if(!empty($news)){
		$this->error('该栏目存在文章，无法删除');
	}


	$num = Db::name('column')->where('id',$id)->delete();
	if($num>0){
		$this->success('栏目删除成功');
	}else{
		$this->error('栏目删除失败，请重试');
	}

}

public function cateadd(){
	$id = input('param.id');//获取到传递过来的栏目id
 	$cat = Db::name('column')->select();
 	$cate = $this->get_cates($cat);

 	$this->assign('cate',$cate);
 	$this->assign('id',$id);
	return $this->fetch();
}


//添加栏目
public function save(){

	$data=$_POST;
	$name = $data['catname'];
 	$a = Db::name('column')->where('catname',$name)->select();
 	
 	if($a){
 		$this->error("栏目名称已经存在，请修改");
 	}else{
 		$b = Db::name('column')->insert($data);
 		if($b>0){
 			$this->success("添加栏目成功",'column/index');
 		}else{
 			$this->error("栏目添加失败，请重试");
 		}
 	}

}


 }