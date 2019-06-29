<?php
namespace app\superadmin\controller;
use think\Controller;
use think\request;
 /**
  * 
  */
 class Index extends Controller
 {
 	public function initialize(){

		  $this->assign('nav',request()->action());
		}
 	public function index(){
 	 	return $this->fetch('');
 	}


	 }