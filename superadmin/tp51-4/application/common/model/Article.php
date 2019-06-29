<?php  
namespace app\common\model;
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
	public function getNewArticleList($limit=15){
		$where_select=[
			['status','=',0],
		];
		$article_list=$this->where($where_select)->limit($limit)->order('create_time','desc')->paginate(5)
		 ->hidden(['contnet'])
		 ->each(function($item, $key) {
			$item->userInfo;
			$item->categoryInfo;
			$where_select[]=['article_id','=',$item['id']];
			$item['comment_total']=model('Comment')->where($where_select)->count();
		});
		return $article_list;
	}
	// public function getNewArticleList(){
	// 	return 0;
	// }

	public function getClickHightList($limit=6){//点赞最高获取
		$where_select=[
			['status','=',0],
		];
		$order_click=[
			['clicks'=>'desc'],
			['create_time'=>'asc'],
		];//有问题 查询条件失败
		$clicks_list=$this
		->where($where_select)
		->order('clicks','desc')
		->limit($limit)
		->select();
		return $clicks_list;
	}
	public function getArticCategoryList($category_id,$page=5){
	 // 依赖导航分类查询文章
		$where_select=[
			'status'=>0,
		];
		if($category_id!=0){$where_select['category_id']=$category_id;}
		$article_list=$this->where($where_select)->order('create_time','desc')->paginate($page,false,['query'=>['category_id'=>$category_id]])->each(function($item,$key){
				$item->userInfo;
				$item->categoryInfo;
				$where_select[]=['article_id','=',$item['id']];
				$item['comment_total']=model('Comment')->where($where_select)->count();


		});
		return $article_list;
	}
	//上、下一篇文章  加入category_id 根据分类来查询新闻
	public function getLastArticle($id,$category_id){
			$where_select=[
				['status','=',0],
				['id','<',$id],
				['category_id','=',$category_id],
			];
			$article_data=$this->where($where_select)->order('id','desc')->find();
			return $article_data;

	}
	public function getNextArticle($id,$category_id){
			$where_select=[
				['status','=',0],
				['id','>',$id],
				['category_id','=',$category_id],
			];
			$article_data=$this->where($where_select)->order('id','asc')->find();
			return $article_data;

	}
	public function getUserArticList($user_id,$page=10){
		$where_select=[
			['user_id','=',$user_id],
			['status','=',0],
		];
		$article_list=$this->where($where_select)->order('create_time','desc')->paginate($page,false,['query'=>['user_id'=>$user_id]])->each(function($item,$key){
				$item->categoryInfo;
				$where_select[]=['article_id','=',$item['id']];
				$item['comment_total']=model('Comment')->where($where_select)->count();

		});
		return $article_list;
	}
}