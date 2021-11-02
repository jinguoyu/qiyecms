<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Index extends Commer
{
	public function index()
	{
		$site = Db::name('site')->find();

		$singer =Db::query("select * from think_music  order by addtime desc limit 8");//最新的演唱

		$news =Db::query("select * from think_news where catid=5 and status='0' and fenlei=0 order by id desc limit 6"); //最新梅城新闻
		
		$this->assign('news',$news);
		$this->assign('singer',$singer);
		$this->assign('site',$site);
		
		return $this->fetch();

	}
}

