<?php
namespace app\superadmin\controller;
use think\Controller;
use think\request;
 /**
  * 
  */
 class Category extends Controller
 {
 	public function initialize(){

		  $this->assign('nav',request()->action());
		}

 // begin Category
 	public function Categorylist(){
 		$data=model('Category')->getlist();
 		$this->assign('data',$data);
 		return $this->fetch();
 	}
 	public function Categoryedit(){
 		$post=$this->request->param('id');
 		if($post==NULL){
 		return $this->redirect('Categorylist');	
 		}else{
 			$post=(int)$post;
 			// var_dump($post);
 			$data_d=model('Category')->where('id',$post)->select();
 			// var_dump($data_d);
 			$data=[
 				'data'=>$data_d,
 			];
 
 			return $this->fetch('Categoryedit',$data);
 			}
 			return $this->error("数据有误");
 		}
 			
 	public function Categoryadd(){
 		return $this->fetch('Categoryedit');
 	}
	public function Categorysave(){
		$post=$this->request->param('id');
		$res=1;
		if($post==NULL){
			$data=$this->request->post();
 			$validata=$this->validate($data,'app\superadmin\validate\Category.add');
 			if(is_string($validata)){return $this->error($validata);}
 			$res=model('Category')->add($data);
 			if($res==0){return $this->redirect('Categorylist');}
 			return $this->error('数据有误');
		}else{
			$post=(int)$post;
			$data=$this->request->post();
			$validata=$this->validate($data,'app\superadmin\validate\Category.edit');
			if(is_string($validata)){return $this->error($validata);}
 			$res=model('Category')->where('id',$post)->update($data);
 			
 			
	}
	if($res==0){return $this->success('OK','Categorylist');}
}
	public function Categorydel(){
		//删除数据
		$id=input('param.id');
		$result=model('Category')->get($id);
		$del=$result->delete();
		if($del){return $this->redirect('Categorylist');}
	}
	public function status(){
		// $data=[
		// 	'info'=>'修改成功',
		// 	'status'=>'1',
		// 	'text'=>'禁用',
		// ];
		// return json_encode($data);
		// var_dump($data);
		$post=intval(input('id'));

		//$validate=$this->validate(12,'app\index\validate\Category.status');
		//var_dump($validate);
		// if(true!==$validate){
		// 		$data=[
		// 	'info'=>'失败',
		// 	'status'=>'0',
		// 	'text'=>'',
		// ];
		// return json_encode($data);
		// }

		$status1=model('Category')->where('id',$post)->value('status');

		
		$status1==0?list($info,$text)=['禁用成功','已禁用']:list($info,$text)=['启用成功','已启用'];
		$status1=($status1+1)%2;

		$update=model('Category')->where('id',$post)->update(['status'=>$status1]);

				$data=[
			'info'=>$info,
			'status'=>$status1,
			'text'=>$text,
		];
		return json_encode($data);
	}

	
 //END
	 }