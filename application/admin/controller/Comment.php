<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Session;

Class Comment extends Common
{

public function index(){
	
	$page =$this->adpage; //获取分页 

	$data =Db::name('comment')->order('id desc')->paginate($page)->each(function($item,$key){
			
			$id =$item['newsid'];
			$item['catid'] = $catid = $item['column_id'];
			$item['title'] =$item['news_title'];
			$arr =Db::name('column')->where('id',$catid)->find();

			$item['catname'] = $arr['catname'];
			$item['url'] = $arr['url'];

			return $item;
	});

	$page = $data->render();

	$this->assign('page',$page);
	$this->assign('data',$data);

	return $this->fetch();

}

public function article()
{
	$id = input('param.id');

	$datas =Db::query("select * from think_comment where id='$id'");
	$nid =$datas[0]['newsid'];

	 $arr = Db::query("select title,catid from think_news where id='$nid'");
	 if(!empty($arr)) {
		$datas[0]['catid'] = $arr[0]['catid'];	 	
	 }

	$this->assign('data',$datas[0]);

	return $this->fetch();
}

public function delete()
{
	$id = input('param.id');

	$nid = Db::execute("delete from think_comment where id='$id'");

	if($nid<1){
		$this->error("删除失败请重试");
	}else{
		$this->success('删除成功','comment/index');
	}

}

public function modelete()
{

	$post = $_POST;
	$arrid =$post['id'];

	for($i=0;$i<count($arrid);$i++){
		$id=$arrid[$i];
		$nid = Db::execute("delete from think_comment where id='$id'");
		if($nid<1){
			$this->error('id为：'.$id.'删除失败请重试','comment/index');
		}
	}
	$this->success('批量删除成功','comment/index');
}

public function revise()
{
	$post = $_POST;

	$id = $post['id'];
	$name = $post['name'];
	$text = $post['text'];

	$nid = Db::execute("update think_comment set name='$name',text='$text' where id='$id'");

	if($nid<1){
		$this->error("修改失败，请重试");
	}else{
		$this->success("评论修改成功",url('comment/article',array('id'=>$id)));
	}

}


}