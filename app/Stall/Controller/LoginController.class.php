<?php
// +----------------------------------------------------------------------
// | 功能:登录
// +----------------------------------------------------------------------
// | 作者:裴国朝
// +----------------------------------------------------------------------
// | 时间:2019.1.18
// +----------------------------------------------------------------------
namespace Stall\Controller;
use Think\Controller;
use Org\Util\Stringnew;
class LoginController extends Controller
{
    // 登录
    public function index()
    {
        $this->display();
    }
    //登录
    public function login()
    {
        $st_account = I("account");
        $st_pwd = I("pwd");
        $info = M("stall")->where(array('delete'=>2,'st_account'=>$st_account))->find();
        if(empty($info)){
            $this->error("账号不存在！",1);
        }elseif($info['st_pwd'] != encrypt_password($st_pwd,$info['ma_pwd_salt'])) {
            $this->error("密码错误！",2);
        }else{
            session("stall_id",$info['stall_id'],time()+86400*30);
            session("ma_id",$info['ma_id'],time()+86400*30);
            //$token = encrypts(time());
            //session("token",encrypts($token),time()+86400*30);
            //M('staff')->where(array('s_id'=>$info['s_id']))->setField('token',$token);
            $data['stall_type'] = $info['stall_type'];
            $this->success("登录成功",$data);
        }
    }

    
    public function error()
    {
    	$this->display();
    }

    
}