<?php
namespace Home\Controller;
use Think\Controller;
class NotifyController extends Controller
{
    /*$JpushModel = new UserModel();
    $m_type = 'http';//推送附加字段的类型
        // $arr = array('mesType'=>'1','f_type'=>'1','f_id'=>1);//自定义参数
    $m_txt = '推送';//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
    $m_time = '86400';//离线保留时间
    $receive = array('alias'=>array(md5(18)));
    $message="";//存储推送状态
    $result = $JpushModel->send($receive,'推送消息',$m_type,$m_txt,$m_time);
    dump($result);
    return $result;*/

    //支付回调
    public function Notify()
    {
        $xmls = file_get_contents("php://input");

        $xmlstring = simplexml_load_string($xmls, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);

        $orderNo = $val["out_trade_no"];//订单号
        if ($val["return_code"] == "SUCCESS")
        {
            //B215112530530017
            $orderDb = M('orders');
            $pass = $orderDb->where(array('order_no'=>$orderNo))->field("order_id,ma_id,stall_id,real_money,order_status,integral,member_list_id")->find();
            //查找默认的铃声
            $tone_music = M('stall_music')->where(array('stall_id'=>$pass['stall_id'],'music_type'=>1))->getField('music');
            $arr = array('type'=>"http://".$_SERVER['SERVER_NAME'].$tone_music);
            $n_content = '档口有新订单';
            $this->jpush($pass['stall_id'],$n_content,$arr);
            if($pass['order_status'] == 1){
                $data['order_status'] = 2;
                $data['payment_time'] = time();
                $data['trade_no'] =  $val['transaction_id'];
                $orderDb->where(array('order_id'=>$pass['order_id']))->save($data);
                /*M('member_list')->where(array('member_list_id'=>$pass['member_list_id']))->setInc("integral",$pass['integral']);
                $inr['order_no'] = $orderNo;
                $inr['ma_id'] = $pass['ma_id'];
                $inr['member_list_id'] = $pass['member_list_id'];
                $inr['state'] = 1;
                $inr['type'] = 1;
                $inr['integral'] = $pass['integral'];
                $inr['creattime'] = time();
                M('integral_statistics')->add($inr);*/
            }
            echo "SUCCESS";
        }
    }
    public function jpush($receiver_value,$n_content,$arr){
        $JpushModel = new \Home\Model\UserModel();
        $m_type = 'http';//推送附加字段的类型
        // $arr = array('mesType'=>'1','f_type'=>'1','f_id'=>1);//自定义参数
        $m_txt = $arr;//推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
        $m_time = 86400;//离线保留时间        
        $receive = array('alias'=>array($receiver_value));
        $message="";//存储推送状态
        $result = $JpushModel->send($receive,$n_content,$m_type,$m_txt,$m_time);
        // var_dump($result);
        return $result;
    }
}