<?php
namespace app\superadmin\validate;
use think\Validate;

class Notice extends Validate{
	protected $rule=[
		'id'=>'require|number',
		'title'=>'require|max:20',
		'content'=>'max:255',
		'remark'=>'max:255',
	];

	protected $message=[
		'id.require'=>'必须',
		'title.require'=>'必须',
		'remark.max'=>'不能超过25个字符',
		'remark'=>'不能超过255字符',

	];

	protected $scene=[
		'add'=>['title','content'],
		'edit'=>['id','title','remark','content'],
		'del'=>['id'],
		'status'=>['id'],
	];
}