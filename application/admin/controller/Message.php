<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Session;

Class Message extends Common
{
	public function index()
	{

		$adpage =$this->adpage;//分页时每页的条数

		$data =Db::name('message')->order('id desc')->paginate($adpage)->each(function($item,$key){


			return $item;
		});

		$page = $data->render();

		$this->assign('page',$page);
		$this->assign('data',$data);
		return $this->fetch();
	}

	public function article()
	{
		$id = input("param.id");
		$data =Db::query("select * from think_message where id='$id'");

		$this->assign("data",$data[0]);
		return $this->fetch();
	}

	public function delete()
	{
		$id = input('param.id');

		$num = Db::execute("delete from think_message where id='$id'");

		if($num==1){
			$this->success("删除成功",'message/index');
		}else{
			$this->error("删除失败，请重试",'message/index');
		}
	}

//批量删除
	public function modelete(){

		$arr = $_POST;
		$id_arr =$arr['id'];

		for($i=0;$i<count($id_arr);$i++){
			$id =$id_arr[$i];
			$num = Db::execute("delete from think_message where id='$id'");

			if($num !=1){
				$this->error("id为:".$id."删除失败，请重试");
			}



		}

		$this ->success("批量删除成功！");

	}
}

?>