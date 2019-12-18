<?php
namespace Stall\Controller;

use Think\Controller;
use Stall\Controller\BaseController;
class IndexController extends Controller
{
    //登录
    public function index()
    {
        $ma_id = I('ma_id');
        $cook['phone'] = cookie('phone');
        $cook['pwd'] = cookie('pwd');

        $this->assign('cook',$cook);
        $this->assign('ma_id',$ma_id);
        $this->display();
    }

    //登录
    public function login()
    {
        $ma_id = I('ma_id');
        $st_account = I("account");
        $st_pwd = I("pwd");
        $info = M("stall")->where(array('delete'=>2,'ma_id'=>$ma_id,'st_account'=>$st_account))->find();
        if(empty($info)){
            $this->error("账号不存在！",1);
        }elseif($info['st_pwd'] != encrypt_password($st_pwd,$info['ma_pwd_salt'])) {
            $this->error("密码错误！",2);
        }/*elseif($info['is_freeze'] == 1){
            $this->error("您的账号被冻结！",3);
        }*/else{
            session("stall_id",$info['stall_id']);
            session("ma_id",$info['ma_id']);
            session("time",time());

            cookie('phone',$st_account);
            cookie('pwd',$st_pwd);
            if($info['stall_type'] == 1){
                $this->success("登录成功",U('Csorder/order'),0);
            }else{
                $this->success("登录成功",U('Tcorder/index'),0);
            }
        }
    }
}