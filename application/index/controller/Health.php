<?php
namespace app\index\controller;
use think\Db;
use think\Controller;

class Health extends Commer
{

	public function index()
	{
    	$site= Db::query("select * from think_column where id=9");//查询当前栏目的seo
		$webset =$this->webset;

		$page =$webset['inpage'];
			$news = Db::name('news')->where('catid',9)->where('status',0)->order('id desc')->paginate($page)->each(function($item,$key){

		return $item;
		});


		$page = $news->render();

		$this->assign('page',$page);
		$this->assign('news',$news);
    	$this->assign('site',$site[0]);		
		return $this->fetch();
	}

	public function article()
	{
		$id = input('param.id');

		$data = Db::query("select * from think_news where id='$id' and catid=9");

		$prenew = Db::query("select * from think_news where id <'$id' and catid=9 order by id desc limit 1");//上一篇

		$nexnew = Db::query("select * from think_news where id > '$id' and catid=9 order by id Asc limit 1");//下一篇

		if(empty($prenew)){
			$prenew ="";
		
		}else{
			$prenew = $prenew[0];
		}

		if(empty($nexnew)){
			$nexnew ="";
		
		}else{
			$nexnew =$nexnew[0];
		}


		if(empty($data)){

			$this->error("你所访问的页面不存在！");

		}else{

			//最新4篇演唱作品
    		$music = Db::query("select * from think_music  order by id desc limit 4");
			//最新7篇健康百科
    		$news = Db::query("select * from think_news where catid=9 and 'status'=0 order by id desc limit 7");

    		Db::execute("update think_news set views=views+1 where id='$id'");//每次访问增加一次点击量

    		$this->assign('music',$music);
    		$this->assign('news',$news);
			$this->assign('data',$data[0]);
			$this->assign('prenew',$prenew);
			$this->assign('nexnew',$nexnew);
			return $this->fetch();

		}

	}

	/*评论*/
	public function comment()
	{
		$post = $_POST;
		$newsid = $post['newsid'];
		$name = $post['name'];
		$text = $post['text'];
		$addtime = time();

		$nid =Db::execute("insert into  think_comment(newsid,name,text,addtime) values('$newsid','$name','$text','$addtime')");
		if($nid<1){
			$this->error("评论失败！");
		}else{
			$this->success("感谢你的评论，请继续浏览！",url('news/article',array('id'=>$newsid)));
		}
	}

}





?>