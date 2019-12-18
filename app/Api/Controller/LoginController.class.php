<?php
namespace Api\Controller;
use Think\Controller;
class LoginController extends Controller
{
    //登录
    public function login()
    {
        $st_account = I("account");
        $st_pwd = I("password");
        //判断登录账号密码是否为空
        if (empty($st_account) && empty($st_pwd)) {
            $this->make_json_error('参数错误！',1);
        }
        $info = M("stall")->where(array('delete'=>2,'st_account'=>$st_account))->find();
        if(empty($info)){
            $this->make_json_error('账号不存在！',1);
        }elseif($info['st_pwd'] != encrypt_password($st_pwd,$info['ma_pwd_salt'])) {
            $this->make_json_error('密码错误!',204);
        }else{
            setcookie("stall_id",encrypt($info['stall_id']),time()+86400*30);

            $this->make_json_result("登录成功",array('stall_id'=>$info['stall_id'],'stall_type'=>$info['stall_type'],'is_freeze'=>$info['is_freeze'],'automatic'=>$info['automatic']));
        }
    }

    //退出
    public function logout()
    {
        cookie('stall_id',null);

        $this->make_json_error('退出登录成功!',0);
    }
    public function jpush(){
        $JpushModel = new \Home\Model\UserModel();
        $m_type = 'http';//推送附加字段的类型
        $arr = array('type'=>"http://mccygood.com/data/upload/avatar/fb4d7e34-4b82-44d1-8246-0f1ceea61d89.mp3");
        // $arr = array('mesType'=>'1','f_type'=>'1','f_id'=>1);//自定义参数
        $m_txt = $arr;//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
        $m_time = 86400;//离线保留时间
        $n_content = '档口有新订单';
        $receive = array('alias'=>array('1'));
        $message="";//存储推送状态
        $result = $JpushModel->send($receive,$n_content,$m_type,$m_txt,$m_time);
        // var_dump($result);
        return $result;
    }

    //申请退款同意
    public function refundAgreeds()
    {
        $order_no = I('order_id');
        $order_id = M('orders')->where(array('order_no'=>$order_no))->getField("order_id");
        if(empty($order_id)){
            $this->make_json_error('参数错误',101);
        }
        $orderData = M('orders')->where(array('order_id'=>$order_id))->field("order_type,order_no,trade_no,real_money,member_list_id,ma_id,dishes_id,integral")->find();
        $shopData = M('merchant')->where(array('ma_id'=>$orderData['ma_id']))->field("appid,appsecret,keycode,partnerid,keycode_certificate,payment_certificate,refund_through_id")->find();
        //$memberData = M('member_list')->where(array('member_list_id'=>$orderData['member_list_id']))->field("openid,member_name")->find();

        $refundData['appid']    = $shopData['appid'];
        $refundData['mchid']    = $shopData['partnerid'];
        $refundData['keys']     = $shopData['keycode'];
        $refundData['appsecret']= $shopData['appsecret'];
        $refundData['cert_pem'] = $shopData['payment_certificate'];
        $refundData['key_pem']  = $shopData['keycode_certificate'];
        $refundData['order_no'] = $orderData['order_no'];
        $refundData['trade_no'] = $orderData['trade_no'];
        $refundData['out_refund_no']= makeOrderNo();
        $refundData['total_fee']    = 0.01;
        $refundData['refund_fee']   = 0.01;
        $pass = refund_wechat($refundData);
        dump($pass);
    }

    public function getIn()
    {
        $this->stall_id = 4;
        $id = 26;
        //发送模板
        $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
        $ordersData = M('orders')->where(array('order_id'=>$id))->field("deliver_type,member_list_id,order_no,create_time,ps_id,meals_time,integral")->find();
        $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,been_completed_id,yqc_food_id,integral_change_id")->find();
        $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_name,integral,telphone")->find();
        $info['type'] = 4;
        $info['appid'] = $shopData['appid'];
        $info['appsecret'] = $shopData['appsecret'];
        $info['template_id'] = $shopData['been_completed_id'];
        $info['openid'] = $memberData['openid'];
        $info['title'] = '您的订单状态已变更';
        $info['keyword1'] = $ordersData['order_no'];
        $info['keyword2'] = '已完成';
        $info['keyword3'] = $ordersData['create_time'];
        dump($info);
        //setMessages($info);
        $in['type'] = 8;
        $in['appid'] = $shopData['appid'];
        $in['appsecret'] = $shopData['appsecret'];
        $in['template_id'] = $shopData['integral_change_id'];
        $in['openid'] = $memberData['openid'];
        $in['title'] = '您好，您的会员积分信息有了新的变更。';
        $in['keyword1'] = $memberData['member_name'];
        $in['keyword2'] = $memberData['telphone'];
        $in['keyword3'] = '您有'.$ordersData['integral'].'积分入户哦！';
        $in['keyword4'] = $memberData['integral'];
        //setMessages($in);
        dump($in);

        /*$order_id = 30;
        $this->stall_id = 4;
        if(empty($order_id)){
            $this->make_json_error('参数错误',101);
        }
        $pass = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1))->field("type,day")->order("id asc")->find();
        $res = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))->save(array('state'=>2,'eat_finish_time'=>time()));
        M('orders')->where(array('order_id'=>$order_id))->setField("child_status",2);
        dump($pass);
        $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
        $ordersData = M('orders')->where(array('order_id'=>$order_id))->field("member_list_id,order_no,create_time")->find();
        $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,take_food_id")->find();
        $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_name")->find();
        $meal_code = M('order_goods')->where(array('order_id'=>$order_id,'type'=>$pass['type'],'day'=>$pass['day']))->getField("meal_code");
        $info['type'] = 3;
        $info['appid'] = $shopData['appid'];
        $info['appsecret'] = $shopData['appsecret'];
        $info['template_id'] = $shopData['take_food_id'];
        $info['openid'] = $memberData['openid'];
        $info['title'] = '您的订单状态已变更';
        $info['keyword1'] = $ordersData['order_no'];
        $info['keyword2'] = '待取餐,'.$meal_code;
        //$info['keyword2'] = '待取餐';
        $info['keyword3'] = $ordersData['create_time'];
        dump($info);*/
    
    }
}