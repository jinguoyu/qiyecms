<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Hanleyuan extends Gongnong
{
	private $shenhe = 0;//0为正式环境，-1为审核

	//查询所有的广场舞
	public function dance() {
		$site = Db::query("select * from think_handance where catid = 1 order by id desc");
		$data['sh'] = $this->shenhe;
		$data['data'] = $site;
		$data =json_encode($data);
		echo $data;
	}

	//查询所有的狗狗
	public function dog() {
		$site = Db::query("select * from think_handance where catid = 2 order by id desc");
		$data['sh'] = $this->shenhe;
		$data['data'] = $site;
		$data =json_encode($data);
		echo $data;
	}

	//增加浏览量
	public function addviews(){
		$id = $_GET['id'];
		Db::query("update think_handance set views='$views' where id='$id'");
	}

		/**给我留言**/
	public function message() {
		$post = $_GET;
		$name = $post['name'];
		$text = $post['text'];
		$lianxi = $post['lianxi'];
		$addtime = time();
		$nid = Db::execute("insert into  think_hanmessage(name,text,addtime,lianxi) values('$name','$text','$addtime','$lianxi')");
		if($nid<1){
			echo 1;
		}else{
			echo 2;
		}
	}

}

