<?php
//define('APP_DEBUG', TRUE);
	namespace app\index\controller;
	use think\Controller;
	use  think\Request;
	/**
	 * 
	 */
	class Category extends Controller
	{	public function test2(){
		//2019年4月1日09:48:32
		// $category=model('Category')->get(1);
		// echo $category->title."<br>";
		// $category1=model('Category')->where('id>1 and id<3');
		// echo $category1->title."<br>";
		// 
		echo input('get.');
		//echo input('param.id');
		// echo Request::route('value');
		
	}


		public function initialize(){

		  $this->assign('nav',request()->action());
		}
		public function Ttest(){
			//数组输出demo
			$val='Ttest OK';
//			return $this->fetch('',['data'=>$val]);

			$arr=[
				'name'=>'老王',
				'sex'=>'男',
				'yearold'=>'35',
			];
		$this->assign('data',$arr);
		return $this->fetch();
		}



		public function save(){
		if($this->request->isPost()){
			$post=input('');
			// $data=[
			// 	'title'=>$post['title'],
			// 	'remark'=>$post['remark'],
			// ];
			// data可不用
			//return var_dump($data);
			//$result=model('Category')->add($data);
			$result=$this->validate($post,'app\index\validate\Category.add');
			//!$result?$this->error('添加数据失败,数据有误'):'';
			if($result!==true){$this->error('添加数据失败,数据有误');}
			$res=model('Category')->add($post);
			if($res==0){
				//$this->success('OK',url('edit'));
				return redirect('index');
			}else{
				$this->error('false');
			}
}
		}

			public function index(){
					//return 'index';
		    header("Content-Type: application/json");
		    header("Access-Control-Allow-Origin: *");
			$module = request()->module();
			$this->assign('mod',$module);
			$list=model('Category')->getlist();
			$listnav=model('Category')->getnav();	
		    $data=json_encode($listnav);
		    // $this->assign('jsdata',$data);
		    file_put_contents('test.json', $data);
		    $nav=$this->request->param('nav');
		    if($nav=='left'){echo $data; return $data;}
		//$this->assign('data',$list);
			//$a=1;
			//$data=['data'=>'AA'];
	$this->assign('data',$list);

		return $this->fetch();
		//dump($list);
		}
		
		public  function test(){
		$data=['title'=>'测试成功',];
		//测试数据添加数据库
		return model('Category')->add($data);
	}

		public function Hello(){
		return 'Hello world';
	}
	public function json(){
		$data=['name'=>'thinkphp','status'=>1];
		return json($data);
	}
	public function add(){
		return $this->fetch();
	}
	public function save1(){
		if($this->request->isPost()){
			/*$post=input('post.');
		//方法1、
			$data=[
				'title'=>$post['title'],//标题
				'remark'=>$post['remark'],//备注
			];
*/			//方法2、
			$data=Request::instance()->param();
			//var_dump($data);
			 $result=model('Category')->add($data);
		}
		//return $this->fetch();
		if($result==0){
		$this->assign('name',$result);
		$this->display();
		return $this->fetch();
		}else{
			$this->error('false');
		}
	}
	public function edit($id=0){
		$data=model('Category')->get($id);
		if(is_null($data)){
			$this->error('非法参数',url('index'));
		}
		return $this->fetch('',[
			'data'=>$data,
			'nav'=>'update',
			'title'=>'更新数据',
		]);
	}
	public function detail(){
		$id=input('param.id');
		$result=model('Category')->get($id);
	 	$this->assign('data',$result);
	 	return $this->fetch();
	// return $this->fetch();
	}

	public function del(){
		//删除数据
		$id=input('param.id');
		$result=model('Category')->get($id);
		$del=$result->delete();
		if($del){return redirect('index');}
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

	public function update(){
		if(!$this->request->isPost()){
			$this->error('非法提交！');
		}
		//查找需修改的
		$data=model('Category')->where('id',$this->request->param('id'))->find();
		 if(empty($data)) return $this->error('无数据');
		
		//验证
		$post=$this->request->post();
		$validate=$this->validate($post,'app\index\validate\Category.edit');
		if(true!==$validate) return $this->error($validate);
		//save 数据
		$res=model('Category')->UpdateDate($post);
		// var_dump($res);
		if($res==0){
			$this->success('修改完成',url('category/detail',['id'=>$post['id']]));
		}
		$this->error('修改失败',url('category/index'));
	}
	

	public function showlist(){

	}
	public function  jsondata(){
		//json数据
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json");
		$module = request()->module();
		$this->assign('mod',$module);
		$list=model('Category')->getnav();	
	    $data=json($list);
		  return $data;
		  file_put_contents('test.json', $data);
		}
}
	
