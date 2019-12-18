<?php
/**
 * 功能：权限认证
 * 修改人：张赛
 * 修改时间：2016.6.29
 */
namespace Common\Controller;
use Common\Controller\CommonController;
use Think\Auth;
//权限认证
class ShopAuthController extends CommonController
{
    protected function _initialize(){
        parent::_initialize();
        //未登陆，不允许直接访问
        if(empty($_SESSION['ma_id'])){
            $this->redirect('Shop/Login/login');
        }else{
            $this->assign('controllername',strtolower(CONTROLLER_NAME));
            $this->assign('actionname',strtolower(ACTION_NAME));
        }
    }

}