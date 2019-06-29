<?php
namespace app\common\validate;
use think\Validate;

class WebsiteLink extends Validate{
	protected $rule=[
		'id'=>'require|number',
		'website_name'=>'require|max:20',
		'website_logo'=>'max:255',
		'href'=>'max:255',
		
	];

	protected $message=[
		'id.require'=>'必须',
		'website_name.require'=>'必须',
		'website_logo.max'=>'不能超过25个字符',
		'href.max'=>'不能超过25个字符',

	];

	protected $scene=[
		'add'=>['website_name','website_logo'],
		'edit'=>['id','website_name','website_logo'],
		'del'=>['id'],
		'status'=>['id'],
	];
}