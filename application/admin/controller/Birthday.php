<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Birthday extends Gongnong
{
	public function index()
	{

		$user = Db::name('birthday') -> order('day asc') -> select();
		$this -> assign('user', $user);
		return $this -> fetch();
	}

	public function add()
	{
		$post = $_POST; //获取表单提交的数据

		if(empty($post)) {
			return $this -> fetch();
		} else {
			$data['name'] = $post['name']; //姓名
			$data['cid'] = $post['cid']; //分类 0为阴历1为阳历
			$data['yue'] = $post['nmonth']; //阴历
			$data['ri'] = $post['nday'];//阳历

			/*
			*为了方便排序统一都转成阴历
			 */
			if($data['cid'] == 0) {
				if($post['nday'] < 10) {
				$post['nday'] = '0'.$post['nday'];
				}
				$date = $post['nmonth'].$post['nday'];
			}else{
				$rest = $this -> convertSolarToLunar('2019', $post['nmonth'], $post['nday']);
				$date = $rest['Month'].$rest['day'];
			}

			 $data['day'] = (int)$date;


			$info = Db::name('birthday') -> insert($data);

			if(empty($info)){
				$this -> error('添加失败，请重试');
			}else{
				$this -> success('添加成功', url('Birthday/index'));
			}

		}
	}


	/*
	*修改
	 */
	public function edit()
	{
		$id = input('param.id');
		$data=Db::name('birthday')->where('id',$id)->find();
		$post =$_POST;

		/**当post为空时说明没有提交*/
		if(empty($post)){
			$this->assign('data',$data);
			return $this->fetch();	
		} else {
			$id = $post['id'];
			$data['name'] = $post['name']; //姓名
			$data['cid'] = $post['cid']; //分类 0为阴历1为阳历
			$data['yue'] = $post['nmonth']; //阴历
			$data['ri'] = $post['nday'];//阳历
			/*
			*为了方便排序统一都转成阴历
			 */
			if($data['cid'] == 0){
				$date = $post['nmonth'].$post['nday'];
			} else {
				$rest = $this->convertSolarToLunar('2019', $post['nmonth'], $post['nday']);
				$date = $rest['Month'].$rest['day'];
			}
			$data['day'] = (int)$date;
			$a = Db::name('birthday') -> where('id',$id) -> update($data);
		
			if($a > 0) {
				$this -> success('修改成功');
			} else {
				$this -> error('修改失败');
			}	
		}
	}

	public function delete()
	{
		$id = input('param.id');
		$info = Db::name('birthday') -> where('id',$id) -> delete();

		if(empty($info))
		{
			$this -> error('删除失败，请重试');
		} else {
			$this -> success('删除成功', url('birthday/index'));
		}
		echo "删除id:".$id;
	}
}