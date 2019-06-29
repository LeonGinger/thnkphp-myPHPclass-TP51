<?php

	namespace app\index\model;
	use think\Model;


	class WebsiteLink extends Model{
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

	}