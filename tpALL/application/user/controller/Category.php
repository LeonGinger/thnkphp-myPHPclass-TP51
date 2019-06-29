<?php 
namespace app\think\controller;
use thnk\Controller;
	/**
	 * 
	 */
	class Category extends Controller
	{
		
		function index(){
			return $this->fetch('');		
		}

	}