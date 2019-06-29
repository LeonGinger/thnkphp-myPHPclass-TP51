<?php  
namespace app\superadmin\controller;
use app\common\controller\UserController;
use think\Model;
use think\Controller;
use think\Db;
use think\Request;
/**
 * 
 */
class Article extends UserController
{
	public function __construct(){
			parent::__construct();
		}

	 	public function initialize(){
	 		$this->user=session('user');  
		  $this->assign('nav',request()->action());
		}
	public function article(){   //文章列表方法
			$basic_data=[
				'title'=>'文章管理',
				'nav_left'=>'article',
				'article_list'=>model('Article')->getArticList($this->user['id'],15),

			];

			return $this->fetch('Articlelist',$basic_data);
		}
		
		public function article_add(){  //发布文章方法
			$basic_data=[
				'title'=>'修改文章',
				'options'=>'article',
			];
			$this->assign('options','');
			return $this->fetch('Articleedit');

		}

		public function article_edit(){
		$basic_data=[
				'title'=>'修改文章',
				
				'options'=>'article',
			];
			$this->assign('options','');
			// return $this->fetch('Articleedit',$basic_data);
		$post=$this->request->param('id');
 		if($post==NULL){
 		return $this->redirect('Categorylist');	
 		}else{
 			$post=(int)$post;
 			// var_dump($post);
 			
 			// var_dump($data_d);
 			$data=[
 				'data'=>model('Article')->where('id',$post)->select(),

 			];
 
 			return $this->fetch('Articleedit',$data);
 			}
 			return $this->error("数据有误");

}		
		public function article_save(){
		$id=$this->request->param('id');

		if($id==NULL){
			$data=$this->request->post();
			$data['user_id']=$this->user->id;
			$data['category_id']=(int)$data['category_id'];
	
 			$validata=$this->validate($data,'app\common\validate\Article.add');

 			if(is_string($validata)){return $this->error($validata);}

 			$res=model('Article')->add($data);

 			if($res==0){return $this->redirect('article');}
 			return $this->error('文章发表失败，数据有误。');

		}else{
			$id=(int)$id;
			$data=$this->request->post();
			$data['category_id']=(int)$data['category_id'];
			$data['id']=(int)$data['id'];
			$data['user_id']=$this->user->id;
			$validata=$this->validate($data,'app\common\validate\Article.update');

			if(is_string($validata)){return $this->error($validata);}

			$res=model('Article')->where('id',$id)->update($data);
	
 			if($res==1){return $this->redirect('article');}

 			return $this->error('文章更新失败，数据有误。');


		}

		}

		public function article_del(){
			$article_id=$this->request->param('id','');
			if(empty($article_id)) return $this->fetch('article');
			$where=['id'=>$article_id,'user_id'=>$this->user['id']];
			$article=model('Article')->where($where)->find();
			if (empty($article)) return $this->error('文章不存在');
			model('Article')->where($where)->delete();
			return redirect('article');
		}
		public function status(){
		$post=intval(input('id'));

		$status1=model('Article')->where('id',$post)->value('status');

		$status1==0?list($info,$text)=['启用成功','已启用']:list($info,$text)=['禁用成功','已禁用'];
		$status1=($status1+1)%2;

		$update=model('Article')->where('id',$post)->update(['status'=>$status1]);

				$data=[
			'info'=>$info,
			'status'=>$status1,
			'text'=>$text,
		];
		return json_encode($data);
	}

}