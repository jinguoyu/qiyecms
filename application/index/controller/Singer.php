<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Singer extends Commer
{
    public function index()
    {

        $site= Db::query("select * from think_column where id=2");//查询当前栏目的seo        

        $webset =$this->webset;

        $page =$webset['inpage'];
    $news = Db::name('music')->order('id desc')->paginate($page)->each(function($item,$key){


        return $item;
        });


        $page = $news->render();

    //最新2篇梅城资讯
    $xinwen = Db::query("select * from think_news where catid=5 and 'status'=0 order by id desc limit 2");

        $this->assign('page',$page);
        $this->assign('xinwen',$xinwen);
        $this->assign('news',$news);
        $this->assign('site',$site[0]);
        return $this->fetch();


    }

    public function article()
    {

    	$id = input('param.id');

$site= Db::query("select * from think_column where id=2");//查询当前栏目的seo        
    	$data =Db::query("select * from think_music where id='$id' ");

    	if(empty($data)){
    		$this->error("您所访问的页面不存在!");
    	}else{
            Db::execute("update think_music set views=views+1 where id='$id'");//每次访问增加一次点击量
            
    		$this->assign('data',$data[0]);

            //最新7篇梅城资讯
            $news = Db::query("select * from think_news where catid=5 and 'status'=0 order by id desc limit 7");

            
            $prenew = Db::query("select * from think_music where id <'$id'  order by id desc limit 1");//上一篇

            $nexnew = Db::query("select * from think_music where id > '$id'  order by id Asc limit 1");//下一篇

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


            $this->assign('site',$site);
            $this->assign('prenew',$prenew);
            $this->assign('nexnew',$nexnew);
            $this->assign('data',$data[0]);
            $this->assign('news',$news);
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
            $this->success("感谢你的评论，请继续欣赏！",url('singer/article',array('id'=>$newsid)));
        }
    }   
}

