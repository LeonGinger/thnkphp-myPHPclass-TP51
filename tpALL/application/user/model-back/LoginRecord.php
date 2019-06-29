<?php  
 namespace app\user\model;
 use think\Model;

 /**
  * 
  */
 class LoginRecord extends Model
 {
 	
 	// function __construct(argument)
 	// {
 	// 	# code...
 	// }
 	public function getUserLoginList($user_id,$page=5){
 		$list=$this->where(['user_id'=>$user_id])
 				   // ->order(['create_time','desc'])
 				   ->paginate($page);
return $list;
 	}

 	public function getLastTime($user_id){
 		$record=$this->where(['user_id'=>$user_id,'type'=>1])
 					 ->order(['create_time'=>'desc'])
 					 ->limit(2)
 					 ->select();
 		if(isset($record[1])) return $record[1]['create_time'];
 		return '';
 	}
 }