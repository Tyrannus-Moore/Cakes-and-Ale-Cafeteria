<?php
namespace Small\Controller;
use Think\Controller;
use Small\Controller\MyorderController;
class BaseController extends Controller{
    public function _initialize(){
        $ma_id = I('ma_id');


        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];
        
        if(!$ma_id){
            // $ma_id = session('ma_id');
            $ma_id = $sessions['ma_id'];
        }
       


        //退款操作
        if(C('IsSetMessages')){
            $order_db = M('orders');
            $tuikuan = $order_db->field("order_id,refund_time")->where(array('refund'=>1))->select();
            foreach ($tuikuan as $key=>$value){
                if($value['refund_time'] < time()-3600){
                    $this->refundAgreedBase($value['order_id']);
                }
            }
        }


        if($ma_id == $sessions['ma_id']){
            if(empty($sessions['member_list_id'])){
                // $this->redirect('Home/Oauth/login',array('ma_id'=>$ma_id));
                $data['code']  = 100;
                $data['msg'] = '获取失败，请重新授权';
                $this->ajaxReturn($data);
            }
            //首先判断会员是否被冻结
            $is_open = M('member_list')->where(array('member_list_id'=>$sessions['member_list_id']))->getField('is_open');
            if($is_open==2){
                // $this -> error('您的账号被冻结');
                $data['code']  = 100;
                $data['msg'] = '您的账号被冻结';
                $this->ajaxReturn($data);
            }
            //首先判断会员是否被删除
            $is_del = M('member_list')->where(array('member_list_id'=>$sessions['member_list_id']))->getField('is_del');
            if($is_del==1){
                // $this -> error('账号已被删除');
                $data['code']  = 100;
                $data['msg'] = '账号已被删除';
                $this->ajaxReturn($data);
            }
            // $map['member_list_id'] = $sessions['member_list_id'];
            //生效时间
            $map['order_status'] = 2;
            $startTime = date("Y-m-d",time());
            $map['refund'] = array('neq',1);
            $map['effect_time'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($startTime."23:59:59")));
            $res = M('orders')->where($map)->field("order_id")->select();
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
            //未支付订单十五分钟超时
            $ordersDb = M('orders');
            $order_goods_db = M('order_goods');
            $dishes_db = M('dishes');
            $passStatus = $ordersDb->where(array('order_status'=>1))->field("dishes_id,create_time,order_status,order_id,order_type")->select();
            
            foreach ($passStatus as $key => $value) {

                if(time()-$value['create_time']>15*60){
                    $ordersDb->where(array('order_id'=>$value['order_id']))->setField("order_status",7);
                    if($value['order_type'] == 1){
                        $orderGoods = $order_goods_db->where(array('order_id'=>$value['order_id']))->field('dishes_id,dishes_nums')->select();
                        foreach ($orderGoods as $k => $v) {
                            $dishes_db->where(array('dishes_id'=>$v['dishes_id']))->setInc("num",$v['dishes_nums']); 
                        }
                    }else{
                        
                        $dishes_db->where(array('dishes_id'=>$value['dishes_id']))->setInc("num",1); 
                    }
                    
                }
            }
            
            //积分商城订单七天确认收货
            $integral_order_db = M('integral_order');
            $ress = $integral_order_db->where(array('status'=>2))->select();
            $times = time()-7*86400;
            foreach ($ress as $key => $value) {
                if($times>$value['sendtime']){
                    $data['finishtime'] = time();
                    $data['status'] = 3;
                    //确认收货
                    $result = M('integral_order')->where(array('order_id'=>$value['order_id']))->save($data);
                    $infos = M('integral_order')->where(array('order_id'=>$value['order_id']))->find();
                    $res['order_no'] = $infos['ordersn'];
                    $res['ma_id'] = $infos['ma_id'];
                    $res['money'] = $infos['pay_money'];
                    $res['state'] = 3;
                    $res['type'] = 1;
                    $res['statue'] = 2;
                    $res['creattime'] = time();
                    M('finance')->add($res);
                }
            }
            // 普通订单三小时确认收货
            $order_db = M('orders');
            $olist = $order_db->where(array('order_status'=>4,'deliver_type'=>1))->select();
            $timea = time()-10800;
            $dishes_db = M('dishes');
            $stall_db = M('stall');
            $order_goods_db = M('order_goods');
            foreach ($olist as $k => $v) {
                if($timea > $v['take_time']){
                    $order_id = $v['order_id'];
                    $res = M('orders')->where(array('order_id'=>$order_id))->setField('order_status',5);
                    $infos = M('orders as a')
                        ->join("LEFT JOIN sm_member_list as b on a.ps_id = b.member_list_id")
                        ->where(array('order_id'=>$order_id))
                        ->field('a.*,b.openid')
                        ->find();
                    // 增加财务审计
                    if($infos['deliver_type'] ==1){
                        $dataF1['order_no'] = $infos['order_no'];
                        $dataF1['ma_id'] = $infos['ma_id'];
                        $dataF1['stall_id'] = $infos['stall_id'];
                        $dataF1['money'] = $infos['express_money'];
                        $dataF1['state'] = 2;
                        $dataF1['type'] = 1;
                        $dataF1['statue'] = 2;
                        $dataF1['creattime'] =$infos['payment_time'];
                        $res2 = M('finance')->add($dataF1);
                    }
                    $dataF['order_no'] = $infos['order_no'];
                    $dataF['ma_id'] = $infos['ma_id'];
                    $dataF['stall_id'] = $infos['stall_id'];
                    $dataF['money'] = $infos['real_money'];
                    $dataF['state'] = 1;
                    $dataF['type'] = 1;
                    $dataF['statue'] = 1;
                    $dataF['creattime'] =$infos['payment_time'];
                    $res1 = M('finance')->add($dataF);
                    //完成之后增加销量
                    $orderGoods = $order_goods_db->where(array('order_id'=>$order_id))->select();
                    $totalNum;
                    foreach ($orderGoods as $key => $value) {
                        $dishes_db->where(array('dishes_id'=>$value['dishes_id']))->setInc('on_the_pin',$value['dishes_nums']);
                        $totalNum+=$value['dishes_nums'];
                    }
                    $stall_db->where(array('stall_id'=>$v['stall_id']))->setInc('on_the_pin',$totalNum);
                }
            }
        }else{
            // $this->redirect('Home/Oauth/login',array('ma_id'=>$ma_id));
            $data['code'] = 100;
            $data['msg'] = '内部错误，请重新授权认证';
            $this->ajaxReturn($data);
        }
        /*if($ma_id == session('ma_id')){
            if(empty($sessions['member_list_id'])){
                $this->redirect('Home/Oauth/login',array('ma_id'=>$ma_id));
            }
        }else{
            $this->redirect('Home/Oauth/login',array('ma_id'=>$ma_id));
        }*/
    }
    //空操作
    public function _empty(){
        $this->error('此操作无效');
    }

    //申请退款同意
    public function refundAgreedBase($order_id)
    {
        $orderData = M('orders')->where(array('order_id'=>$order_id))->field("order_type,order_no,trade_no,real_money,member_list_id,ma_id,dishes_id,integral")->find();
        $shopData = M('merchant')->where(array('ma_id'=>$orderData['ma_id']))->field("appid,appsecret,keycode,partnerid,keycode_certificate,payment_certificate,refund_through_id")->find();
        $memberData = M('member_list')->where(array('member_list_id'=>$orderData['member_list_id'],'is_del'=>2))->field("openid,member_name")->find();

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
                // if(C('IsSetMessages')){
                //     $info['type'] = 7;
                //     $info['appid'] = $shopData['appid'];
                //     $info['appsecret'] = $shopData['appsecret'];
                //     $info['template_id'] = $shopData['refund_through_id'];
                //     $info['openid'] = $memberData['openid'];
                //     $info['title'] = '订单退款状态已变更为【同意退款】';
                //     $info['keyword1'] = $orderData['order_no'];
                //     $info['keyword2'] = time();
                //     $info['keyword3'] = $orderData['real_money'];
                //     setMessages($info);
                // }
            }
        }
    }


    //图片路径
    public function thumbUrl($arr = [],$field){
        foreach ($arr as $k => &$v) {
            $v[$field] = 'http://'.$_SERVER['SERVER_NAME'].$v[$field];
        }
        return $arr;
    }

    //内容图片路径
    public function conthumburl($row){
        return htmlspecialchars_decode(str_replace('/upfiles','https://'.$_SERVER['SERVER_NAME'].'/upfiles',htmlspecialchars($row)));
    }

     //递归调用--树处理
    public function Tree($arr,$pid=0){
        $res = [];//定义一个空数组
        foreach($arr as $v){
            if($v['pid'] == $pid){
                $data = $this->Tree($arr,$v['id']);
                if (!empty($data)) {
                    $v['next_level'] = $data;
                }
                $res[] = $v;
            }
        }
        return $res;
    }
    
    //获取自建session中的值
    public function Sessions(){
        if ($_COOKIE['sessions']) {
            session_id($_COOKIE['sessions']);
        }

        session_start();
        $arr = session_id();
        $sessions = json_decode($arr,true);
        return $sessions;
    }

    //数组转Xml
    function ArrToXml($arr){
        if(!is_array($arr) || count($arr) == 0) return '';

        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    //Xml转数组
    function XmlToArr($xml){
        if($xml == '') return '';
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }

    //sign生成及检测sign
    function getSign($param , $key){
        if(isset($param['sign'])){
            unset($param['sign']);
        }
        ksort($param);

        $str = urldecode(http_build_query($param));
        $str .= '&key='.$key;

        // return $param;
        return strtoupper(md5($str));
    }


    /**
    使用curl方式实现get或post请求
    @param $url 请求的url地址
    @param $data 发送的post数据 如果为空则为get方式请求
    return 请求后获取到的数据
     */
    public function curlRequest($url,$data = ''){
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_TIMEOUT] = 30; //超时时间
        if(!empty($data)){
            $params[CURLOPT_POST] = true;
            $params[CURLOPT_POSTFIELDS] = $data;
        }
        $params[CURLOPT_SSL_VERIFYPEER] = false;//请求https时设置,还有其他解决方案
        $params[CURLOPT_SSL_VERIFYHOST] = false;//请求https时,其他方案查看其他博文
        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
        curl_close($ch); //关闭连接
        return $content;
    }
}