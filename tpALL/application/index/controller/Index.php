<?php
namespace app\index\controller;
use think\Controller;

 class Index extends Controller{

 	public function initialize(){
 		$this->assign('nav',request()->action());
 		$category_list=model('Category')->getCategoryListAll();
 		$this->assign('category_list',$category_list);
 		$Category_list=model('Category')->getnav();
		$this->assign('Category_list',$Category_list);

 	}
    public function index(){
    	$basic_data=[
    		'title'=>'首页',
    		'recommend_list'=>model('Article')->getClickHightList(),
    		'article_list'=>model('Article')->getNewArticleList(),
    	];
    	return $this->fetch('',$basic_data);
    }
    public function categoryArticle($id=''){
        $category_id=$id;
        if(empty($category_id)||$category_id==0){
            $return_data=['id'=>0,'title'=>'全部分类','create_time'=>''];
        }else{
            $return_data=model('Category')->where(['id'=>$category_id,'status'=>0])->find();
        }

        $basic_data=[
            'title'=>'当前栏目  /  '.$return_data['title'],
            'return_data'=>$return_data,
            'recommend_list'=>model('Article')->getClickHightList(),
            'article_title'=>model('Article')->getArticCategoryList($return_data['id']),
        ];
        return $this->fetch('category',$basic_data);
    }
    public function details(){
        if(!$this->request->isPost()){
            $id=$this->request->param('id');
           
            if(empty($id)) return $this->redirect('@index/index/index');
            $details=model('Article')->where(['status'=>0,'id'=>$id])->find();
      
            if(empty($details)) return $this->redirect('@index/index/index');
             $details['nickname']=$details->userInfo->nickname;

             $details['categoryName']=$details->categoryInfo->title;

             model('Article')->where('id',$id)->setInc('clicks',1);//+1 clicks
            $basic_data=[
                'title'=>$details['title'],
                'details'=>$details,
                'last_article'=>model('Article')->getLastArticle($id,$details['category_id']),
                'next_article'=>model('Article')->getNextArticle($id,$details['category_id']),
                'recommend_list'=>model('Article')->getClickHightList(),
                'article_comment_list'=>model('Comment')->getArticleComment($id),
                            ];
            return $this->fetch('',$basic_data);
    }else{return $this->redirect('@index/index/index');}

    }
    public function author($id=-1){
        $basic_data=[
            'title'=>'作者专栏',
            'article_list'=>model('Article')->getUserArticList($id,5),
            'user_info'=>model('User')->getUserinfo($id),
            'recommend_list'=>model('Article')->getClickHightList(),
        ];
        return $this->fetch('',$basic_data);
    }

    public function test(){
    	$data=model('Article')->getClickHightList();
    	foreach ($data as $key => $value) {
    		# code...
    		echo $value;
    	}
    }
}