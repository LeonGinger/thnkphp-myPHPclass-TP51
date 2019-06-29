<?php
namespace app\superadmin\controller;
use think\Controller;
use think\request;


/**
 * 
 */
class User extends Controller
{
	public function initialize(){

		  $this->assign('nav',request()->action());
		}
 	public function Userlist(){
 		$data=model('User')->getuserlist();

 		$this->assign('data',$data);
 		return $this->fetch();
 	}
 	public function Useredit(){
 		$post=$this->request->param('id');
 		if($post==NULL){
 		return $this->redirect('Userlist');	
 		}else{
 			$post=(int)$post;
 			// var_dump($post);
 			$data_d=model('User')->where('id',$post)->select();
 			// var_dump($data_d);
 			$data=[
 				'data'=>$data_d,
 			];
 			return $this->fetch('Useredit',$data);
 			}
 			return $this->error("数据有误");
 		}
 			
 	public function Useradd(){
 		return $this->fetch('Useredit');
 	}
	public function Usersave(){
		$post=$this->request->param('id');
		$res=1;
		if($post==NULL){
			$data=$this->request->post();
			$data['password']=password($data['password']);
 			$validata=$this->validate($data,'app\superadmin\validate\User.add');
 			if(is_string($validata)){return $this->error($validata);}
 			$res=model('User')->saveUser($data);
 			if($res==0){return $this->redirect('Userlist');}
 			return $this->error('数据有误');
		}else{
			$post=(int)$post;
			$data=$this->request->post();
			$validata=$this->validate($data,'app\superadmin\validate\User.edit');
			if(is_string($validata)){return $this->error($validata);}
 			$res=model('User')->where('id',$post)->update($data);
 			
 			
	}
	if($res==0){return $this->success('OK','Userlist');}
}
	public function Userdel(){
		//删除数据
		$id=input('param.id');
		$result=model('User')->get($id);
		$del=$result->delete();
		if($del){return $this->redirect('Userlist');}
	}
	public function status(){
		$post=intval(input('id'));

		$status1=model('User')->where('id',$post)->value('status');

		$status1==0?list($info,$text)=['用户已被封停','已禁用']:list($info,$text)=['用户已被解封','已启用'];
		$status1=($status1+1)%2;

		$update=model('User')->where('id',$post)->update(['status'=>$status1]);

				$data=[
			'info'=>$info,
			'status'=>$status1,
			'text'=>$text,
		];
		return json_encode($data);
	}
}