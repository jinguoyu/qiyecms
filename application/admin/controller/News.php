<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use \think\Request;
use app\extra\AliOss;


 Class News extends Common
 {

 	public function index(){
		$adpage = $this->adpage;//分页时每页的条数
 		$list = Db::name('news')->where("status",0)->order('sort desc,id desc')->paginate($adpage)->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:i:s',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";    break;
 			}
 			return $item;

 		});

 		$info=Db::name('column')->where('is_news',1)->select();
 		$page = $list->render();

 		$this->assign('info',$info); //栏目

 		$this->assign('list',$list); //文章
 		$this->assign('page', $page);//分页

 		 return $this->fetch();
 	}

 	public function add(){
 		$drafttime =$this->drafttime;//草稿箱时间
 		$this->assign('drafttime',$drafttime);

 		//当$data为空时就是刚打开页面的时候，当$data不为空时就是有post提交的时候
 		$cat = Db::name('column')->where('is_news', 1)->select();
		$result = $this->get_cates($cat); 

		//如果有子栏目，则res=1，如果没有子栏目，则res=0
		foreach($result as $k=>$v){
			$id =$v['id'];
			$a = Db::name('column')->where('pid',$id)->find();
			if(empty($a)){
				$result[$k]['res'] =0;
			}else{
				$result[$k]['res']=1;
			}			
		}
 		$this->assign('result',$result);
  		return $this->fetch();			
 	}


 	public function eidt(){
 		$id = input('param.id');

 		$data = Db::name('news')->where('id',$id)->find();
 		$data['addtime']=date('Y-m-d H:i:s',$data['addtime']);

 		$cat = Db::name('column')->where('is_news', 1)->select();
		$result = $this->get_cates($cat); 

		//如果有子栏目，则res=1，如果没有子栏目，则res=0
			foreach($result as $k=>$v){
				$id =$v['id'];
				$a = Db::name('column')->where('pid',$id)->find();
				if(empty($a)){
					$result[$k]['res'] =0;
				}else{
					$result[$k]['res']=1;
				}			
			}


 	 	$drafttime =$this->drafttime;//草稿箱时间

 		$this->assign('drafttime',$drafttime);

 		$this->assign('result',$result);
 		$this->assign('data',$data);
 		return $this->fetch();
 	}

 	public function save() {
 		$data = $_POST;

 		//增加判断，如果标题或者内容为空时返回。
 		if($data['title'] == '' || $data['content'] == '') {
			$this->error('标题和内容不能为空', 'news/add');
		}

		//获取上传的文件,如果是添加文章，取thumb的值，如果是修改文章，且thumbEidt有值，说明有修改缩略图，取thumbEidt的值
		$file = '';
		if(!empty(request()->file('thumb'))) {
			$file = request()->file('thumb');
		}else if(!empty(request()->file('thumbEidt'))) {
			$file = request()->file('thumbEidt');
		}

		// echo '<pre>';
		// var_dump($file);
		// var_dump(request()->file('thumb1'));
		// var_dump($data);
		// exit;
		//如果有上传附件，就立即传到oss.
		if(!empty($file)) {
			$info = $file->getInfo(); //获取上传的文件信息
			$fileName = $info['name']; //上传文件的名字(包括后缀)
			$fileTmp = $info['tmp_name'];//上传文件的完整路径
			$fileSize = $info['size']; //上传文件的大小
			$extension = pathinfo($fileName, PATHINFO_EXTENSION); //获取文件后缀
			$time = date('YmdHis', time()); //当前时间戳

			//上传到oss服务器
			$ossClient  = AliOss::getOssClient();
			$bucketName = AliOss::getBucketName();
			   
			$fileName = 'images/'.$time.'.'.$extension;// 文件名
   
			// 执行上传并获取返回 oss 信息
			$Client = $ossClient->uploadFile($bucketName, $fileName, $fileTmp);
			$data['thumb'] = $Client['oss-request-url'];//上传oss后文件的地址
			// 如果图片的协议是http，则转换成https
			if (substr($data['thumb'], 0, 4) == 'http') {
				$data['thumb'] = substr_replace($data['thumb'], 'https', 0, 4);
			}

		}

		/*
			*如果推荐位不存在，说明推荐位是空的，都没选
			*如果推荐位是数组就把数组的值相加
		*/

		if(array_key_exists('posids',$data)) {
			if(count($data['posids']) == 1) {
				$data['posids'] = $data['posids'][0];
			} elseif(count($data['posids']) == 2) {
				$data['posids'] = ($data['posids'][0] + $data['posids'][1]);
			} elseif(count($data['posids']) == 3){
				$data['posids']=($data['posids'][0] + $data['posids'][1] + $data['posids'][2]);	
			}
		}
						   
	   //如果发布时间为空，就添加上当前时间
	   if($data['addtime'] == '') {
		   $data['addtime'] = time();
	   } else {
		   $data['addtime'] = strtotime($data['addtime']);				
	   }

	   $data['updatetime'] = time();
	   if($data['id'] == 0) {
		   unset($data['id']);
		   $getnum = Db::name('news')->insert($data);
	   } else {
		   $numid = $data['id'];
		   unset($data['id']);
		   $getnum = Db::name('news')->where('id',$numid)->update($data);
	   }



	   if($getnum > 0) {
		   $this->success('文章更新成功','news/index');
	   } else {
		   $this->error('文章更新失败');
	   }	

 	}

 	//单个文章的删除
 	public function delete(){
 		$id = input('param.id');
 		$num = Db::name('news')->where('id',$id)->delete();
 		if($num>0){
 			$this->success("文章删除成功",'news/index');
 		}else{
 			$this->error("文章删除失败，请重试");
 		}
	}

	//批量删除
	public function modelete(){
			$ids =$_POST['id'];

			for($i=0;$i<count($ids);$i++){
				$id =$ids[$i];
				$num = Db::name('news')->where('id',$id)->delete();
				if($num<1){
					$this->error('批量删除错误');
				}
			}
			$this->success("批量删除成功",'news/index');

	}

	//批量排序
	public function sorts(){

		$data =$_POST;//获取from的提交数据

		$sort =$data['sort'];

		foreach($sort as $k=>$v){
			$info['sort']=(int)$v;
			 $num = Db::name('news')->where('id',$k)->update($info);

			
			if($num===false){

				$this->error("排序修改错误");
			}

		}
		$this->success("排序修改成功",'news/index');
	}

	//批量移动到某个栏目
	public function cate(){
		$catid =$_POST['movecid'];
		$ids = $_POST['id'];

		for($i=0;$i<count($ids);$i++){
			$id =$ids[$i];
			$num = Db::name('news')->where('id',$id)->update(['catid'=>$catid]);
			if($num===false){
				$this->error("移动栏目错误");
			}
		}	

		$this->success("移动栏目成功",'news/index');

	}

	//批量移动到首页
 	public function ishome(){

 		$set = $_POST['ishome'];//当set等于1时，所选的id都推荐到首页，当set等于0时，所选的id都取消推荐到首页

 		$ids = $_POST['id']; //被选中的所有id

 		$posids =$_POST['posids']; //id和推荐位值对应的数组

 	//通过id可以在posids对应的值得到推荐位。然后对推荐位的值进行判断如果没有推荐的修改数据进行推荐
 		if($set==1){
 			for($i=0;$i<count($ids);$i++){
				$id = $ids[$i];
				$posid =$posids[$id];
				if($posid ==0 or $posid==2 or $posid==4 or $posid==6){
					$num = Db::name('news')->where('id',$id)->update(['posids'=>($posid+1)]);
					if($num <=0){
						$this->error("移动失败");
					}
				}
			}
			$this->success('批量推荐到首页成功');

 		}elseif($set==2){
 			for($i=0;$i<count($ids);$i++){
				$id = $ids[$i];
				$posid =$posids[$id];
				if($posid==1 or $posid==3 or $posid==5 or $posid==7){
					$num =Db::name('news')->where('id',$id)->update(['posids'=>($posid-1)]);
					if($num <=0){
						$this->error("移动失败");
					}
				}
			}
			$this->success('批量取消推荐到首页成功');
 		}

 	}

 	/*
 	*批量推荐到栏目
 	**/
 	public function iscolumn(){
 		$set = $_POST['iscolumn'];//当set等于1时，所选的id都推荐到首页，当set等于0时，所选的id都取消推荐到首页
 		$ids = $_POST['id']; //被选中的所有id
 		$posids =$_POST['posids']; //id和推荐位值对应的数组

 	//通过id可以在posids对应的值得到推荐位。然后对推荐位的值进行判断如果没有推荐的修改数据进行推荐
 		if($set==1){
 			for($i=0;$i<count($ids);$i++){
				$id = $ids[$i];
				$posid =$posids[$id];
				if($posid ==0 or $posid==1 or $posid==4 or $posid==5){
					$num = Db::name('news')->where('id',$id)->update(['posids'=>($posid+2)]);
					if($num <=0){
						$this->error("移动失败");
					}
				}
			}
			$this->success('批量推荐到栏目成功');

 		}elseif($set==2){
 			for($i=0;$i<count($ids);$i++){
				$id = $ids[$i];
				$posid =$posids[$id];
				if($posid==2 or $posid==3 or $posid==6 or $posid==7){
					$num =Db::name('news')->where('id',$id)->update(['posids'=>($posid-2)]);
					if($num <=0){
						$this->error("移动失败");
					}
				}
			}
			$this->success('批量取消推荐到栏目成功');
 		}
 	}

  	/*
 	*批量推荐到其他
 	**/
 	public function isother(){
 		$set = $_POST['isother'];//当set等于1时，所选的id都推荐到首页，当set等于0时，所选的id都取消推荐到首页
 		$ids = $_POST['id']; //被选中的所有id
 		$posids =$_POST['posids']; //id和推荐位值对应的数组

 	//通过id可以在posids对应的值得到推荐位。然后对推荐位的值进行判断如果没有推荐的修改数据进行推荐
 		if($set==1){
 			for($i=0;$i<count($ids);$i++){
				$id = $ids[$i];
				$posid =$posids[$id];
				if($posid ==0 or $posid==1 or $posid==2 or $posid==3){
					$num = Db::name('news')->where('id',$id)->update(['posids'=>($posid+4)]);
					if($num <=0){
						$this->error("移动失败");
					}
				}
			}
			$this->success('批量推荐到其他成功');

 		}elseif($set==2){
 			for($i=0;$i<count($ids);$i++){
				$id = $ids[$i];
				$posid =$posids[$id];
				if($posid==4 or $posid==5 or $posid==6 or $posid==7){
					$num =Db::name('news')->where('id',$id)->update(['posids'=>($posid-4)]);
					if($num <=0){
						$this->error("移动失败");
					}
				}
			}
			$this->success('批量取消推荐到其他成功');
 		}
 	}

 	/*
 	*文章列表筛选
 	**/
 	public function setchange(){

 		$shome = input('param.shome'); //是否首页值
		$scolumn = input('param.scolumn'); //是否栏目值
		$sother = input('param.sother');   //是否其他值
		$cid = input('param.cid');  //栏目id
		$adpage = $this->adpage;//分页时每页的条数
		
		//推荐位是首页
		if($shome==1){

			$list = Db::name('news')->where('posids',1)->whereor('posids',3)->whereor('posids',5)->whereor('posids',7)->order('posids desc,sort desc,id desc')->paginate($adpage,false,['query' =>['shome'=>$shome] ])->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   	break;
 			}

 			return $item;

 		});

		//推荐位不是首页		
	}elseif($shome==2){

			$list = Db::name('news')->where('posids',0)->whereor('posids',2)->whereor('posids',4)->whereor('posids',6)->order('posids desc,sort desc,id desc')->paginate($adpage,false,['query' =>['shome'=>$shome] ])->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   	break;
 			}

 			return $item;

 		});

		//推荐位是栏目
	}elseif($scolumn==1){

			$list = Db::name('news')->where('posids',2)->whereor('posids',3)->whereor('posids',6)->whereor('posids',7)->order('posids desc,sort desc,id desc')->paginate($adpage,false,['query' =>['shome'=>$shome] ])->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   	break;
 			}

 			return $item;

 		});

		//推荐位不是栏目
	}elseif($scolumn==2){

			$list = Db::name('news')->where('posids',0)->whereor('posids',1)->whereor('posids',4)->whereor('posids',5)->order('posids desc,sort desc,id desc')->paginate($adpage,false,['query' =>['shome'=>$shome] ])->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   	break;
 			}

 			return $item;

 		});

		//推荐位是其他
	}elseif($sother==1){

			$list = Db::name('news')->where('posids',4)->whereor('posids',5)->whereor('posids',6)->whereor('posids',7)->order('posids desc,sort desc,id desc')->paginate($adpage,false,['query' =>['shome'=>$shome] ])->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   	break;
 			}

 			return $item;

 		});

		//推荐位不是其他	
	}elseif($sother==2){

			$list = Db::name('news')->where('posids',0)->whereor('posids',1)->whereor('posids',2)->whereor('posids',3)->order('posids desc,sort desc,id desc')->paginate($adpage,false,['query' =>['shome'=>$shome] ])->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   	break;
 			}

 			return $item;

 		});

		//栏目id有值时
	}elseif($cid>0){

			$list = Db::name('news')->where('catid',$cid)->order('posids desc,sort desc,id desc')->paginate($adpage,false,['query' =>['shome'=>$shome] ])->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catname']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   break;
 			}

 			return $item;

 		});


	}



		$info=Db::name('column')->where('is_navigation',1)->select();
 
 		$page = $list->render();

 		$this->assign('info',$info); //栏目

 		$this->assign('list',$list); //文章
 		$this->assign('page', $page);//分页

 		return $this->fetch('news/index');

		
 } 

 /*
 *根据提交的关键词匹配标题匹配
 */

 public function search(){
 	$keywords = $_POST['keywords'];

	$list = Db::name('news')->where('title','like','%'.$keywords.'%')->order('sort desc')->paginate(5)->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catid']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   break;
 			}

 			return $item;

 		});

	 		$data = Db::name('column')->select();
 		foreach($data as $k=>$v){
 			$set_id = $v['id'];
 			$num = Db::name('column')->where('pid',$set_id)->find();
 			if(empty($num)){
 				$info[] =$v;
 			}
 		}
 		$page = $list->render();

 		$this->assign('info',$info); //栏目

 		$this->assign('list',$list); //文章
 		$this->assign('page', $page);//分页

 		return $this->fetch('news/index');


 }

/*
*草稿箱
*在编辑文章的时候每隔多久自动保存到草稿箱
*在文章发布时将文章的状态改成发布。
*/
 public function draft_box(){

 		$adpage =$this->adpage;//每页多少条数据

 			$list = Db::name('news')->where("status",1)->order('sort desc')->paginate($adpage)->each(function($item, $key){
 			$id = $item['catid'];
 			$column_id = Db::name('column')->where('id',$id)->find();
 			$item['catid']=$column_id['catname'];
 			$item['cateUrl'] = $column_id['url'];
 			$item['addtime'] = date('Y-m-d H:s:i',$item['addtime']);

 			switch($item['posids']){
 				case 0: $item['posid'] =0; 					  break;
 				case 1: $item['posid'] = "首页"; 			  break;
 				case 2: $item['posid'] = "栏目"; 			  break;
 				case 4: $item['posid'] = "其他"; 			  break;
 				case 3: $item['posid'] = "首页,栏目"; 	   	  break;
 				case 5: $item['posid'] = "首页,其他";  	      break;
 				case 6: $item['posid'] = "栏目,其他"; 	  	  break;
 				case 7: $item['posid'] = "首页,栏目,其他";   break;
 			}

 			return $item;

 		});



 		$data = Db::name('column')->select();
 		foreach($data as $k=>$v){
 			$set_id = $v['id'];
 			$num = Db::name('column')->where('pid',$set_id)->find();
 			if(empty($num)){
 				$info[] =$v;
 			}
 		}
 		$page = $list->render();


 		$this->assign('info',$info); //栏目

 		$this->assign('list',$list); //文章
 		$this->assign('page', $page);//分页

 		return $this->fetch();

 }

/*
*接收ajax传递过来的from的值
*如果更新数据库成功就返回1，失败就返回0；
*返回值stat为-1时，是保存失败。
**/
 public function ajax_draft(){
 	$data = Request::instance()->post();
 	$title =$data['title'];

 	$num =input('param.num');
 	$data['posids'] =input('param.p');


 	//return ['data'=>$data,'stat'=>200,'id'=>12];

 	if($num==0){
 		 if($title==''){
 		 	return['data'=>'文章标题为空','stat'=>-1];

 		}else{
 			$data['status']=1;
 			if($data['addtime']==''){
 				$data['addtime'] =time();
 			}
 			$data['updatetime']=time();
 			unset($data['newsid']);
 			$setid = Db::name('news')->insert($data);
 			$nid = Db::name('news')->getLastInsID();
 			if($setid=1){
 				return ['data'=>"保存草稿箱成功1",'stat'=>200,'id'=>$nid];
 			}else{
 				return ['data'=>"保存草稿箱失败",'stat'=>-1];
 			}
 		}
 	}else{
 		 if(empty($data['title'])){
 		 	return['data'=>"标题为空,无法保存草稿2",'stat'=>-1];

 		}else{
 			
 			if($data['addtime']==''){
 				$data['addtime'] =time();
 			}else{
 				$data['addtime'] =strtotime($data['addtime']);
 		}
 			$data['updatetime']=time();

 			unset($data['newsid']);

 			$setid = Db::name('news')->where('id',$num)->update($data);
 			$nid = Db::name('news')->getLastInsID();
 			if($setid=1){
 				return ['data'=>"保存草稿箱成功2",'stat'=>200,'id'=>$num];
 			}else{
 				return ['data'=>"保存草稿箱失败",'stat'=>-1];
 			}
 		}
 	}


 	
 }

}
