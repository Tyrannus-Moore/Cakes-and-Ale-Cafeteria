<?php
// +----------------------------------------------------------------------
// | 功能:普通订单管理
// +----------------------------------------------------------------------
namespace Api\Controller;
use Think\Controller;
use Api\Controller\BaseController;

class PtorderController extends BaseController
{
    //订单列表
    public function order()
    {
        $stall_id = $this->stall_id;
        $orders_db = M('orders');
        $order_goods_db = M('order_goods');
        $itemIndex = I('itemIndex');

        $psize = I('psize',10); //每页显示条数
        $page = I('page',1);  //当前页
        $offset = ($page - 1) * $psize;

        $where = array();
        $where['a.stall_id'] = $stall_id;//档口
        $where['a.order_type'] = 1;//普通菜品
        if($itemIndex == 2){
            $where['a.order_status'] = 3;
        }else if($itemIndex == 4){//已完成
            $where['a.order_status'] = array('in','4,5,6');
        }else if($itemIndex == 3){//退款
            $where['a.refund'] = array('in','1,2');
        }else{
            $where['a.order_status'] = 2;
            $where['a.refund'] = array('neq',1);
        }

        $list = $orders_db->alias('a')
            ->field("a.order_id,a.create_time,a.order_status,a.phone,a.deliver_type,a.telphone,a.success_time,a.order_no,a.refund")
            //->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.member_list_id = c.member_list_id')
            ->where($where)
            ->limit($offset,$psize)
            ->order('a.create_time desc')
            ->select();
        $merchant = M('stall')->alias('a')
            ->field('timeout')
            ->join('LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')
            ->where(array('a.stall_id'=>$stall_id))
            ->find();
        foreach ($list as $key=>$value){
            $list[$key]['create_time'] = date('Y-m-d H:i',$value['create_time']);
            $list[$key]['goodlist'] = $order_goods_db->field("dishes_name,pic_url,dishes_nums")->where(array('order_id'=>$value['order_id']))->select();
            if($value['order_status'] == 3){
                $list[$key]['timeout'] = date('Y-m-d H:i',$value['success_time']+3600*$merchant['timeout']);
            }
        }

        $this->make_json_result("订单列表",$list);
    }

    //备餐完成
    public function order_eef()
    {
        $order_id = I('order_id');
        //验证
        if (empty($order_id)){
            $this->make_json_error('参数错误！',10508);
        }
        $orders_db = M('orders');
        $deliver_type = $orders_db->where(array('order_id'=>$order_id))->getField('deliver_type');

        $data['order_status'] = 3;
        $data['success_time'] = time();
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
                    $info['title'] = '您的订单状态已变更';
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
                    $info['title'] = '您的订单状态已变更';
                    $info['keyword1'] = $ordersData['order_no'];
                    $info['keyword2'] = '待取餐';
                    //$info['keyword2'] = '待取餐';
                    $info['keyword3'] = $ordersData['create_time'];
                    setMessages($info);
                }
            }
            $this->make_json_error("备餐成功！",0);
        }else{
            $this->make_json_error('操作失败！',1);
        }
    }

    //取餐
    public function order_tf()
    {

        $orders_db = M('orders');
        $stall_id = $this->stall_id;
        $meal_code = I('meal_code');//取餐码
        //验证
        if (empty($meal_code)){
            $this->make_json_error('参数错误！',10508);
        }

        $where['a.stall_id'] = $stall_id;
        //$where['order_type'] = 1;//普通菜品
        // $where['a.order_status'] = 3;//待取餐
        $where['b.meal_code'] = $meal_code;
        $infos = $orders_db->alias('a')
            ->field("a.order_id,a.order_status")
            ->join('LEFT JOIN __ORDER_GOODS__ AS b ON a.order_id = b.order_id')
            ->where($where)
            ->find();

        // if (!empty($infos)){
        //     $this->make_json_result("验证成功",array('order_id'=>$infos['order_id']));
        // }else{
        //     $this->make_json_error('请输入正确的取餐码！',1);
        // }


        if (empty($infos)){
            $this->make_json_error('请输入正确的取餐码！',1);
        }elseif($infos['order_status'] == 4 || $infos['order_status'] == 5){
            $this->make_json_error('该订单已取走',1);
        }else{
            $this->make_json_result("验证成功",array('order_id'=>$infos['order_id']));
        }
    }

    //确认取餐
    public function order_qr()
    {
        $orders_db = M('orders');
        $order_id = I('order_id');
        $stall_id = $this->stall_id;
        //dump($stall_id);exit;
        //验证
        if (empty($order_id)){
            $this->make_json_error('参数错误！',10508);
        }

        $where['stall_id'] = $stall_id;
        $where['order_status'] = 3;//待取餐
        $where['order_id'] = $order_id;
        $infos = $orders_db->alias('a')
            ->field("a.order_id,a.deliver_type,a.order_no,a.ma_id,a.stall_id,a.real_money,a.payment_time,a.longitude,a.latitude,a.ma_id,a.member_list_id,a.integral")
            //->join('LEFT JOIN __ORDER_GOODS__ AS b ON a.order_id = b.order_id')
            ->where($where)
            ->find();
        if(!empty($infos)){
            if ($infos['deliver_type'] == 1){
                $data['order_status'] = 4;
            }else{
                $data['state'] = 1;
                $data['order_status'] = 5;
                //添加财务审计
                $res['order_no'] = $infos['order_no'];
                $res['ma_id'] = $infos['ma_id'];
                $res['stall_id'] = $stall_id;
                $res['money'] = $infos['real_money'];
                $res['state'] = 1;
                $res['type'] = 1;
                $res['statue'] = 1;
                $res['creattime'] = $infos['payment_time'];
                M('finance')->add($res);
                //增加积分记录
                $inr['order_no'] = $infos['order_no'];
                $inr['ma_id'] = $infos['ma_id'];
                $inr['member_list_id'] = $infos['member_list_id'];
                $inr['state'] = 1;
                $inr['type'] = 1;
                $inr['integral'] = $infos['integral'];
                $inr['creattime'] = time();
                M('integral_statistics')->add($inr);
                M('member_list')->where(array('member_list_id'=>$infos['member_list_id']))->setInc("integral",$infos['integral']);
                //完成之后增加销量
                $orderGoods = M('order_goods')->where(array('order_id'=>$order_id))->select();
                $dishes_db = M('dishes');
                foreach ($orderGoods as $key => $value) {
                    $dishes_db->where(array('dishes_id'=>$value['dishes_id']))->setInc('on_the_pin',$value['dishes_nums']);
                    $totalNum+=$value['dishes_nums'];
                }
                M('stall')->where(array('stall_id'=>$infos['stall_id']))->setInc('on_the_pin',$totalNum);
            }
            $data['take_time'] = time();
            //大概送达时间
            $merchant = M('merchant')->field("longitude,latitude")->where(array('ma_id'=>$infos['ma_id']))->find();
            $distance = $this->getDistanceByGaoDe($infos['longitude'],$infos['latitude'],$merchant['longitude'],$merchant['latitude']);
            //file_put_contents('./a.txt',$distance."----------".PHP_EOL,FILE_APPEND);
            $data['meals_time'] = (int) (time()+round(($distance/50+5),1)*60);
            $a = $orders_db->where(array('order_id'=>$infos['order_id']))->save($data);
            if(C('IsSetMessages')) {
                $this->xiao_zt($order_id);//模板推送
            }
            if($a !== false){
                $this->make_json_error("取餐成功",0);
            }else{
                $this->make_json_error("取餐失败",1);
            }
        }else{
            $this->make_json_error("非法操作！",10658);
        }

    }

    //模板消息
    public function xiao_zt($id)
    {
        //发送模板
        $ma_id = M('stall')->where(array('stall_id'=>$this->stall_id))->getField("ma_id");
        $ordersData = M('orders')->where(array('order_id'=>$id))->field("deliver_type,member_list_id,order_no,create_time,ps_id,meals_time,integral")->find();
        $shopData = M('merchant')->where(array('ma_id'=>$ma_id))->field("appid,appsecret,been_completed_id,yqc_food_id,integral_change_id")->find();
        $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_list_nickname,member_name,integral,telphone")->find();
        if($ordersData['deliver_type'] == 2){
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
        }else{
            $orderGoodsData = M('order_goods')->where(array('order_id'=>$id))->field("dishes_name,dishes_nums")->select();
            foreach ($orderGoodsData as $key => $value) {
                $arr[] = $value['dishes_name'].' '.$value['dishes_nums'].'份';
            }
            $goodsArr = implode(',', $arr);
            $psyData = M('member_list')->where(array('member_list_id'=>$ordersData['ps_id']))->field("member_name,telphone")->find();
            $info['type'] = 5;
            $info['appid'] = $shopData['appid'];
            $info['appsecret'] = $shopData['appsecret'];
            $info['template_id'] = $shopData['yqc_food_id'];
            $info['openid'] = $memberData['openid'];
            $info['title'] = '您的订单状态已变更';
            $info['keyword1'] = $goodsArr;
            $info['keyword2'] = $psyData['member_name'].'('.$psyData['telphone'].')';
            $info['remark'] = '预计'.date('H:i',$ordersData['meals_time']).',送达，请做好准备，谢谢。';
            //$info['keyword4'] = $ordersData['meals_time'];//送达时间
            setMessages($info);
        }
        
    }

    //距离
    public function getDistanceByGaoDe($slng, $slat, $elng, $elat,$decimal=2)
    {
        $earth_radius = 6378.137;//地球半径
        $lng1 = (M_PI / 180) * $slng;
        $lng2 = (M_PI / 180) * $elng;
        $lat1 = (M_PI / 180) * $slat;
        $lat2 = (M_PI / 180) * $elat;
        // 两点间距离 km，如果想要米的话，结果*1000就可以了
        $d = acos(
                sin($lat1) * sin($lat2)
                + cos($lat1) * cos($lat2) * cos($lng2 - $lng1)
            ) * $earth_radius;
        // 精度2位小数
        $d = sprintf("%.2f",$d*1000);;

        return $d;
    }

    //取餐超时
    public function order_cs()
    {
        $orders_db = M('orders');
        $order_id = I('order_id');
        //验证
        if (empty($order_id)){
            $this->make_json_error('参数错误！',10508);
        }

        $data['state'] = 2;
        $data['order_status'] = 5;
        $res = $orders_db->where(array('order_id'=>$order_id))->save($data);

        if ($res !== false){
            //财务审计
            $infos = M('orders')->field("order_id,order_no,ma_id,stall_id,real_money,payment_time")->where(array('order_id'=>$order_id))->find();
            $res['order_no'] = $infos['order_no'];
            $res['ma_id'] = $infos['ma_id'];
            $res['stall_id'] = $infos['stall_id'];
            $res['money'] = $infos['real_money'];
            $res['state'] = 1;
            $res['type'] = 1;
            $res['statue'] = 1;
            $res['creattime'] = $infos['payment_time'];
            M('finance')->add($res);
            //完成之后增加销量
            $orderGoods = M('order_goods')->where(array('order_id'=>$order_id))->select();
            $dishes_db = M('dishes');
            $totalNum;
            foreach ($orderGoods as $key => $value) {
                $dishes_db->where(array('dishes_id'=>$value['dishes_id']))->setInc('on_the_pin',$value['dishes_nums']);
                $totalNum+=$value['dishes_nums'];
            }
            M('stall')->where(array('stall_id'=>$infos['stall_id']))->setInc('on_the_pin',$totalNum);
            $this->make_json_error("操作成功",0);
        }else{
            $this->make_json_error('操作失败！',1);
        }
    }

    //详情
    public function order_xq()
    {
        $order_id = I('order_id');
        $stall_id = $this->stall_id;
        //验证
        if (empty($order_id)){
            $this->make_json_error('参数错误！',10508);
        }
        //档口名称
        $stall_name = M('stall')->where(array('stall_id'=>$stall_id))->getField('stall_name');
        //订单
        $infos = M('orders')->alias('a')
            ->field("a.order_id,
                a.order_no,
                a.member_list_id,
                a.username,
                a.phone,
                a.address,
                FROM_UNIXTIME(a.express_time,'%Y-%m-%d %H:%i') as express_time,
                a.total_money,
                a.express_money,
                a.discount,
                a.real_money,
                a.order_status,
                a.deliver_type,
                a.integral,
                a.is_grab,
                a.trade_no,
                a.state,
                FROM_UNIXTIME(a.create_time,'%Y-%m-%d %H:%i') as create_time,
                FROM_UNIXTIME(a.payment_time,'%Y-%m-%d %H:%i') as payment_time,
                a.success_time,
                FROM_UNIXTIME(a.take_time,'%Y-%m-%d %H:%i') as take_time,
                a.order_type,
                a.order_note,
                a.stall_address,
                a.telphone,
                a.stall_tel,
                a.user_refuse_reason,
                a.refund,
                a.refuse_reason,
                c.member_list_nickname,
                c.member_list_headpic as yh_headpic,
                d.member_list_headpic as ps_headpic,
                d.member_name as ps_name,
                d.telphone as ps_telphone")
            //->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')
            ->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.member_list_id = c.member_list_id')
            ->join('LEFT JOIN __MEMBER_LIST__ AS d ON a.ps_id = d.member_list_id')
            //->join('LEFT JOIN __MERCHANT__ AS e ON a.ma_id = e.ma_id')
            ->where(array('a.stall_id'=>$stall_id,'a.order_id'=>$order_id))
            ->find();
        //商品
        $goods_list = M('order_goods')
            ->field("dishes_name,pic_url,dishes_nums")
            ->where(array('order_id'=>$infos['order_id']))
            ->select();
        if($infos['order_status'] == 6){
            $comment_list = M('comment')
                ->field("dish_score,service_score,image,content,FROM_UNIXTIME(addtime,'%Y-%m-%d %H:%i') as addtime")
                ->where(array('order_id'=>$order_id,'stall_id'=>$stall_id,'is_del'=>2))
                ->find();
            if(!empty($comment_list)){
                if (!empty($comment_list['image'])){
                    $comment_list['image'] = explode(',',$comment_list['image']);
                }else{
                    $comment_list['image'] = array();
                }
            }
        }
        //超时
        $merchant = M('stall')->alias('a')
            ->field('timeout')
            ->join('LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')
            ->where(array('a.stall_id'=>$stall_id))
            ->find();
        if($infos['order_status'] == 3){
            $infos['timeout'] = date('Y-m-d H:i',$infos['success_time']+3600*$merchant['timeout']);
        }

        $Data = [
            'stall_name' => $stall_name,
            'infos' => $infos,
            'goods_list' => $goods_list,
            'comment_list' => $comment_list,
        ];

        $this->make_json_result("操作成功",$Data);
    }










}