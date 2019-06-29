<?php
namespace app\common\validate;
use think\Validate;

class User extends Validate{
	protected $rule=[
		'id'=>'require|number',
		'password'=>'require|max:255',
		'username'=>'require|max:20',
		'nickname'=>'require|max:255',
		'email'=>'require|max:250',
		
	];

	protected $message=[
		'id.require'=>'必须',
		'username.require'=>'必须',
		'username.max'=>'不能超过25个字符',
		'email.max'=>'不能超过25个字符',

	];

	protected $scene=[
		'add'=>['username','password','nickname','email'],
		'edit'=>['id','username','password','nickname','email'],
		'del'=>['id'],
		'status'=>['id'],
	];
}