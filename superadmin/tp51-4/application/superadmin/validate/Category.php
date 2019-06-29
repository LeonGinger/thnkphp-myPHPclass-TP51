<?php
namespace app\superadmin\validate;
use think\Validate;

class Category extends Validate{
	protected $rule=[
		'id'=>'require|number',
		'title'=>'require|max:20',
		'remark'=>'max:255',
	];

	protected $message=[
		'id.require'=>'必须',
		'title.require'=>'必须',
		'remark.max'=>'不能超过25个字符',
		'remark'=>'不能超过255字符',

	];

	protected $scene=[
		'add'=>['title','remark'],
		'edit'=>['id','title','remark'],
		'del'=>['id'],
		'status'=>['id'],
	];
}