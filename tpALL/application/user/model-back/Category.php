<?php 
namespace app\user\model;
use think\Model;

/**
 * 
 */
class Category extends Model
{
	public function getCategoryListAll(){
			$order_list=[
				'sort'=>'desc',
				'create_time'=>'asc',
			];
			$list=$this->field('id,title')->where(['status'=>0])->order($order_list)->select();
			return $list;
	}
	}