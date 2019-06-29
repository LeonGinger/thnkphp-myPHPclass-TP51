<?php  
namespace app\common\controller;
use think\Controller;
use think\Db;
	/**
	 * 
	 */
	class UserController extends Controller
	{
		protected $is_login=false; //is login user true/def=false
		protected $user=[]; //userinfo

		// function __construct(argument)
		// {
		// 	# code...
		// }
		public function __construct(){
			parent::__construct();
			// $this->user=session('user');
			// if(!empty($this->user)){
			// 	$this->checkLoginOver($this->user);
			// }
			// $this->checkLogin();
			//传栏目参数
			$Category_list=model('Category')->getnav();
			$this->assign('Category_list',$Category_list);
			
		}
		public function checkLogin(){
			if(empty(session('user.id'))){
				$this->error('未登录',url('@user/login'));
			}
			$this->is_login=true;
		}
		public function checkLoginOver($user){
			$LoginDuration=3600;
			if(!empty($LoginDuration) &&  isset($user['login_at'])){
				if(time()-$user['login_at']>=$LoginDuration){
					$this->LoginRecord(3);
					$this->user=[];
					session(null);
				}
			}
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

	}