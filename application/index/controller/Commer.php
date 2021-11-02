<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Commer extends Controller
{
	public function __construct(){
		parent::__construct();
		$this->fircolumn = Db::name('column')->where('pid',0)->select();
		$this->webset = Db::name('webset')->where('id',1)->find();
	}

}

