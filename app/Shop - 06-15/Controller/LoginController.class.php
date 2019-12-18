<?php
// +----------------------------------------------------------------------
// | 功能：登陆退出后台
// +----------------------------------------------------------------------
namespace Shop\Controller;
//use Common\Controller\ShopAuthController;
 use Common\Controller\CommonController;
use Think\Verify;
class LoginController extends CommonController {
	//登入页面
	public function login(){
		//已登录,跳转到首页
		if(session('ma_id')){
			$this->redirect('Shop/Sys/profile');
		}
		$this->display();
	}

	//登陆验证
	public function runlogin(){
		if (!IS_AJAX){
			$this->error("提交方式错误！",U('Shop/Login/login'),0);
		}else{
			$admin_username=I('admin_username');
			$password=I('admin_pwd');
			$verify =new Verify ();
			if (!$verify->check(I('verify'), 'ma_id')) {
				$this->error('验证码错误',U('Shop/Login/login'),0);
			}
			$admin=M('merchant')->where(array('ma_account'=>$admin_username))->find();
			if (!$admin||encrypt_password($password,$admin['ma_pwd_salt'])!==$admin['ma_pwd']){
				$this->error('用户名或者密码错误，重新输入',U('Login/login'),0);
			}
			if($admin['is_open'] == 2 ){
				$this->error('账号被冻结',U('Login/login'),0);
			}
            if($admin['due_deadline'] < time() ){
                $this->error('账号已过期',U('Login/login'),0);
            }
            if($admin['delete'] == 2 ){
                $this->error('账号已删除',U('Login/login'),0);
            }
            session('ma_id',$admin['ma_id']);
            session('ma_account',$admin['ma_account']);
            $cha = $admin['due_deadline'] - time();
            $day = $cha/86400;
            if($day < 30){
                $this->success('注意：商家账号快到期!',U('Shop/Sys/profile'),1);
            }else{
                $this->success('恭喜您，登录成功',U('Shop/Sys/profile'),1);
            }
		}
	}
	//验证码
	public function verify()
    {
        if (session('ma_id')) {
            header('Location: ' . U('Shop/Index/index'));
            return;
        }
		ob_end_clean();
        $verify = new Verify (array(
            'fontSize' => 20,
            'imageH' => 42,
            'imageW' => 250,
            'length' => 5,
            'useCurve' => false,
        ));
        $verify->entry('ma_id');
    }
	/*
     * 退出登录
     */
	public function logout(){
		session('ma_id',null);
		$this->redirect('Shop/Login/login');
	}
}