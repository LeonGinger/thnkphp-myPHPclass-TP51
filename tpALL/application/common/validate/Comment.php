<?php  
namespace app\common\validate;
use think\Validate;

/**
 * 
 */
class Comment extends Validate
{
	//规则
	protected $rule=[
		'article_id'=>'require|number|checkArticleId',
		'user_id'=>'require|number',
		'content'=>'require|max:1000',
	];
	//error tips
	protected $messafe=[];

	protected $scene=[
		'add_comment'=>['article_id','member_id','content'],
	];
	protected function checkArticleId($value,$rule,$data=[]){
		$where_select=[
			['id','=',$value],
			['status','=',0],
		];
		$article=model('Article')->where($where_select)->find();
		if(empty($article)) return '此文章不存在';
		return true;

	}
}