<?php
namespace app\user\controller;
use think\Controller;
use think\facade\Session;

class Login extends Controller{
	public function index(){
		// echo THINK_VERSION;
		// cookie('name','123');

		return $this->fetch();
	}
	public function __construct(){
		parent::__construct();
		$this->model=model('User');
		$action=$this->request->action();
		if(!empty(session('user.id')) && $action!=='out')
			return $this->redirect('@index/index');
	}
	public function login(){
		$post=input('');
		$validate=$this->validate($post,'app\user\validate\Login.index');
		if(true!==$validate) return $this->error($validate);
		$login=model('User')->login($post['username'],$post['password']);//$username,$password
		if($login['code']==1) return $this->error($login['msg']);
		$login['user']['login_at']=time();
		session('user',$login['user']);
		 $this->LoginRecord(1);   //方法未写--0429完成OK
		// return $this->fetch('@user/user');
	return $this->redirect("superadmin/index/index");
	// ,array("status"=>"100")
// success($login['msg'])
	}
	public function register(){
		return $this->fetch();
	}
	public function regUser(){ 
		$post=input('');
		$validate=$this->validate($post,'app\user\validate\Check.Register');
		$user=model('User')->where('username',$post['username'])->find();
if(!empty($user)) return $this->error('用户存在。');
		 // var_dump($validate);
		 // echo $post['username'];
		// var_dump($validate);
		 // exit;
		if(true!==$validate) return $this->error($validate);
		$post['password']=password($post['password']);
		unset($post['vercode']);  //数组中vercode   表中没有这个字段 删除在保存数据
		$res=model('User')->saveUser($post);
		if($res!==0) $this->fetch('index');
 		// var_dump($post['password']);
		// 	exit;
		// $login=model('User')->login($post['username'],$post['password']);
		// if($login['code']==1) return $this->error($login['msg']);
		$login['user']['login_at']=time();
		session('user',$login['user']);
		 $this->LoginRecord(1); 
		 return $this->success('注册成功,正在跳转到后台编辑',url('index'));
		 // return $this->fetch('index');
	}
	public function out(){
		$this->LoginRecord(0);
		session(null);
		return $this->redirect("index/index/index");
	}

	public function LoginRecord($type=1){
		// 0 主动exit  1 user登录   3 过期logout
		switch ($type) {
			case 0:
				# code...
				$type=0;
				$remark='logout...';
				break;
			case 1:
				# code...
				$type=1;
				$remark='loging...';
				break;
			case 3:
				# code...
				$type=0;
				$remark='sessionTimeOut';
				break;
									
			default:
				# code...
				$type=3;
				$remark='sessionTimeOut+May be WARING';
				break;
		}
			if(!empty(session('user.id'))){
				model('LoginRecord')->save([
					'type' =>$type,
					'user_id'=>session('user.id'),
					'ip'=>get_ip(),
					'remark'=>$remark,


				]);

			}
	}














	public function test(){
		session('name','test');
		cookie('name','123');
		return $this->fetch();
	}
	public function testcheck(){
		$data=input('');
		$res=$this->validate($data,'app\user\validate\Login.Register');
		var_dump($res);
		if($res!==true){
			echo "false";
		}
	}

	public function testC(){
				$data=input('');
				$user=model('User')->where('username',$data['username'])->find();
				if(!empty($user)) return '用户存在。';
	}
}