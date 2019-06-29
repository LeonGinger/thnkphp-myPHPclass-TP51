<?php  
namespace app\user\model;
use think\Model;
/**
 * 
 */
class Article extends Model
{
	
	// function __construct(argument)
	// {
	// 	# code...
	// }x-er
	public function userinfo(){
	 	return $this->hasOne("User","id","user_id")->joinType('left')->field('nickname,username');
	}//关联会员表 
	public function categoryInfo(){
		return $this->hasOne("Category","id","category_id")->joinType('left')->field('title');
	}
	public function getArticList($user_id,$page=10){
				$where_list=[
			['user_id','=',$user_id],
		];
		$list=$this->where(['user_id'=>$user_id])->order('create_time','desc')->paginate($page,false,['query'=>['user_id'=>$user_id]])->each(function($item,$key){
				$item->userInfo;
				$item->categoryInfo;
				$where_list[]=['article_id','=',$item['id']];
				$item['comment_total']=model('Comment')->where(['article_id'=>$item['id']])->count();
		});
// var_dump($list);
	 return $list;
	}



	public function add($data){ //添加数据
	 		/*$res=$this->save($data);
	 		return $res;*/
	 		$this->startTrans();
	 		try{
	 			$this->save($data); 
	 			$this->commit();  //OK
	 			return 0;
	 		}catch(\Exception $e){
	 			$this->rollback();   //False
	 			return 1;
	 		}

	 	}
	 public function UpdateDate($data){//更新数据
		$this->startTrans();
		try{
			$this->where('id',$data['id'])->update($data);
			$this->commit();
		}catch(\Exception $e){
			$this->rollback();
			return 1;  //FALSE

		}
		return 0; //OK
	}
}