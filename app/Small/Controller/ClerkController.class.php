<?php
// +----------------------------------------------------------------------
// |  配送员
// +----------------------------------------------------------------------
namespace Small\Controller;
use Small\Controller\BaseController;

class ClerkController extends BaseController
{
    // public function _initialize(){
    //     session('member_list_id',1);
    //     session('ma_id',1);
    // }
    //首页
    public function index()
    {
        $this->display();
    }

    //我的任务-待抢单
    public function task(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $page = I('page');
        $orders_db = M('orders as a');

        $sheets_count = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.is_grab'=>1,'a.order_type'=>1,'a.order_status'=>3))->field('a.*,b.image,b.stall_name')->order('a.create_time desc')->select();

        $pagesize = 10;
        $count = count($sheets_count);
        $pagesum = ceil($count/$pagesize);
        $start = ($page-1)*$pagesize;

        //待抢单
        $sheets = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.is_grab'=>1,'a.order_type'=>1,'a.order_status'=>3))->field('a.*,b.image,b.stall_name')->order('a.create_time desc')->limit($start , $pagesize)->select();
        // $this->assign('sheets',$sheets);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('status');

        // echo $orders_db->getLastSql();
        foreach ($sheets as $key => $value) {
            $sheets[$key]['create_time'] = date("Y-m-d H:i:s" , $value['create_time']);
            $address_info = M('user_address_list')->where(array('address'=>$value['address']))->find();
            $sheets[$key]['proviceid'] = M('user_address')->where(array('id'=>$address_info['proviceid']))->getField('title');
            $sheets[$key]['cityid'] = M('user_address')->where(array('id'=>$address_info['cityid']))->getField('title');
            $sheets[$key]['countyid'] = M('user_address')->where(array('id'=>$address_info['countyid']))->getField('title');
        }
        $sheets = $this->thumbUrl($sheets,'image');

        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['data']['list'] = $sheets;
        $data['data']['status'] = $status;
        $data['data']['click_status'] = 1;

        $this->ajaxReturn($data);
        // $this->assign('status',$status);
        // $this->display();
    }

    
    //待取餐
    public function waitOrder(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $page = I('page');
        $orders_db = M('orders as a');
        //订单列表
        $list_count = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','3'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>$member_list_id))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->select();

        $pagesize = 10;
        $count = count($list_count);
        $pagesum = ceil($count/$pagesize);
        $start = ($page-1)*$pagesize;

        $list = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','3'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>$member_list_id))->order('a.create_time desc')->field('a.*,b.image,b.stall_name,b.stall_tel')->limit($start , $pagesize)->select();

        $order_goods_db = M('order_goods');
        foreach ($list as $key => $value) {
            $list[$key]['meal_code'] = $order_goods_db->where(array('order_id'=>$value['order_id']))->limit(1)->getField('meal_code');
            $list[$key]['create_time'] = date("Y-m-d H:i:s" , $value['create_time']);
            $address_info = M('user_address_list')->where(array('address'=>$value['address']))->find();
            $list[$key]['proviceid'] = M('user_address')->where(array('id'=>$address_info['proviceid']))->getField('title');
            $list[$key]['cityid'] = M('user_address')->where(array('id'=>$address_info['cityid']))->getField('title');
            $list[$key]['countyid'] = M('user_address')->where(array('id'=>$address_info['countyid']))->getField('title');
            $list[$key]['qrcode'] = "mccy".substr(time(), -5).$order_goods_db->where(array('order_id'=>$value['order_id']))->limit(1)->getField('meal_code');
        }
        $list = $this->thumbUrl($list,'image');
        // dump($list);
        // $this->assign('list',$list);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('status');

        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['data']['list'] = $list;
        $data['data']['status'] = $status;
        $data['data']['click_status'] = 2;

        $this->ajaxReturn($data);
    }


    //配送中
    public function deliveryOrder(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $page = I('page');
        $orders_db = M('orders as a');
        //订单列表
        $list_count = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','4'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>$member_list_id))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->select();

        $pagesize = 10;
        $count = count($list_count);
        $pagesum = ceil($count/$pagesize);
        $start = ($page-1)*$pagesize;

        $list = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','4'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>$member_list_id))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->limit($start , $pagesize)->select();

        $order_goods_db = M('order_goods');
        foreach ($list as $key => $value) {
            $list[$key]['meal_code'] = $order_goods_db->where(array('order_id'=>$value['order_id']))->limit(1)->getField('meal_code');
            $list[$key]['create_time'] = date("Y-m-d H:i:s" , $value['create_time']);
            $address_info = M('user_address_list')->where(array('address'=>$value['address']))->find();
            $list[$key]['proviceid'] = M('user_address')->where(array('id'=>$address_info['proviceid']))->getField('title');
            $list[$key]['cityid'] = M('user_address')->where(array('id'=>$address_info['cityid']))->getField('title');
            $list[$key]['countyid'] = M('user_address')->where(array('id'=>$address_info['countyid']))->getField('title');
        }
        $list = $this->thumbUrl($list,'image');
        // dump($list);
        // $this->assign('list',$list);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('status');

        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['data']['list'] = $list;
        $data['data']['status'] = $status;
        $data['data']['click_status'] = 3;

        $this->ajaxReturn($data);
    }


    //已完成
    public function finishOrder(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $page = I('page');
        $orders_db = M('orders as a');
        //订单列表
        $list_count = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','5,6'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>$member_list_id))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->select();

        $pagesize = 10;
        $count = count($list_count);
        $pagesum = ceil($count/$pagesize);
        $start = ($page-1)*$pagesize;

        $list = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','5,6'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>$member_list_id))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->limit($start , $pagesize)->select();

        $order_goods_db = M('order_goods');
        foreach ($list as $key => $value) {
            $list[$key]['meal_code'] = $order_goods_db->where(array('order_id'=>$value['order_id']))->limit(1)->getField('meal_code');
            $list[$key]['create_time'] = date("Y-m-d H:i:s" , $value['create_time']);
            $address_info = M('user_address_list')->where(array('address'=>$value['address']))->find();
            $list[$key]['proviceid'] = M('user_address')->where(array('id'=>$address_info['proviceid']))->getField('title');
            $list[$key]['cityid'] = M('user_address')->where(array('id'=>$address_info['cityid']))->getField('title');
            $list[$key]['countyid'] = M('user_address')->where(array('id'=>$address_info['countyid']))->getField('title');
        }
        $list = $this->thumbUrl($list,'image');
        // dump($list);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('status');

        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['data']['list'] = $list;
        $data['data']['status'] = $status;
        $data['data']['click_status'] = 4;

        $this->ajaxReturn($data);
    }


    //抢单
    public function sheet(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $order_id = I('order_id');
        $arr['ps_id'] = $member_list_id;
        $arr['order_status'] = 3;
        $arr['is_grab'] = 2;
        //首先判断这个人是否还是配送员
        $is_status = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('status');
        if($is_status == 1){
            $data['code'] = 100;
            $data['msg'] = '您不是配送员';
        }
        //判断该订单是否被抢
        $is_find = M('orders')->where(array('order_id'=>$order_id))->getField('is_grab');
        if($is_find == 2){
            $data['code'] = 100;
            $data['msg'] = '此订单已被抢';
        }
        $result = M('orders')->where(array('order_id'=>$order_id))->save($arr);
        if($result){
            $order_info = M('orders')->where(array('order_id'=>$order_id))->find();
            $member_list_info = M("member_list")->where(array("member_list_id"=>$member_list_id))->find();
            $openid = M('member_list')->where(array("member_list_id"=>$order_info['member_list_id']))->getField("openid");
            $template_id = "HPuQozv9yvkh5rJdzVB-OKyRkr9m0T48tUMdbGlVE4M";
            $time = time()+15*60;
            $send_data = [
                "first"  =>  "你好，你的订单已开始配送",
                "keyword1"  => "测试测试",
                "keyword2"  => $member_list_info['member_list_nickname']."（".$member_list_id['telphone']."）",
                "remark"  => "预计".date("H:i" , $time)."送达，请做好准备，谢谢。",
            ];
            $this->send_tmp($openid , $template_id , $send_data , 1);
            $data['code'] = 200;
            $data['msg'] = '抢单成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '抢单失败';
        }
        $this->ajaxReturn($data);
    }


    //任务详情
    public function taskDetail(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $orders_db = M('orders as a');
        $order_id = I('order_id');
        //订单信息
        $infos = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->join('LEFT JOIN __MERCHANT__ AS c ON b.ma_id = c.ma_id')->where(array('a.order_id'=>$order_id))->field('a.*,b.image,b.stall_name,b.stall_tel,c.address as stall_address')->find();
        $data['code'] = 200;
        $data['msg'] = "获取成功";

        $infos['image'] = "http://mccygood.com".$infos['image'];
        $infos['create_time'] = date("Y-m-d H:i:s" , $infos['create_time']);
        $infos['payment_time'] = date("Y-m-d H:i:s" , $infos['payment_time']);
        $data['data']['infos'] = $infos;

        //送货地址信息
        $member_info = M('member_list')->where(array('member_list_id'=>$infos['member_list_id']))->find();
        $address_info = M('user_address_list')->where(array('address'=>$infos['address']))->find();
        $data['data']['address']['proviceid'] = M('user_address')->where(array('id'=>$address_info['proviceid']))->getField('title');
        $data['data']['address']['cityid'] = M('user_address')->where(array('id'=>$address_info['cityid']))->getField('title');
        $data['data']['address']['countyid'] = M('user_address')->where(array('id'=>$address_info['countyid']))->getField('title');
        $data['data']['address']['headpic'] = $member_info['member_list_headpic'];
        $data['data']['address']['nickname'] = $member_info['member_list_nickname'];
        $data['data']['address']['telphone'] = $member_info['telphone'];

        //配送人信息
        $ps_info = M('member_list')->field('member_list_nickname,member_list_headpic,telphone')->where(array('member_list_id'=>$infos['ps_id']))->find();
        $data['data']['ps_info'] = $ps_info;

        $order_goods_db = M('order_goods');
        //订单商品信息
        $list = $order_goods_db->where(array('order_id'=>$order_id))->select();
        $data['data']['list'] = $list;

        //查看此人的兼职类型
        $type = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('type');
        $data['data']['type'] = $type;

        //取餐超时时间
        $dd = M('stall')->where(array('a.stall_id'=>$infos['stall_id']))
            ->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->getField("b.timeout");
        // var_dump($dd);
        $dt=date('Y-m-d H:i:s',$infos['success_time']);
        $dd = $dd*60;
        $endTimes = date("Y-m-d H:i:s",strtotime("$dt   +".$dd."   minute"));
        $data['data']['endTimes'] = $endTimes;

        $this->ajaxReturn($data);
    }

    //我的收益
    public function myMoney(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $total_money = M('orders')->where(array('ps_id'=>$member_list_id,'order_status in (5,6)','deliver_type'=>1))->getField("sum(express_money)");

        $list = M('orders as a')->field("a.stall_id,b.stall_name,b.image,a.express_money,a.meals_time")->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('ps_id'=>$member_list_id,'order_status in (5,6)','deliver_type'=>1))->select();

        $total_order = M('orders')->where(array('ps_id'=>$member_list_id,'order_status in (5,6)','deliver_type'=>1))->getField('count(*)');

        if ($total_money == ""){
            $total_money = 0;
        }

        if ($total_order == ""){
            $total_order = 0;
        }

        foreach ($list as $key => $value) {
            if ($value['meals_time'] != '') {
                $list[$key]['meals_time'] = date("Y-m-d H:i" , $value['meals_time']);
            }
        }
        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['data']['totle_order'] = $total_order;
        $data['data']['total_money'] = $total_money;
        $list = $this->thumbUrl($list,'image');
        $data['data']['list'] = $list;

        $this->ajaxReturn($data);
    }
	
	//提交提现数据接口
	public function withdrawal(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $arr = json_decode(file_get_contents("php://input") , true);
        $money = $arr['money'];

        $info = M("member_list")->where(array('member_list_id'=>$member_list_id))->find();
        if ($info['money'] < 10){
            $data['code'] = 100;
            $data['msg'] = '少于10元无法提现';
        } else {
            if ($info['money'] < $money){
                $data['code'] = 100;
                $data['msg'] = '当前余额不足';
            } else {
                $payment_id = 'TX' . date("YmdHis") . rand(10000, 99999);
                $ins_data = [
                    'order_no' => $payment_id,
                    'member_list_id' => $member_list_id,
                    'money' => $money,
                    'status' => 0,
                    'c_time' => time(),
                ];
                $res = M("withdrawal")->add($ins_data);
                if ($res) {
					$openid = M("member_list")->where(array('member_list_id' => $member_list_id))->getField('openid');
                    //$ret = $this->test_pay($money,$openid,$payment_id);
					$ret = file_get_contents("https://hbgc.mccygood.com/warehouse/weixin/public/testPay?money=".$money."&openid=".$openid."&payment_id=".$payment_id);
					
					file_put_contents('tixian.log' , $ret , FILE_APPEND);
					$arr = json_decode($ret , true);
                    if($arr['code'] == 200){
						$arr_data['status'] = 1;
						M("withdrawal")->where(array('order_no'=>$payment_id))->save($arr_data);
						M("member_list")->where(array('member_list_id'=>$member_list_id))->setDec('money' , $money);
						$data['code'] = 200;
						$data['msg'] = '申请提现成功,预计在1-3个工作日内到账';
					} else {
						$arr_data['status'] = 2;
						M("withdrawal")->where(array('order_no'=>$payment_id))->save($arr_data);
						$data['code'] = 100;
						$data['msg'] = '申请提现失败';
					}
                    if($arr['code'] == 300){
						M("withdrawal")->where(array('order_no'=>$payment_id))->delete();
						$data['code'] = 100;
						$data['msg'] = $arr['msg'];
					}
                } else {
                    $data['code'] = 100;
                    $data['msg'] = '申请提现失败';
                }
            }
        }

        $this->ajaxReturn($data);
    }
	
	    //提现记录
    public function withdrawal_list(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $list = M("withdrawal")->where(array('member_list_id'=>$member_list_id))->select();

        foreach ($list as $key=>$val){
            $list[$key]['c_time'] = date('Y-m-d H:i:s' , $val['c_time']);
        }

        $data['code'] = 200;
        $data['msg'] = M("withdrawal")->getLastSql();
        $data['data'] = $list;
        $this->ajaxReturn($data);
    }
	
	public function test_pay($amount , $openid , $payment_id){

        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";

//        $openid = "o0WsF5tDqvax2kZFhkl4mnNwlPPE"; //用户唯一标示
//
//        $amount = "0.5"; //付款金额
//
//        $payment_id = $this->_get_pay_code();//订单号，可以自定义，但不能重复使用
        
        // 执行支付

        $parameters = array(

            'mch_appid' =>  "wx6f20b935cb808182",//绑定支付的APPID

            'mchid' => '1523992141',//商户号

            'nonce_str' => $this->create_noncestr(32), //生成32位的随机字符串

            'partner_trade_no' => $payment_id,  //商户订单号，不能重复使用

            'openid' => $openid,//用户的open_id

            'check_name' => 'NO_CHECK',//是否检查姓名

            // 're_user_name' => $transfer['real_name'],  //真实姓名

            'amount' => bcmul($amount,100,0),   //企业付款金额，单位为分  最低1元

            'desc' => '提现',//备注

            'spbill_create_ip' => strval($_SERVER['SERVER_ADDR']),//服务器IP

        );

        $parameters['sign'] = $this->getSign($parameters, "fe29cc0f340f8da7fe1d293e37eaec6e");//getSign商户支付密钥

        $xml = $this->arrayToXml($parameters);

        $response = $this->postXmlCurl($xml, $url,true);

        $result = $this->xmlToArray($response);

		file_put_contents('tixian.log' , $response , FILE_APPEND);
        if($result['return_code']=='SUCCESS'){

            if($result['result_code']=='SUCCESS'){
				$data['msg'] = 'true';
                return $data;

            }else{

                $data['msg'] =  $result['err_code_des'].' 付款失败';

                return $data;

            }

        }else{

            $data['msg'] = '付款失败，微信接口出现异常';

			return $data;

        }

    }

    public function  create_noncestr($length=16){

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $str = '';

        for ($i = 0; $i < $length; $i++) {

            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);

        }

        return $str;

    }

    public function getSign($obj,$key){

        foreach ($obj as $k => $v) {

            $parameters[$k] = $v;

        }

        ksort($parameters);

        $string = $this->formtBizQueryParMap($parameters,false);

        $string = $string."&key=".$key;

        // var_dump($string);

        $string = md5($string);

        $result =strtoupper($string);

        // var_dump($result);die;

        return $result;

    }





    public function arrayToXml($arr){

        $xml = "<xml>";

        foreach ($arr as $key => $value) {

            if(is_numeric($value)){

                $xml .= "<" .$key. ">" .$value ."</" .$key .">";

            }else{

                $xml .= "<" .$key. "><![CDATA[".$value."]]></".$key.">";

            }

        }

        $xml .="</xml>";

        return $xml;

    }





    public function postXmlCurl($xml,$url,$userCert= false,$second= 30){

        $ch = curl_init();

        // 设置超时时间

        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //严格模式

        curl_setopt($ch, CURLOPT_HEADER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if($userCert ==true){

            // 设置证书

            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');

            curl_setopt($ch, CURLOPT_SSLCERT, './apiclient_cert.pem');//证书位置相对路径

            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'pem');

            curl_setopt($ch, CURLOPT_SSLKEY, './apiclient_key.pem'); //证书位置 相对路径

        }

        // post 提交

        curl_setopt($ch, CURLOPT_POST, TRUE);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        // 运行curl

        $data = curl_exec($ch);

        // 返回结果

        if($data){

            curl_close($ch);

            return $data;

        }else{

            $error = curl_error($ch);

            curl_close($ch);

            return "error".$error;
//            $this->error("curl出错,错误码".$error,'wxpostXmlCurl');



        }



    }



    //  将xml 转array

    public function xmlToArray($xml){

        $array_data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),TRUE);

        return $array_data;

    }



    //  格式化参数，签名使用

    public function  formtBizQueryParMap($paramap,$urlencode){

        $buff = "";

        ksort($paramap);

        foreach ($paramap as $k => $v) {

            if($urlencode){

                $v = $urlencode['$v'];

            }

            $buff .= $k ."=".$v."&";

        }

        $reqpar = '';

        if(strlen($buff) >0){

            $reqpar = substr($buff, 0,strlen($buff)-1);

        }

        return $reqpar;

    }


    //取消订单
    public function cancel(){
        $order_id = I('order_id');
        $data['order_status'] = 2;
        $data['is_grab'] = 1;
        //判断该订单现在是不是你的
        $ps_id = M('orders')->where(array('order_id'=>$order_id))->getField('ps_id');
        if($ps_id != session('member_list_id')){
            $this->ajaxReturn(3);
        }
        $result = M('orders')->where(array('order_id'=>$order_id))->save($data);
        if($result){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(2);
        }
    }

    public function send_tmp($touser , $template_id , $data , $flag){
        $appid = "wx6f20b935cb808182";
        $secret = "8dc48e526ece28eb48db4aea5ecf2947";

        $arr = $this->get_token($appid , $secret);
        $access_token = $arr['access_token'];
        $send_url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/uniform_send?access_token=".$access_token;
        // $touser = "o0WsF5gs_fYWxvMm_lDGjYDksNEw";
        $g_appid = "wxc0b30000d65d2286";
        // $template_id = "HPuQozv9yvkh5rJdzVB-OKyRkr9m0T48tUMdbGlVE4M";

        $arr_data = [
            "touser"    =>  $touser,
            "weapp_template_msg"    =>  [],
            "mp_template_msg"   =>  [
                "appid"         =>  $g_appid,
                "template_id"   =>  $template_id,
                "url"           =>  "https://www.baidu.com",
                "miniprogram"   =>  [
                    "appid" =>  "wx6f20b935cb808182",
                    "path"  =>  "pages/access/access"
                ],
                "data"  =>  [],
            ],
            
        ];

        if ($flag == 1) {
            $arr_data['mp_template_msg']['template_id'] = $template_id;
            $arr_data['mp_template_msg']['data'] = [
                "first"  =>  [
                    "value" =>  $data['first'],
                ],
                "keyword1"  =>  [
                    "value" =>  $data['keyword1'],
                ],
                "keyword2"  =>  [
                    "value" =>  $data['keyword2'],
                ],
                "remark"  =>  [
                    "value" =>  $data['remark'],
                ],
            ];
        } else {
            $arr_data['mp_template_msg']['template_id'] = $template_id;
        }

        $json_data = json_encode($arr_data);

        $send_data = $this->curlRequest($send_url , $json_data);
        // echo $send_data;
    }


    public function get_token($appid , $secret){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;

        $data = S('name');
        $arr = json_decode($data , true);
        if (empty($arr)) {
            $data = $this->curlRequest($url);
            S('name',$data,3600);
            $arr = json_decode($data , true);
        }

        return $arr;
    }
}