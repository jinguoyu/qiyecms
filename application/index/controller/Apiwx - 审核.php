<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Apiwx extends Gongnong
{

	//查询所有的新闻 
	public function news() {
		 $site= Db::query("select * from think_news where id=6"); 
		if(empty($site)) {
			$data = 1;
		}else {
			foreach($site as $k=>$v) {
				$time = $v['addtime'];
				$site[$k]['addtime'] = date('Y-m-d H:i:s', $time);
				$site['sh'] = 1;
			}
			$data =json_encode($site);
		}
		echo $data;
	}

	//查询所有演奏演唱作品中的音频 
	public function music() {
		if(empty($_GET)) {
			$page = 1;
		}else {
			$page = $_GET['p'];
		}
		$b = ($page-1)*10;
		$site= Db::query("select * from think_news where id=7 ");
		if(empty($site)) {
			$data = 1;
		}else {
			foreach($site as $k=>$v){
				$time = $v['addtime'];
				$site[$k]['addtime'] = date('Y-m-d H:i:s', $time);
				$site['sh'] = 1;
			}
			$data =json_encode($site);
		}
		echo $data;
	}
	
	//查询所有演奏演唱作品中的视频 
	public function video() {
if(empty($_GET)){
			$page = 1;
		}else {
			$page = $_GET['p'];
		}
		$b = ($page-1)*10;
		$site= Db::query("select * from think_news where id=23 ");

		if(empty($site)) {
			$data =1;
		}else {
			foreach($site as $k=>$v) {
				$time =$v['addtime'];
				$site[$k]['addtime'] = date('Y-m-d H:i:s',$time);
				$site['sh'] = 1;
			}
			$data =json_encode($site);
		}
		echo $data;
	}
	
	//查询所有演奏演唱作品中的视频 
	public function musicVideo() {
		$id = $_GET['id'];
		$date = Db::query("select * from think_music where id ='$id'");
		$data = $date[0];
		$time = $data['addtime'];
		$data['addtime'] = date('Y-m-d H:i:s', $time);
		$views = $data['views']+1;
		$data['views'] = $views;
		Db::execute("update think_music set views='$views' where id='$id'");
		$data = json_encode($data);
		echo $data;
	}

	/**文章详情页**/
	public function show() {
		$id = $_GET['id'];
		$date = Db::query("select * from think_news where id ='$id'");
		$data = $date[0];
		$time = $data['addtime'];
		$data['addtime'] = date('Y-m-d H:i:s', $time);
		$views = $data['views']+1;
		$data['views'] = $views;
		Db::execute("update think_news set views='$views' where id='$id'");
		$data = json_encode($data);
		echo $data;
	}
	/**提交评论**/
	public function comment() {
		$post = $_GET;
		$newsid = $post['newsid'];
		$name = $post['name'];
		$text = $post['text'];
		$image = $post['image'];
		$addtime = time();
		$column_id = $_GET['catid'];
		$news_title = $_GET['title'];

		$nid = Db::execute("insert into  think_comment(newsid,name,text,addtime,image,news_title,column_id) values('$newsid','$name','$text','$addtime','$image','$news_title',$column_id)");
		if($nid<1) {
			echo 1;
		}else{
			echo 2;
		}
	}

	/**获取当前文章的评论**/
	public function getcom() {
		$id = $_GET['id'];
		$catid = $_GET['catid'];
		$info = Db::query("select * from think_comment where newsid =' $id'and column_id =$catid order by id desc");
		$num = count($info);//评论总个数
		if(empty($info)) {
			echo 1;
		}else{
			foreach($info as $k=>$v){
				$time = $v['addtime'];
				$info[$k]['addtime'] = date('Y年m月d日',$time);
			}
			$data['num'] = $num;
			$data['info'] = $info;
			$data = json_encode($data);
			echo $data;
		}
	}

	/**查询所有的生日信息，并且阳历阴历都有，按照阴历的先后顺序排序**/
	public function getbirthday() {
		$data = Db::name('birthday')->order('day asc')->select();
		$gyear = date('Y',time());//当天的阳历的年
		$gyue = date('m',time());//当天的阳历的月
		$gri = date('d',time());//当天的阳历的日
		$nin = $this->convertSolarToLunar($gyear,$gyue,$gri);
		$nyear = $nin[0];

		foreach($data as $k=>$v) {
			$info[$k]['id'] = $v['id'];
			$info[$k]['name'] = $v['name'];
			if($v['cid']==0) {
				$info[$k]['yin'] = $v['yue']."月".$v['ri']."日";
				$a = $this->convertLunarToSolar($nyear,$v['yue'],$v['ri']);
				$info[$k]['yang'] = $a[0].'年'.$a[1]."月".$a[2]."日";
			}else {
				$info[$k]['yang'] = $v['yue']."月".$v['ri']."日";
				$b = $this->convertSolarToLunar($nyear,$v['yue'],$v['ri']);
				$info[$k]['yin'] = $b[0].'年'.$b[1]."月".$b[2]."日";
			}
		}
		$info = json_encode($info);
		echo $info;
	}

	/**查询谁马上要过生日了**/
	public function whobirthday(){
		$gyear = date('Y',time());//当天的阳历的年
		$gyue = date('m',time());//当天的阳历的月
		$gri = date('d',time());//当天的阳历的日

		$nin = $this->convertSolarToLunar($gyear,$gyue,$gri);//当天的农历日期
		$nyear = $nin[0];//当天的农历年
		$time1 = strtotime($gyear.'-'.$gyue.'-'.$gri);  //当天的时间戳
		if($nin[2]<10){
			$nin[2] = '0'.$nin[2];
		}
		$days = $nin[1].$nin[2];
		$res = Db::name('birthday')->where('day','>=',$days)->order('day asc')->find();

		//如果没有比今天大的日期说明今年都过完生日了，那就取第一个。
		if(empty($res)) {
			$res = Db::name('birthday')->order('day asc')->find();
			$nyear = $nyear+1; //因为是第二年了，所以需要年份加1
		}

		if($res['cid']==0){
			$info = $this->convertLunarToSolar($nyear,$res['yue'],$res['ri']);
			$time2 = strtotime($info[0].'-'.$info[1].'-'.$info[2]);
		}else {
			$time2 = strtotime($gyear.'-'.$res['yue'].'-'.$res['ri']);
		}

		$time = $time2-$time1;
		$times = $time/86400;
		$data['name'] = $res['name'];
		$data['time'] = $times;
		$info = json_encode($data);
		echo $info;
	}

	/**查询所有的育儿课堂**/ 
	public function yuer() {
		if(empty($_GET)){
			$page=1;
		}else {
			$page=$_GET['p'];
		}
		$b = ($page-1)*10;
		$site= Db::query("select * from think_news where catid=8 and status='0' order by id desc limit $b,10");

		if(empty($site)) {
			$data =1;
		}else{
			foreach($site as $k=>$v) {
				$time = $v['addtime'];
				$site[$k]['addtime'] = date('Y-m-d H:i:s', $time);
			}
			$data = json_encode($site);
		}
		echo  $data;
	}
	
	/**音乐详情页**/
	public function musicshow() {
		$id = $_GET['id'];
		$date = Db::query("select * from think_music where id ='$id'");
		$data = $date[0];
		$time = $data['addtime'];
		$data['addtime'] = date('Y-m-d H:i:s',$time);
		$views = $data['views']+1;
		$data['views'] = $views;
		Db::execute("update think_music set views='$views' where id='$id'");
		$rest['next'] = '';
		$rest['prev'] = '';
		$site= Db::query("select id,sid from think_music where fenlei=2 order by id desc"); 
		$num = count($site);
		
		foreach($site as $v) {
			if($v['id']<$id){
				$rest['next'] = $v['id'];
				$rest['nid'] = $v['sid'];
                break;
			}
			if($v['id']>$id){
				$rest['prev'] = $v['id'];
				$rest['pid'] = $v['sid'];
			}
		}
		
		if(empty($rest['next'])){
			$rest['next'] = $site[0]['id'];
			$rest['nid'] = $site[0]['sid'];
		}
		if(empty($rest['prev'])){
			$rest['prev'] = $site[$num-1]['id'];
			$rest['pid'] = $site[$num-1]['sid'];
		}
		$rest['conent'] = $data;
		$rest = json_encode($rest);
		echo $rest;
	}
	
	/**给我留言**/
	public function message() {
		$post = $_GET;
		$name = $post['name'];
		$text = $post['text'];
		$image = $post['image'];
		$addtime = time();
		$nid = Db::execute("insert into  think_message(name,text,addtime,image) values('$name','$text','$addtime','$image')");
		if($nid<1){
			echo 1;
		}else{
			echo 2;
		}
	}
	
	/**获取相册_照片**/
	public function photo() {
		if(empty($_GET)) {
			$page = 1;
			$nid = 1;
		}else {
			$page = $_GET['p'];
			$nid = $_GET['nid'];
		}
		$b = ($page-1)*10;
		$data = Db::query("select title,content,addtime,top from think_photo where nameid='$nid' and sort=1 order by top desc,id desc limit $b,10");
		
		if(empty($data)) {
			$data =1;
		}else{
			foreach($data as $k=>$v) {
				preg_match_all('/ src="(.*?)"/',$v['content'],$array);
				$num = count($array[1]);
				$data[$k]['content'] = $array[1];
				$data[$k]['addtime'] = date('Y年m月d日',$v['addtime']);
				$data[$k]['num'] = $num;

			}
			$data['sh'] = 1;
		}
		 $data = json_encode($data);
		
		 echo $data;
		
	}
	
	/**获取相册_视频列表**/
	public function pvideo() {
		if(empty($_GET)) {
			$page = 1;
			$nid = 1;
		}else {
			$page = $_GET['p'];
			$nid = $_GET['nid'];
		}
		$b = ($page-1)*10;
		$data = Db::query("select title,content,id from think_photo where nameid='$nid' and sort=2 order by id desc limit $b,10");
		  if(empty($data)) {
			  $data =1;
		  }else{
			  foreach($data as $k=>$v) {
				  preg_match_all('/ src="(.*?)"/',$v['content'],$array);
				  $data[$k]['content'] = $array[1][1];
				  $data[$k]['thumb'] = $array[1][0];
			  }
		 }
		 $data = json_encode($data);
		 echo $data;
	}
    
    	/**获取相册_视频详情**/
	public function pvideoId() {
        $get = $_GET;
        $id = $get['id'];
		$datas = Db::query("select title,content,addtime,click from think_photo where id='$id' and sort=2");
		  if(empty($datas)) {
			  $data =1;
		   }else{
                   $data['title'] = $datas[0]['title'];
				   preg_match_all('/ src="(.*?)"/',$datas[0]['content'],$array);
				   $data['content'] = $array[1][1];
				   $data['thumb'] = $array[1][0];
				   $data['addtime'] = date('Y年m月d日',$datas[0]['addtime']);
                   $data['click'] = $datas[0]['click'];
                   
                   $newClick = $datas[0]['click']+1;
                   Db::execute("update think_photo set click='$newClick' where id='$id'");
		  }
		  $data = json_encode($data);
		  echo $data;
	}
    
    /**查询所有的短片**/ 
	public function shortlist() {
		if(empty($_GET)){
			$page=1;
		}else {
			$page=$_GET['p'];
		}
		$b = ($page-1)*10;
		$site= Db::query("select * from think_news where id=6");
		if(empty($site)) {
			$data =1;
		}else{
			foreach($site as $k=>$v) {
				$time = $v['addtime'];
				$site[$k]['addtime'] = date('Y年m月d日', $time);
				$site['sh'] = 1;
			}
			$data = json_encode($site);
		}
		echo  $data;
	}
    
    /**获取短片详情**/
	public function shortId() {
        $get = $_GET;
        $id = $get['id'];
		$datas = Db::query("select * from think_short_film where id='$id'");
		  if(empty($datas)) {
			  $data =1;
		   }else{
                $data= $datas[0];
				$data['addtime'] = date('Y年m月d日',$datas[0]['addtime']);
		  }
		$views = $data['views']+1;
		$data['views'] = $views;
		Db::execute("update think_short_film set views='$views' where id='$id'");

		$data = json_encode($data);
		echo $data;
	}

}

