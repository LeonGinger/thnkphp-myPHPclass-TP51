<?php
	namespace app\index\controller;
	use think\Controller;
	use  think\Request;
 
 class WebsiteLink extends controller{
 		public function index(){
 			return $this->fetch('add');
 		}
 			public function add(){
 					return $this->fetch();
 		}
 		
	
	public function save(){
		if($this->request->isPost()){
			$data=input('');
			//var_dump($data);
		//$result=model('WebsiteLink')->add($data);
			$result=$this->validate($data,'app\index\validate\WebsiteLink.add');
		//var_dump("<br>".$result);
		//return $this->fetch();
		if($result!==true){
		$this->error('数据有误');
		//var_dump($data);
			
		//return $this->fetch()."<br>OK";
		}
		$res=model('Website_link')->add($data);
		if($res==0){
				//$this->success('OK',url('edit'));
				return  $this->fetch()."<br>OK";
			}else{
				$this->error('数据有误');
			}
	//return $this->fetch()."<br>OK";
	
	}
 }
 }
		/*public function save(){
		if($this->request->isPost()){
			$post=input('');
			$data=[
				'title'=>$post['title'],
				'remark'=>$post['remark'],
			];
			//return var_dump($data);
			//$result=model('Category')->add($data);
			$result=$this->validate($data,'app\index\validate\Category.add');
			//!$result?$this->error('添加数据失败,数据有误'):'';
			if($result!==true){$this->error('添加数据失败,数据有误');}
			$res=model('Category')->add($data);
			if($res==0){
				//$this->success('OK',url('edit'));
				return redirect('edit');
			}else{
				$this->error('false');
			}
}
		}*/