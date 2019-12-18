<?php
// +----------------------------------------------------------------------
// | 功能:套餐订单管理
// +----------------------------------------------------------------------
namespace Api\Controller;


class TcorderController extends BaseController
{
    /*
     * 套餐订单（已生效）
     */
    public function equipmentEat()
    {
        $where['stall_id'] = $this->stall_id;
        $where['order_status'] = 4;
        $where['order_type'] = 2;
        $child_status = I('child_status');
        if($child_status != 1 && $child_status != 2){
            $this->make_json_error('参数错误',101);
        }
        $where['child_status'] = $child_status;
        $psize = I('psize', 20); //每页显示条数
        $page = I('page', 1);  //当前页
        $offset = ($page - 1) * $psize;
        $list = M('orders')
            ->field("order_id,stall_tel,telphone,phone,real_money,dishes_id,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i') as create_time,dishes_name,dishes_url,discount")
            ->where($where)
            ->limit($offset, $psize)
            ->order("create_time desc")->select();
        foreach ($list as $key=>$value){
            $pass = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>array('eq',$child_status)))->field("type,day,state,eat_finish_time")->order("id asc")->find();
            $list[$key]['diningType'] = $pass['type'];
            $list[$key]['weekType'] = $pass['day'];
            if($pass['state'] == 1){
                $list[$key]['endStatus'] = 2;
                $list[$key]['passNo'] = 1;
                $list[$key]['goodsData'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))
                    ->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
            }else{
                $dd = M('stall')->where(array('a.stall_id'=>$this->stall_id))
                    ->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->getField("b.timeout");
                $list[$key]['passNo'] = 2;
                $dt=date('Y-m-d H:i:s',$pass['eat_finish_time']);
                $dd = $dd*60;
                $endTime = strtotime("$dt   +".$dd."   minute");
                if($endTime<time()){
                    $list[$key]['endStatus'] = 1;
                }else{
                    $list[$key]['endStatus'] = 2;
                }
                $list[$key]['goodsData'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>2,'type'=>$pass['type'],'day'=>$pass['day']))
                    ->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
            }
        }
        $this->make_json_result('已生效订单',$list);
    }

    /*
     * 套餐订单（已完成）
     */
    public function finishList()
    {
        $map['stall_id'] = $this->stall_id;
        $map['order_type'] = 2;
        $map['order_status'] = array('in','5,6');
        $psize = I('psize', 20); //每页显示条数
        $page = I('page', 1);  //当前页
        $offset = ($page - 1) * $psize;
        $finishDatas = M('orders')
            ->field("order_id,order_status,dishes_url,dishes_name,real_money,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i') as create_time,discount,dishes_money")
            ->limit($offset, $psize)
            ->where($map)
            ->order("create_time desc")->select();
        $this->make_json_result('已完成订单',$finishDatas);
    }

    /*
     * 套餐订单（已取消）
     */
    public function cancelList()
    {
        $map['stall_id'] = $this->stall_id;
        $map['order_type'] = 2;
        $map['order_status'] = array('eq',7);
        $psize = I('psize', 20); //每页显示条数
        $page = I('page', 1);  //当前页
        $offset = ($page - 1) * $psize;
        $cancelDatas = M('orders')
            ->field("order_id,order_status,dishes_url,dishes_name,real_money,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i') as create_time,discount,dishes_money")
            ->limit($offset, $psize)
            ->where($map)
            ->order("create_time desc")->select();
        $this->make_json_result('已取消订单',$cancelDatas);
    }

    /*
     * 套餐订单（退款申请订单）
     */
    public function refundList()
    {
        $map['stall_id'] = $this->stall_id;
        $map['order_type'] = 2;
        $map['order_status'] = array('in','2,8');
        $map['refund'] = array('in','1,2');
        $psize = I('psize', 20); //每页显示条数
        $page = I('page', 1);  //当前页
        $offset = ($page - 1) * $psize;
        $refundDatas = M('orders')
            ->field("order_id,order_status,dishes_url,dishes_name,real_money,FROM_UNIXTIME(refund_time,'%Y-%m-%d %H:%i:%s') as refund_time,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i') as create_time,discount,dishes_money,telphone")
            ->limit($offset, $psize)
            ->where($map)
            ->order("create_time desc")->select();
        $this->make_json_result('退款申请订单',$refundDatas);
    }

    //备餐完成
    public function finishOrder()
    {
        $order_id = I('order_id');
        if(empty($order_id)){
            $this->make_json_error('参数错误',101);
        }
        $pass = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1))->field("type,day")->order("id asc")->find();
        $res = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))->save(array('state'=>2,'eat_finish_time'=>time()));
        if($res){
            M('orders')->where(array('order_id'=>$order_id))->setField("child_status",2);
            if(C('IsSetMessages')){
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
                $info['keyword2'] = '待取餐   取餐码：'.$meal_code;
                //$info['keyword2'] = '待取餐';
                $info['keyword3'] = $ordersData['create_time'];
                setMessages($info);
            }
            $this->make_json_error('备餐成功',0);
        }else{
            $this->make_json_error('备餐失败',104);
        }
    }

    //取餐操作
    public function takeFoodOperation()
    {
        $number = I('number');
        if(empty($number)){
            $this->make_json_error('参数错误',101);
        }
        $where['b.stall_id'] = $this->stall_id;
        $where['a.state'] = 2;
        $where['a.meal_code'] = $number;
        $order_id = M('order_goods')
            ->join('AS a LEFT JOIN __ORDERS__ AS b ON a.order_id = b.order_id')
            ->where($where)->getField("b.order_id");
        if($order_id){
            $list['order_id'] = $order_id;
            $this->make_json_result('取餐ID',$list);
        }else{
            $this->make_json_error('取餐码错误',104);
        }
    }

    //取餐完成
    public function takeFood()
    {
        $id = I('order_id');
        $pass = M('order_goods')->where(array('order_id'=>$id,'state'=>2))->field("type,day")->order("id asc")->find();
        $res = M('order_goods')->where(array('order_id'=>$id,'state'=>2,'type'=>$pass['type'],'day'=>$pass['day']))->save(array('state'=>3,'end_status'=>1));
        if($res !== false){
            $passTure = M('order_goods')->where(array('order_id'=>$id,'state'=>1))->order("id asc")->find();
            if(empty($passTure)){
                $data['order_status'] = 5;
                $data['child_status'] = 3;
                $pass = M('orders')->where(array('order_id'=>$id))->field("order_no,ma_id,stall_id,real_money,payment_time,member_list_id,integral")->find();
                $f['order_no'] = $pass['order_no'];
                $f['ma_id'] = $pass['ma_id'];
                $f['stall_id'] = $pass['stall_id'];
                $f['money'] = $pass['real_money'];
                $f['state'] = 1;
                $f['type'] = 1;
                $f['statue'] = 1;
                $f['creattime'] = $pass['payment_time'];
                M('finance')->add($f);
                //增加积分记录
                $inr['order_no'] = $pass['order_no'];
                $inr['ma_id'] = $pass['ma_id'];
                $inr['member_list_id'] = $pass['member_list_id'];
                $inr['state'] = 1;
                $inr['type'] = 1;
                $inr['integral'] = $pass['integral'];
                $inr['creattime'] = time();
                M('integral_statistics')->add($inr);
                M('member_list')->where(array('member_list_id'=>$pass['member_list_id']))->setInc("integral",$pass['integral']);
                //发送模板
                if(C('IsSetMessages')){
                    $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
                    $ordersData = M('orders')->where(array('order_id'=>$id))->field("member_list_id,order_no,create_time,integral")->find();
                    $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,been_completed_id,integral_change_id")->find();
                    $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_list_nickname,telphone,member_name,integral")->find();
                    $info['type'] = 4;
                    $info['appid'] = $shopData['appid'];
                    $info['appsecret'] = $shopData['appsecret'];
                    $info['template_id'] = $shopData['been_completed_id'];
                    $info['openid'] = $memberData['openid'];
                    $info['title'] = '您的订单状态已变更';
                    $info['keyword1'] = $ordersData['order_no'];
                    $info['keyword2'] = '已完成';
                    $info['keyword3'] = $ordersData['create_time'];
                    setMessages($info);

                    // $in['type'] = 8;
                    // $in['appid'] = $shopData['appid'];
                    // $in['appsecret'] = $shopData['appsecret'];
                    // $in['template_id'] = $shopData['integral_change_id'];
                    // $in['openid'] = $memberData['openid'];
                    // $in['title'] = '您好，您的会员积分信息有了新的变更。';
                    // $in['keyword1'] = $memberData['member_list_nickname'];
                    // $in['keyword2'] = $memberData['telphone'];
                    // $in['keyword3'] = '您有'.$ordersData['integral'].'积分入户哦！';
                    // $in['keyword4'] = $memberData['integral'];
                    // setMessages($in);
                }

                $orderInfo = M('orders')->where(array('order_id'=>$id))->find();
                M('dishes')->where(array('dishes_id'=>$orderInfo['dishes_id']))->setInc('on_the_pin',1);
                M('stall')->where(array('stall_id'=>$orderInfo['stall_id']))->setInc('on_the_pin',1);
            }else{
                $data['child_status'] = 1;
            }
            M('orders')->where(array('order_id'=>$id))->save($data);
            $this->make_json_error('取餐成功',0);
        }else {
            $this->make_json_error('取餐失败',104);
        }
    }

    //取餐超时完成
    public function TakeFoodTimeout()
    {
        $id = I('order_id');
        $pass = M('order_goods')->where(array('order_id'=>$id,'state'=>2))->field("type,day,meal_code")->order("id asc")->find();
        $res = M('order_goods')->where(array('order_id'=>$id,'state'=>2,'type'=>$pass['type'],'day'=>$pass['day']))->save(array('state'=>3,'end_status'=>2));
        if($res !== false){
            $passTure = M('order_goods')->where(array('order_id'=>$id,'state'=>1))->order("id asc")->find();
            if(empty($passTure)){
                $data['order_status'] = 5;
                $data['child_status'] = 3;
                $pass = M('orders')->where(array('order_id'=>$id))->field("order_no,ma_id,stall_id,real_money,payment_time,member_list_id,integral")->find();
                $f['order_no'] = $pass['order_no'];
                $f['ma_id'] = $pass['ma_id'];
                $f['stall_id'] = $pass['stall_id'];
                $f['money'] = $pass['real_money'];
                $f['state'] = 1;
                $f['type'] = 1;
                $f['statue'] = 1;
                $f['creattime'] = $pass['payment_time'];
                M('finance')->add($f);
                //增加积分记录
                $inr['order_no'] = $pass['order_no'];
                $inr['ma_id'] = $pass['ma_id'];
                $inr['member_list_id'] = $pass['member_list_id'];
                $inr['state'] = 1;
                $inr['type'] = 1;
                $inr['integral'] = $pass['integral'];
                $inr['creattime'] = time();
                M('integral_statistics')->add($inr);
                M('member_list')->where(array('member_list_id'=>$pass['member_list_id']))->setInc("integral",$pass['integral']);
                //发送模板
                if(C('IsSetMessages')){
                    $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
                    $ordersData = M('orders')->where(array('order_id'=>$id))->field("member_list_id,order_no,create_time,integral")->find();
                    $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,been_completed_id,integral_change_id")->find();
                    $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_list_nickname,telphone,member_name,integral")->find();
                    $info['type'] = 4;
                    $info['appid'] = $shopData['appid'];
                    $info['appsecret'] = $shopData['appsecret'];
                    $info['template_id'] = $shopData['been_completed_id'];
                    $info['openid'] = $memberData['openid'];
                    $info['title'] = '您的订单状态已变更';
                    $info['keyword1'] = $ordersData['order_no'];
                    $info['keyword2'] = '已完成';
                    $info['keyword3'] = $ordersData['create_time'];
                    setMessages($info);

                    // $in['type'] = 8;
                    // $in['appid'] = $shopData['appid'];
                    // $in['appsecret'] = $shopData['appsecret'];
                    // $in['template_id'] = $shopData['integral_change_id'];
                    // $in['openid'] = $memberData['openid'];
                    // $in['title'] = '您好，您的会员积分信息有了新的变更。';
                    // $in['keyword1'] = $memberData['member_list_nickname'];
                    // $in['keyword2'] = $memberData['telphone'];
                    // $in['keyword3'] = '您有'.$ordersData['integral'].'积分入户哦！';
                    // $in['keyword4'] = $memberData['integral'];
                    // setMessages($in);
                }
                $orderInfo = M('orders')->where(array('order_id'=>$id))->find();
                M('dishes')->where(array('dishes_id'=>$orderInfo['dishes_id']))->setInc('on_the_pin',1);
                M('stall')->where(array('stall_id'=>$orderInfo['stall_id']))->setInc('on_the_pin',1);
            }else{
                $data['child_status'] = 1;
            }
            M('orders')->where(array('order_id'=>$id))->save($data);
           
            $this->make_json_error('操作成功',0);
        }else{
            $this->make_json_error('操作失败',104);
        }
    }

    //订单详情
    public function orderDetails()
    {
        $order_id = I('order_id');
        //$order_id = 229;
        $list = M('orders')
            ->join('AS a LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')
            ->field("a.order_id,a.order_no,a.order_status,a.real_money,a.integral,
            FROM_UNIXTIME(a.create_time,'%Y-%m-%d %H:%i') as create_time,
            FROM_UNIXTIME(a.payment_time,'%Y-%m-%d %H:%i') as payment_time,
            FROM_UNIXTIME(a.effect_time,'%Y-%m-%d') as starttime,
            a.total_money,a.dishes_url,a.dishes_name,a.dishes_money,a.stall_address,a.stall_tel,a.order_note,b.stall_name,a.telphone,a.refund,a.user_refuse_reason")
            ->where(array('a.order_id'=>$order_id))->find();
        $list['endtime'] = date('Y-m-d',strtotime("$list[starttime]+1week"));

        if($list['order_status'] == 4){
            $pass = M('order_goods')->where(array('order_id'=>$order_id,'state'=>array('in','1,2')))->field("type,day,state,eat_finish_time")->order("id asc")->find();
            $list['diningType'] = $pass['type'];
            $list['weekType'] = $pass['day'];
            if($pass['state'] == 1){
                $list['endStatus'] = 2;
                $list['passNo'] = 1;
                $list['goodsData'] = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))
                    ->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
            }else{
                $dd = M('stall')->where(array('a.stall_id'=>$this->stall_id))
                    ->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->getField("b.timeout");
                $list['passNo'] = 2;
                $dt=date('Y-m-d H:i:s',$pass['eat_finish_time']);
                $dd = $dd*60;
                $endTime = strtotime("$dt   +".$dd."   minute");
                //$endTime = strtotime("$dt+3hour");
                if($endTime<time()){
                    $list['endStatus'] = 1;
                }else{
                    $list['endStatus'] = 2;
                }
                $eye = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1))->find();
                if($eye){
                    $list['passEye'] = 2;
                }else{
                    $list['passEye'] = 1;
                }
                $list['goodsData'] = M('order_goods')->where(array('order_id'=>$order_id,'state'=>2,'type'=>$pass['type'],'day'=>$pass['day']))
                    ->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
            }
        }else{
            $list['endStatus'] = 2;
            $pass = M('order_goods')->where(array('order_id'=>$order_id))->field("type,day")->order("id asc")->find();
            $list['diningType'] = $pass['type'];
            $list['weekType'] = $pass['day'];
            $list['goodsData'] = M('order_goods')->where(array('order_id'=>$order_id,'type'=>$pass['type'],'day'=>$pass['day']))
                ->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
            if($list['order_status'] == 6){
                $list['evaluation'] = M('comment')
                    ->join('AS a LEFT JOIN __MEMBER_LIST__ AS b ON a.member_list_id = b.member_list_id')
                    ->field("a.dish_score,a.service_score,a.image,a.content,FROM_UNIXTIME(a.addtime,'%Y-%m-%d %H:%i') as addtime,b.member_list_headpic,b.member_list_nickname")
                    ->where(array('order_id'=>$order_id))
                    ->find();
                if (!empty($list['evaluation']['image'])){
                    $list['evaluation']['image'] = explode(',',$list['evaluation']['image']);
                }else{
                    $list['evaluation']['image'] = [];
                }
            }
        }
        $this->make_json_result('订单详情',$list);
    }

    //套餐小订单商品
    public function NextOrder()
    {
        $orderId    = I('order_id');
        $diningType = I('diningType');
        $weekType   = I('weekType');
        if(empty($orderId) || empty($diningType) || empty($weekType)){
            $this->make_json_error('参数错误',101);
        }
        $list = M('order_goods')->where(array('order_id'=>$orderId,'type'=>$diningType,'day'=>$weekType))
            ->field("id,dishes_name,state,dishes_nums,dishes_price")->order("id asc")->select();
        if($list){
            $this->make_json_result('套餐小订单商品',$list);
        }else{
            $res['data'] = [];
            $res['code'] = 104;
            $res['message'] = '暂无数据';
            $this->ajaxReturn($res,'JSON');
        }
    }

    //申请退款拒绝
    public function refundRefused()
    {
        $order_id = I('order_id');
        $refuse_reason = I('refuse_reason');
        if(empty($order_id) || empty($refuse_reason)){
            $this->make_json_error('参数错误',101);
        }
        $data['refuse_reason'] = $refuse_reason;
        $data['refund'] = 3;
        $res = M('orders')->where(array('order_id'=>$order_id))->save($data);
        if($res){
            //发送模板
            if(C('IsSetMessages')){
                $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
                $ordersData = M('orders')->where(array('order_id'=>$order_id))->field("member_list_id,order_no,create_time")->find();
                $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,refund_refused_id")->find();
                $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_name")->find();
                $info['type'] = 6;
                $info['appid'] = $shopData['appid'];
                $info['appsecret'] = $shopData['appsecret'];
                $info['template_id'] = $shopData['refund_refused_id'];
                $info['openid'] = $memberData['openid'];
                $info['title'] = '订单退款状态已变更为【拒绝退款】';
                $info['keyword1'] = $ordersData['order_no'];
                $info['keyword2'] = $ordersData['create_time'];
                $info['keyword3'] = time();
                $info['keyword4'] = $refuse_reason;
                setMessages($info);
            }
            $this->make_json_error('操作成功',0);
        }else{
            $this->make_json_error('操作失败',104);
        }
    }

    //申请退款同意
    public function refundAgreed()
    {
        $order_id = I('order_id');
        if(empty($order_id)){
            $this->make_json_error('参数错误',101);
        }
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
                $this->make_json_error('退款成功',0);
            }else{
                $this->make_json_error('退款失败',104);
            }
        }else{
            $this->make_json_error('退款失败',105,$pass);
        }
    }

}