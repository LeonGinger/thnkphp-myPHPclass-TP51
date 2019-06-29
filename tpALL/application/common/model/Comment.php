<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class Comment extends Model{
	public function userInfo(){
		return $this->hasOne('User','id','user_id')->joinType('left')->field('nickname,username');
	}	
	public function getArticleComment($id){//get文章评论
		$where_select=[
			['status','=',0],
			['article_id','=',$id],
		];
		$article_data=$this->field('id,article_id,user_id,content,create_time')->where($where_select)->order('create_time','desc')->select()->each(function($item,$key){
					$item->userInfo;
		});
		return $article_data;
	}
	public function addComment($insert){
		$this->startTrans();
		$save=$this->save($insert);
		if(!$save){$this->rollback();return 1;}
		$this->commit();
		return 0;
	}
}