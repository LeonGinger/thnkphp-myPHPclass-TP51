<?php 
namespace app\index\controller;
use think\Controller;
use thikn\Request;

/**
 * 
 */
class Article extends Controller{
	public function add_comment(){



		// if($this->request->isAjax()){
			$data=$this->request->post();
			$data['content']=remove_xss($data['content']);
 			$validate=validate('Comment')->scene('add_comment')->check($data);
			if(true!==$validate){
				return json(['code'=>1,'msg'=>'提交数据有误!']);
			}
			$res=model('Comment')->addComment($data);
			if($res==0){return json(['code'=>0,'msg'=>'评论成功!']);}
		
		return json(['code'=>1,'msg'=>'what?']);
}
	public function testdata(){
		$data=$_POST['Code'];
		var_dump($data);
		$data=json_encode($data);
		return $data;

	}
}