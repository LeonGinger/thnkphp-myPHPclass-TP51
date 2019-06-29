<?php  
namespace app\superadmin\model;
use think\Model;
	/**
	 * 
	 */
	class User extends Model
	{
		
		public function login($username,$password){
			//login 验证
			$where=[
				['username','=',$username],
				// ['password','=',$password],
				['status','=',0],
			];
			$login=$this->where('username',$username)->find();

			if(empty($login)) return ['code'=>1,'msg'=>'用户不存在'];
			if($login['password']!=password($password)) return ['code'=>1,'msg'=>'密码不正确'];
			unset($login['password']);  //unset（）销毁函数变量
			return ['code'=>0,'msg'=>'登录中，稍等......','user'=>$login];

		}
		public function saveUser($data){
				//添加用户
			// $this->startTans();
			// $insert=$this->save($data);
			// if(!$insert){
			// 	$this->rollback();
			// 	return 1;//FAiLED
			// }
			// $this->commit();
			// return 0;//OK
			// 
			 // $this->save($data);
			$this->startTrans();
			$save=$this->save($data);
			if(!$save){
				$this->rollback();
				return 1;
			}
			$this->commit();
			return 0;
		}

/**/
/**/
		public  function getUserID($id=-1){  //获取网站编辑信息？？？
			$where=[
				['id','=',$id],
				['status','=',0],

			];
			return $this->field('username,nickname,head_img,email')->where($where)->find();
		}
		public function  getUserinfo($U_ID){   //获取用户信息
				$info=$this->where(['id'=>$U_ID,'status'=>0])->find();
				return $info;
		}
	 //获取数据
	 	public function getuserlist(){
	 		//$list=$this->select();
	 		$list=$this->field('*')
	 				  // ->where(['status'=>0])
	 				   //->limit(3)
	 				   // ->order(['sort'=>'asc','create_time'=>'desc',])
	 				   //->select();
	 				   ->paginate(5);
	 		return $list;
	 	}
		public function updateUser($Udata){
			//更新用户信息
		$this->startTrans();
		try{
			$this->where(['id',$Udata['id']])->update($Udata);
			$this->commit();
		}catch(\Exception $e){
			$this->rollback();
			return 1;  //if false back info

		}
		$user=$this->where(['id'=>$Udata['id']])->find();
		unset($user['password']);
		$user['login_at']=time();
		session('user',$user);
		return 0; //OK
		}

	}