<?php  
namespace app\common\validate;
use think\Validate;

/**
 * 
 */
class Article extends Validate
{
 	protected $rule=[
 	'article_id'=>'require|number|checkArticle',
 	'user_id'=>'require|number|checkUserId',
 	'content'=>'require',
 	'title'=>'require|max:250',
 	'category_id'=>'require|number',
 	'cover_img'=>'max:250',
 	'describe'=>'max:250',

 ];	
	protected $message=[
	
	];
	protected $scene=[
		'add'=>['user_id','title','category_id','content'],
		'update'=>['user_id','title','category_id','content'],
	];
	public function sceneAdd(){
		return $this->only(['user_id','title','category_id','content'])->append('content','max:1000000');
	}//addArticle
	public function sceneEdit(){
		return $this->only(['article_id','user_id','title','category_id','cover_img','describe','content'])->append('content','max:1000000');
	}//changeArticle
	public function sceneTest(){
		return true;
	}
	protected function checkArticle($value,$rule,$data=[]){
		// $where=[
		// 	['id','=',$value],
		// 	['status','=',0],
		// ];
		$where['id']=$value;
		$where['status']=0;
	$article=model('Article')->where($where)->find();
	if(empty($article)) return '无此文章';
	return true;
	}//checkeArticle 
	protected function checkUserId($value,$rule,$data=[]){
		// $where=[
		// 	['id','=',$value],
		// 	['status','=',0],

		// ];
		$where['id']=$value;          
		$where['status']=0;
		$user=model('User')->where($where)->find();
	if(empty($user)) return '无此用户';
	return true;
	}//checkuser
	public function checkContent($value,$rule,$data=[]){
		if(empty($value)||$value='<p><br/></p>') return '内容不能为空';
		return true;
	}//checkContent


}
	

