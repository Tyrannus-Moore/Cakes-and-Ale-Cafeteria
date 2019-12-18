<?php
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
class IndexController extends ShopAuthController {
	public function index(){
		//未登录
		if (empty($_SESSION['ma_id'])){
			$this->redirect('Shop/Login/login');
		}
		//渲染模板
		$this->display();
	}
}