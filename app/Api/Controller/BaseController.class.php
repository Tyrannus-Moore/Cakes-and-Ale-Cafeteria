<?php
// +----------------------------------------------------------------------
// | 功能:登陆基础验证
// +----------------------------------------------------------------------
namespace Api\Controller;
use Common\Controller\CommonController;
use Think\Controller;
class BaseController extends Controller
{
    public $stall_id;
    //验证用户是否登录
    protected function _initialize(){
        //parent::_initialize();
        $this->stall_id = decrypt(cookie('stall_id'));
        // $this->stall_id = 3;
        //退款操作
        $order_db = M('orders');
        $tuikuan = $order_db->field("order_id,refund_time")->where(array('refund'=>1))->select();
        foreach ($tuikuan as $key=>$value){
            if($value['refund_time'] < time()-3600){
                $this->refundAgreedBase($value['order_id']);
            }
        }
        if(!$this->stall_id) {
            $this->make_json_error("用户未登录",512);
        }else{
            $where['stall_id'] = $this->stall_id;
            $where['order_status'] = 2;
            $startTime = date("Y-m-d",time());
            $where['refund'] = array('neq',1);
            $where['effect_time'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($startTime."23:59:59")));
            $res = M('orders')->where($where)->field("order_id")->select();
            if($res){
                foreach($res as $key=>$value){
                    $ss['order_status'] = 4;
                    $ss['child_status'] = 1;
                    $pass = M('orders')->where(array('order_id'=>$value['order_id']))->save($ss);
                    if($pass){
                        M('order_goods')->where(array('order_id'=>$value['order_id']))->setField("state",1);
                    }
                }
            }
        }
        //统计上月月销
        // $pin['FROM_UNIXTIME(addtime,"%Y-%m")'] = date('Y-m',time());
        // $the_pin = M('the_pin_log')->where($pin)->find();
        // if(empty($the_pin)){
        //     $Model = new \Think\Model();
        //     $dishes_db = M('dishes');
        //     $stall_db = M('stall');
        //     //时间
        //     $time = time();
        //     $starttime = strtotime(date('Y-m-d',strtotime(date("Y-m",strtotime("last month"))))." 00:00:00");
        //     $endtime = strtotime(date('Y-m-d',strtotime(date("Y-m",$time)))." 00:00:00");
        //     //统计菜品上月月销
        //     $sql1 = "SELECT a.dishes_id,d.on_the_pin FROM sm_dishes as a LEFT JOIN
        //             (SELECT c.dishes_id,SUM(c.dishes_nums)as on_the_pin FROM sm_orders as b
        //             LEFT JOIN sm_order_goods c
        //             on b.order_id=c.order_id
        //             WHERE b.order_type=1 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
        //             GROUP BY dishes_id)
        //             d on a.dishes_id=d.dishes_id
        //             where a.is_del=2 AND a.type=1";
        //     $list1 = $Model->query($sql1);
        //     foreach ($list1 as $key1=>$value1){
        //         $dishes_db->save($value1);
        //     }
        //     $sql2 = "SELECT a.dishes_id,d.on_the_pin FROM sm_dishes as a LEFT JOIN
        //             (SELECT b.dishes_id,count(*) as on_the_pin FROM sm_orders as b 
        //             WHERE b.order_type=2 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
        //             GROUP BY dishes_id)
        //             d on a.dishes_id=d.dishes_id
        //             where a.is_del=2 AND a.type=2";
        //     $list2 = $Model->query($sql2);
        //     foreach ($list2 as $key2=>$value2){
        //         $dishes_db->save($value2);
        //     }
        //     //统计档口上月月销
        //     $sql3 = "SELECT a.stall_id,d.on_the_pin FROM sm_stall as a LEFT JOIN
        //             (SELECT b.stall_id,SUM(c.dishes_nums)as on_the_pin FROM sm_orders as b
        //             LEFT JOIN sm_order_goods c
        //             on b.order_id=c.order_id
        //             WHERE b.order_type=1 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
        //             GROUP BY stall_id)
        //             d on a.stall_id=d.stall_id
        //             where a.delete=2 AND a.stall_type=1";
        //     $list3 = $Model->query($sql3);
        //     foreach ($list3 as $key3=>$value3){
        //         $stall_db->save($value3);
        //     }
        //     $sql4 = "SELECT a.stall_id,d.on_the_pin FROM sm_stall as a LEFT JOIN
        //             (SELECT b.stall_id,count(*) as on_the_pin FROM sm_orders as b 
        //             WHERE b.order_type=2 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
        //             GROUP BY stall_id)
        //             d on a.stall_id=d.stall_id
        //             where a.delete=2 AND a.stall_type=2";
        //     $list4 = $Model->query($sql4);
        //     foreach ($list4 as $key4=>$value4){
        //         $stall_db->save($value4);
        //     }
        //     M('the_pin_log')->add(array('addtime'=>time()));
        // }
    }

    //申请退款同意
    public function refundAgreedBase($order_id)
    {
        $orderData = M('orders')->where(array('order_id'=>$order_id))->field("order_type,order_no,trade_no,real_money,member_list_id,ma_id,dishes_id,integral")->find();
        $shopData = M('merchant')->where(array('ma_id'=>$orderData['ma_id']))->field("appid,appsecret,keycode,partnerid,keycode_certificate,payment_certificate,refund_through_id")->find();
        $memberData = M('member_list')->where(array('member_list_id'=>$orderData['member_list_id']))->field("openid,member_name")->find();

        $refundData['appid']    = $shopData['appid'];
        $refundData['mchid']    = $shopData['partnerid'];
        $refundData['keys']     = $shopData['keycode'];
        $refundData['appsecret']= $shopData['appsecret'];
        $refundData['cert_pem'] = $shopData['payment_certificate'];
        $refundData['key_pem']  = $shopData['keycode_certificate'];
        $refundData['order_no'] = $orderData['order_no'];
        $refundData['trade_no'] = $orderData['trade_no'];
        $refundData['out_refund_no']= makeOrderNo();
        $refundData['total_fee']    = $orderData['real_money'];
        $refundData['refund_fee']   = $orderData['real_money'];
        $pass = refund_wechat($refundData);
        //file_put_contents('2.log',$order_id);
        //file_put_contents("1.log",$pass);
        if($pass['status'] == 1){
            $data['order_status'] = 8;
            $data['refund'] = 2;
            $res = M('orders')->where(array('order_id'=>$order_id))->save($data);
            if($res){
                //减库存
                if($orderData['order_type'] == 1){
                    $goodsData = M('order_goods')->where(array('order_id'=>$order_id))->field("dishes_id,dishes_nums")->find();
                    foreach ($goodsData as $key=>$value){
                        M('dishes')->where(array('dishes_id'=>$value['dishes_id']))->setInc("num",$value['dishes_nums']);
                    }
                }else{
                    M('dishes')->where(array('dishes_id'=>$orderData['dishes_id']))->setInc("num",1);
                }
                /*//增加积分记录
                $inr['order_no'] = $orderData['order_no'];
                $inr['ma_id'] = $orderData['ma_id'];
                $inr['member_list_id'] = $orderData['member_list_id'];
                $inr['state'] = 1;
                $inr['type'] = 3;
                $inr['integral'] = $orderData['integral'];
                $inr['creattime'] = time();
                M('integral_statistics')->add($inr);
                //减会员积分
                M('member_list')->where(array('member_list_id'=>$orderData['member_list_id']))->setDec("integral",$orderData['integral']);*/
                //发送模板
                if(C('IsSetMessages')){
                    $info['type'] = 7;
                    $info['appid'] = $shopData['appid'];
                    $info['appsecret'] = $shopData['appsecret'];
                    $info['template_id'] = $shopData['refund_through_id'];
                    $info['openid'] = $memberData['openid'];
                    $info['title'] = '订单退款状态已变更为【同意退款】';
                    $info['keyword1'] = $orderData['order_no'];
                    $info['keyword2'] = time();
                    $info['keyword3'] = $orderData['real_money'];
                    setMessages($info);
                }
            }
        }
    }


}