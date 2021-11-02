<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class User extends Common
{
	public function index()
	{

		$user=Db::name('admin')->select();

		foreach($user as $k=>$v){
			if($v['lastlogintime']>1){
				$user[$k]['lastlogintime']=date('Y-m-d H:i:s',$v['lastlogintime']);
			}else{
				$user[$k]['lastlogintime']='';
			}

		}

		
		$this->assign('user',$user);
		return $this->fetch();
	}

	public function add()
	{
		$post =$_POST; //获取表单提交的数据

		if(empty($post)){

			return $this->fetch();
		}else{

			if($post['pass'] !=$post['renewpass']){

				$this->error("两次密码不一致，请重新输入");

			}else{
				$name =Db::name('admin')->where('username',$post['name'])->find();
				if(!empty($name)){
					$this->error('用户名已经存在，清重新输入');
				}else{

					$char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$encrypt='';
					for($i=0;$i<4;$i++){
						$encrypt .=substr($char,rand(1,61),1);
					}
					$post['password'] = sha1($post['pass'].$encrypt);

					$data['username'] = $post['name'];
					$data['password'] =	$post['password'];
					$data['catid']	  =	$post['catid'];
					$data['encrypt']  = $encrypt;
					$data['realname'] = $post['realname'];
					$data['num']	  = 0;
					$info = Db::name('admin')->insert($data);
					if(empty($info)){
						$this->error('添加失败，请重试');
					}else{
						$this->success('添加成功',url('User/index'));
					}
				}
			}
		}
	}


	/*用户修改
	*为了安全用户名无法修改
	*需要输入原密码。
	*/
	public function edit()
	{
		$id = input('param.id');
		$data=Db::name('admin')->where('userid',$id)->find();
		$post =$_POST;


		/**当post为空时说明没有提交*/
		if(empty($post)){
			$this->assign('data',$data);
			return $this->fetch();	
		}else{

			$pass=sha1($post['mpass'].$data['encrypt']);
			if($pass=$data['password']){
				$id=$post['id'];
				$info['password'] =sha1($post['newpass'].$data['encrypt']);
				$info['catid']	=	$post['catid'];
				$info['realname'] = $post['realname'];
				$a =Db::name('admin')->where('userid',$id)->update($info);
				if($a>0){
					$this->success('修改成功');
				}else{
					$this->error('修改失败');
				}

			}else{
				
				$this->error('提交失败，请重试');
			}

			
		}
	

	}

	public function delete()
	{
		$id = input('param.id');

		$info = Db::name('admin')->where('userid',$id)->delete();

		if(empty($info))
		{
			$this->error('删除失败，请重试');
		}else{
			$this->success('删除成功',url('User/index'));
		}

		echo "删除id:".$id;
	}


}