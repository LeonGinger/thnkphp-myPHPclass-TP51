<?php
namespace app\common\Validate;
use think\Validate;

	class Check extends Validate{
		public function sceneRegister(){
			return $this->only(['username','password','nickname','email','vercode'])
			->append('username','require|checkUsername')
		    ->append('email','require|checkemail');
		}


		protected function checkUsername($value){
				// $where=[
				// 	['username','=',$value],
				// 	['status','=',0],

				// ];	
					$where['username']=$value;
					$where['status']=0;
				$user=model('User')->where($where)->find();
				if(!empty($user)) return '用户存在。';
			
				return true;	
		}
		protected function checkEmail($value,$rule,$date=[]){
			// $where=[
			// 	['email','=',$value],
			// 	['status','=',0],8
			// ];
			$where['email']=$value;
			$where['status']=0;
			$user=model('User')->where($where)->find();
			if(!empty($user)) return '邮箱已注册,请用此邮箱登录更换邮箱注册';
			return true;
		}

		protected $rule=[
			'username'=>'require|max:15|checkUsername',
			'password'=>'require|max:20',
			'nickname'=>'require|max:20',
			'email'=>'require|email|checkEmail',
			'vercode'=>'require|captcha',

		];
		protected $message=[
			'username.requite'=>'必须',

		];
		protected $scene=[
			'index'=>['username','password','vercode'],
	];



		
		
	}