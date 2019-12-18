<?php
namespace Small\Controller;
use Think\Controller;
use Small\Model\qiyeModel;

class WechatController extends Controller
{

    //小程序支付回调地址
    public function notify_url(){
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

                //判断是否为自动接单状态
                $automatic = M('stall')->where(array('stall_id'=>$infos['stall_id']))->getField("automatic");
                if($automatic == 2){
                    $order_id = M('orders')->where(array('order_no'=>$out_trade_no))->getField("order_id");
                    $res = $this->order_eef($order_id);
                    if(!$res){
                        $data['code'] = 100;
                        $data['msg'] = '内部错误';
                        $this->ajaxReturn($data);
                    }
                }else{
                    $order_data['order_status'] = 2;
                    M('orders')->where(array('order_no'=>$out_trade_no))->save($order_data);
                }
                //判断是否为自动接单状态

                // M('orders')->where(array('order_no'=>$out_trade_no))->save($order_data);
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

    public function jpush($receiver_value,$n_content,$arr){
        $JpushModel = new \Home\Model\UserModel();
        $m_type = 'http';//推送附加字段的类型
        // $arr = array('mesType'=>'1','f_type'=>'1','f_id'=>1);//自定义参数
        $m_txt = $arr;//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
        $m_time = 0;//离线保留时间        
        $receive = array('alias'=>array($receiver_value));
        $message="";//存储推送状态
        $result = $JpushModel->send($receive,$n_content,$m_type,$m_txt,$m_time);
        print_r($result);
        //file_put_contents('999.log', var_export($m_txt,TEUE).'|'.  PHP_EOL,FILE_APPEND);
        return $result;
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
