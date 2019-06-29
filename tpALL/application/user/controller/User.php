<?php
namespace app\user\controller;
use app\common\controller\UserController;
use think\Request;


	/**
	 * 
	 */
class User extends UserController
	{
		public function initialize(){

		  $this->assign('nav_left',request()->action());
		}
		public function __construct(){
			parent::__construct();
			$this->init_top();
		}

		public function index(){
			// echo Session::get();
			// $this->user=session('user');
			$basic_data=[
				'title'=>'用户信息',
				'nav_left'=>'index',
			    'user_info'=>model('User')->where(['id'=>$this->user['id'],'status'=>0])->find(),
				'last_time'=>model('LoginRecord')->getLastTime($this->user['id']),
			];
			
			return $this->fetch('',$basic_data);
		}
		public function init_top(){
			if(empty($this->user)) return false;
			$where=[
				'user_id'=>$this->user['id'],
				'status'=>0,

			];
			$this->assign([
				'article_num'=>model('Article')->where($where)->count(),
				'click_num'=>model('Article')->where($where)->sum('clicks'),
				'praise_num'=>model('Article')->where($where)->sum('praise'),

			]);

		}
		public function login_log(){
			$basic_data=[
				'title'=>'登录日记',
				'nav_left'=>'index',
				'login_record'=>model('LoginRecord')->getUserLoginList($this->user['id']),

			];
	
			return $this->fetch('',$basic_data);
		}


		public function article(){   //文章列表方法
			$basic_data=[
				'title'=>'文章管理',
				'nav_left'=>'article',
				'article_list'=>model('Article')->getArticList($this->user['id'],8),

			];
			
			return $this->fetch('',$basic_data);
		}
		
		public function article_add(){  //发布文章方法
			if(!$this->request->isPost()){
			$basic_data=[
				'title'=>'发布文章',
				'nav_left'=>'article_add',
				'category_list'=>model('Category')->getCategoryListAll(),
				'options'=>'article',
			];
			$this->assign('options','');
			return $this->fetch('',$basic_data);
}else{			
			
			$post=$this->request->param('');
			$post['user_id']=$this->user->id;
		 $post['category_id']=(int)$post['category_id'];
			$validate=$this->validate($post,'app\user\validate\Article.add');
			// $validate=Validate('app\user\validate\Article');
			// $res=$validate->scene('add')->check($post);
			 if(is_string($validate)) return $this->error($validate);
			 // var_dump($post);
			 // var_dump($validate);

			 //  exit;
			if(true!=$validate) return $this->error($validate);
			$res=model('Article')->save($post);
			if($res>0){$this->success('OK',url('@user/user/article'));}
			$this->error('FALSE');
//thinkphp 版本原因 语法不能用  validate方法   sceneAdd sceneEdit两个自定义方法不能调用 版本原因
}
		}
		public function article_edit(){
			if(!$this->request->isPost()){
			$article_id=$this->request->param('id','');
			if(empty($article_id)) return $this->fetch('article');
			$where=['id'=>$article_id,'user_id'=>$this->user['id']];
			$article=model('Article')->where($where)->find();
			if (empty($article)) return $this->error('文章不存在');
			$basic_data=[
				'title'=>'修改文章',
				'nav_left'=>'article',
				'category_list'=>model('Category')->getCategoryListAll(),
				'article'=>$article,
				'options'=>'archive',
			];
			// var_dump($article);
			return $this->fetch('article_edit',$basic_data);
	}else{
			$data=$this->request->post();
			$data['user_id']=$this->user->id;
			$data['article_id']=$this->request->param('id');
			$where=['id'=>$data['article_id'],'user_id'=>$this->user['id'],'status'=>0];
			$article=model('Article')->where($where)->find();
			if(empty($article)) return $this->error('文章不存在');

			$validate=$this->validate($data,'app\user\validate\Article.edit');
			if(true!=$validate)  return $this->error($validate);

			$save=$article->save($data);
			if($save>=1){  return redirect('article');}
			$this->error('未知原因,修改失败');

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
		public function testimg(){
			
			  $this->assign('options',request()->action());
			  	if(isset($_POST['action']) && !empty($_POST['action'])) {
	var_dump($_POST['action']);

	    }
	
			return $this->fetch('');
			}
		
				public function form(){
			
			  $this->assign('options',request()->action());
			return $this->fetch('');
		}
		public function debug(){
		// 图片插件调试 2019年5月26日 00:03:08 clone了github上的不依赖thinkPHP框架的php版本的插件  可以了 稍作修改
		// imge-upload.html    ajax的数据是接收到的。  var_dump不到数据？？？？- 不知道接收了那些数据。。。问题不大
		// php配置 php.ini  开启   extension=php_exif.dll      ; Must be after mbstring as it depends on it   
		$action = $_POST['action'];
	    switch($action) {
	        case 'saveImage':
			 return json_decode(1);
	    }
	}

}

		 	// $this->startTrans();
	 		// try{
	 		// 	$this->$this->where('id',$data['id'])->update($data);; 
	 		// 	$this->commit();  //OK
	 		// }catch(\Exception $e){
	 		// 	$this->rollback();   //False
	 		// 	return 1;
	 		// }
	 		// return 0; //OK