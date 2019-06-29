<?php
	namespace app\link\controller;
	use think\Controller;
	use  think\Request;
 
 class Websitelink extends controller{

 			public function initialize(){

		  $this->assign('nav',request()->action());
		}
 		public function index(){
 			return $this->fetch('add');
 		}
 			public function add(){
 					return $this->fetch();
 		}
 		
	
	public function save(){
		if($this->request->isPost()){
			$data=input('');

			$result=$this->validate($data,'app\link\validate\WebsiteLink.add');				

		if($result!==true){
		$this->error('数据有误');

		}
		$res=model('Websitelink')->add($data);
		if($res==0){
				// return  $this->fetch('list');
			$this->redirect('list');
			}else{
				$this->error('数据有误');
			}
	}
 }
 	public function list(){
 		$data=model('Websitelink')->webitelist();
 		$this->assign('data',$data);
 		return $this->fetch();
 


 	}
 }
