<?php

	namespace app\index\model;
	use think\Model;
	class Category extends Model{
	 	/*protected $table ='blog_category';
	 	protected $pk='ccid';*/
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
	 //获取数据
	 	public function getlist(){
	 		//$list=$this->select();
	 		$list=$this->field('*')
	 				  // ->where(['status'=>0])
	 				   //->limit(3)
	 				   ->order(['sort'=>'asc','create_time'=>'desc',])
	 				   //->select();
	 				   ->paginate(5);
	 		return $list;
	 	}
	 	public function getnav(){
	 		//$list=$this->select();
	 		$list=$this->field('*')
	 				  // ->where(['status'=>0])
	 				   //->limit(3)
	 				   ->order(['sort'=>'desc','create_time'=>'asc',])
	 				   ->select();
	 				   // ->paginate(5);
	 		return $list;
	 	}
	}

