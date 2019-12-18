<?php
// +----------------------------------------------------------------------
// | 订单
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
class WechatpayController extends Controller {
    // 普通订单支付
    public function ordera_notify_url(){
        $xmls = file_get_contents("php://input");

        $xmlstring = simplexml_load_string($xmls, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);

        $out_trade_no = $val["out_trade_no"];//订单号
        $total_fee = $val["total_fee"];
        //支付流水号
        $trade_no = $val['transaction_id'];
        $pay_no = $out_trade_no;
        $money = $total_fee / 100;
        if ($val["return_code"] == "SUCCESS")
        {
            //支付状态
            $infos = M('orders')->where(array('order_no'=>$out_trade_no))->find();
            //支付状态0未支付1已支付
            if ( $infos['order_status'] == 1)
            {
                //修改订单状态
                $order_data['trade_no'] = $trade_no;
                $order_data['payment_time'] = time();
                $order_data['order_status'] = 2;
                M('orders')->where(array('order_no'=>$out_trade_no))->save($order_data);
                //增加积分记录
                /*M('member_list')->where(array('member_list_id'=>$infos['member_list_id']))->setInc('integral',$infos['integral']);
                $res['order_no'] = $infos['order_no'];
                $res['ma_id'] = $infos['ma_id'];
                $res['member_list_id'] = $infos['member_list_id'];
                $res['state'] = 1;
                $res['type'] = 1;
                $res['integral'] = $infos['integral'];
                $res['creattime'] = time();
                M('integral_statistics')->add($res);*/
                 //查找默认的铃声
                $tone_music = M('stall_music')->where(array('stall_id'=>$infos['stall_id'],'music_type'=>1))->getField('music');
                $arr = array('type'=>"http://".$_SERVER['SERVER_NAME'].$tone_music);
                $n_content = '档口有新订单';
                $this->jpush($infos['stall_id'],$n_content,$arr);
            }
            echo "SUCCESS";
        }
    }

    public function integral_notify_url()
    {
        
        $xmls = file_get_contents("php://input");

        $xmlstring = simplexml_load_string($xmls, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);

        $out_trade_no = $val["out_trade_no"];//订单号
        $total_fee = $val["total_fee"];
        //支付流水号
        $trade_no = $val['transaction_id'];
        $pay_no = $out_trade_no;
        $money = $total_fee / 100;
        if ($val["return_code"] == "SUCCESS") 
        {
            //支付状态
            $is_pay = M('integral_pay_log')->where(array('pay_no'=>$pay_no))->find();
            //支付状态0未支付1已支付
            if ( $is_pay['state'] == 1)
            {
                //改变支付记录
                $data['state'] = 2;
                $data['pay_time'] = time();
                $data['trade_no'] = $trade_no;
                $pay_log_id = M('integral_pay_log')->where(array('id'=>$is_pay['id']))->save($data);
                //修改订单状态
                $order_data['payment_time'] = time();
                $order_data['status'] = 1;
                M('integral_order')->where(array('order_id'=>$is_pay['object_no']))->save($order_data);
                //扣除积分
                $orderInfo = M('integral_order')->where(array('order_id'=>$is_pay['object_no']))->find();
                M('member_list')->where(array('member_list_id'=>$orderInfo['member_list_id']))->setDec('integral',$orderInfo['use_integral']);
                 //减少库存
                M('goods')->where(array('goods_id'=>$orderInfo['goods_id']))->setDec('num',$orderInfo['pay_num']);
                //增加积分记录
                $res['order_no'] = $orderInfo['ordersn'];
                $res['ma_id'] = $orderInfo['ma_id'];
                $res['member_list_id'] = $orderInfo['member_list_id'];
                $res['state'] = 2;
                $res['type'] = 2;
                $res['integral'] = $orderInfo['use_integral'];
                $res['creattime'] = time();
                M('integral_statistics')->add($res);
                $shopData = M('merchant')->where(array('ma_id'=>$orderInfo['ma_id']))->field("appid,appsecret,take_food_id")->find();
                $memberData = M('member_list')->where(array('member_list_id'=>$orderInfo['member_list_id']))->field("openid,member_list_nickname,member_name,telphone,integral")->find();
                //查找推送信息
                $in['type'] = 8;
                $in['appid'] = $shopData['appid'];
                $in['appsecret'] = $shopData['appsecret'];
                $in['template_id'] = $shopData['integral_change_id'];
                $in['openid'] = $memberData['openid'];
                $in['title'] = '您好，您的会员积分信息有了新的变更。';
                $in['keyword1'] = $memberData['member_list_nickname'];
                $in['keyword2'] = $memberData['telphone'];
                $in['keyword3'] = '您有'.$orderInfo['use_integral'].'积分消费哦！';
                $in['keyword4'] = $memberData['integral'];
                setMessages($in);
            }
            echo "SUCCESS";
        }
    }

    public function aa($infos)
    {
        $shopData = M('merchant')->where(array('ma_id'=>$infos['ma_id']))->field("appid,appsecret,take_food_id")->find();
        $memberData = M('member_list')->where(array('member_list_id'=>$infos['member_list_id']))->field("openid,member_list_nickname,member_name,telphone,integral")->find();
        $info['type'] = 3;
        $info['appid'] = $shopData['appid'];
        $info['appsecret'] = $shopData['appsecret'];
        $info['template_id'] = $shopData['take_food_id'];
        $info['openid'] = $memberData['openid'];
        $info['title'] = '下单成功';
        $info['keyword1'] = $infos['order_no'];
        //$info['keyword2'] = '待取餐';
        $info['keyword3'] = $infos['create_time'];
        setMessages($info);

        $receiver_value = 5;
        $n_content = '档口有新订单';
        $tone_music = M('stall_music')->where(array('stall_id'=>$receiver_value,'music_type'=>1))->getField('music');
        $arr = array('type'=>"http://".$_SERVER['SERVER_NAME'].$tone_music);
        $this->jpush($receiver_value,$n_content,$arr);
    }
    public function jpush($receiver_value,$n_content,$arr){
        $JpushModel = new \Home\Model\UserModel();
        $m_type = 'http';//推送附加字段的类型
        // $arr = array('alert_type = '=>'2');//自定义参数
        $m_txt = $arr;//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
        $m_time = 0  ;//离线保留时间
        $receive = array('alias'=>array($receiver_value));
        $message="";//存储推送状态
        $result = $JpushModel->send($receive,$n_content,$m_type,$m_txt,$m_time);
        print_r($result);
        //file_put_contents('999.log', var_export($m_txt,TEUE).'|'.  PHP_EOL,FILE_APPEND);
        return $result;
    }

    // 修改订单状态
    public function order_ddf($order_id,$order_data){
        M('orders')->where(array('order_id'=>$order_id))->save($order_data);
    }



       //备餐完成（）
    public function order_eef($order_id){

        //验证
        if (empty($order_id)){
             return false;
            // $this->make_json_error('参数错误！',10508);
        }
        $orders_db = M('orders');
        $deliver_type = $orders_db->where(array('order_id'=>$order_id))->getField('deliver_type');

        $data['order_status'] = 3;
        $data['success_time'] = time();
        $data['payment_time'] = time();
        if ($deliver_type == 1){
            $data['is_grab'] = 1;
        }

        $res = $orders_db->where(array('order_id'=>$order_id))->save($data);
        if ($res !== false){
            //模板推送
            if ($deliver_type == 2){
                if(C('IsSetMessages')){
                    $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
                    $ordersData = M('orders')->where(array('order_id'=>$order_id))->field("member_list_id,order_no,create_time")->find();
                    $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,take_food_id")->find();
                    $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_name")->find();
                    $meal_code = M('order_goods')->where(array('order_id'=>$order_id))->getField("meal_code");
                    $info['type'] = 3;
                    $info['appid'] = $shopData['appid'];
                    $info['appsecret'] = $shopData['appsecret'];
                    $info['template_id'] = $shopData['take_food_id'];
                    $info['openid'] = $memberData['openid'];
                    $info['title'] = '您的订单状态为';
                    $info['keyword1'] = $ordersData['order_no'];
                    $info['keyword2'] = '待取餐   取餐码：'.$meal_code;
                    //$info['keyword2'] = '待取餐';
                    $info['keyword3'] = $ordersData['create_time'];
                    setMessages($info);
                }
            }
            if ($deliver_type == 1){
                if(C('IsSetMessages')){
                    $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
                    $ordersData = M('orders')->where(array('order_id'=>$order_id))->field("member_list_id,order_no,create_time")->find();
                    $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,take_food_id")->find();
                    $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_name")->find();
                    $meal_code = M('order_goods')->where(array('order_id'=>$order_id))->getField("meal_code");
                    $info['type'] = 3;
                    $info['appid'] = $shopData['appid'];
                    $info['appsecret'] = $shopData['appsecret'];
                    $info['template_id'] = $shopData['take_food_id'];
                    $info['openid'] = $memberData['openid'];
                    $info['title'] = '您的订单状态为';
                    $info['keyword1'] = $ordersData['order_no'];
                    $info['keyword2'] = '待取餐';
                    //$info['keyword2'] = '待取餐';
                    $info['keyword3'] = $ordersData['create_time'];
                    setMessages($info);
                }
            }
            return true;
            // $this->make_json_error("备餐成功！",0);
        }else{
             return true;
            // $this->make_json_error('操作失败！',1);
        }
    }
}