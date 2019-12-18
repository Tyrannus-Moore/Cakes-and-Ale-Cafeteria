<?php
// +----------------------------------------------------------------------
// | 功能:周餐订单管理
// +----------------------------------------------------------------------
// | 作者:崔骏
// +----------------------------------------------------------------------
// | 时间:2019.1.19
// +----------------------------------------------------------------------
namespace Stall\Controller;

class TcorderController extends BaseController
{
    //周餐订单列表页
    public function index()
    {
        $ma_id = $this->ma_id;
        $stall_id = $this->stall_id;
        $where['ma_id'] = $ma_id;
        $where['stall_id'] = $stall_id;
        $where['order_type'] = 2;
        $where['order_status'] = 4;
        $list = M('orders')->where($where)->field("order_id,stall_tel,telphone,phone,real_money,dishes_id,create_time,dishes_name,dishes_url")->order("create_time desc")->select();
        foreach($list as $key=>$value){
            $list[$key]['create_time'] = date('Y-m-d H:i',$value['create_time']);
            $pass = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>1))->field("type,day")->order("id asc")->find();
            if(empty($pass)){
                $list[$key]['passNo'] = 1;
            }else{
                $list[$key]['diningType'] = $pass['type'];
                $list[$key]['weekType'] = $pass['day'];
                $list[$key]['goodsData'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
            }
            $passTwo = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>2))->field("type,day,eat_finish_time")->order("id asc")->find();
            if(empty($passTwo)){
                $list[$key]['passNoTwo'] = 1;
            }else{
                $dt=date('Y-m-d H:i:s',$passTwo['eat_finish_time']);
                $endTime = strtotime("$dt+3hour");
                if($endTime<time()){
                    $list[$key]['endStatus'] = 1;
                }else{
                    $list[$key]['endStatus'] = 2;
                }
                $list[$key]['diningTypeTwo'] = $passTwo['type'];
                $list[$key]['weekTypeTwo'] = $passTwo['day'];
                $list[$key]['goodsDataTwo'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>2,'type'=>$passTwo['type'],'day'=>$passTwo['day']))->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
            }
        }
        $map['a.ma_id'] = $ma_id;
        $map['a.stall_id'] = $stall_id;
        $map['a.order_type'] = 2;
        $map['a.order_status'] = array('in','5,6,7');
        $finishDatas = M('orders')->alias('a')->field("a.order_id,a.order_status,a.dishes_url,a.dishes_name,a.real_money,FROM_UNIXTIME(a.create_time,'%Y-%m-%d %H:%i') as create_time")->where($map)->select();
        $this->assign('finishDatas',$finishDatas);
        $this->assign('list',$list);
        $this->display();
    }

    //备餐完成
    public function finishOrder()
    {
        $order_id = I('id');
        $pass = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1))->field("type,day")->order("id asc")->find();
        $res = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))->save(array('state'=>2,'eat_finish_time'=>time()));
        if($res !== false){
            $this->success('备餐成功！',U('index'),1);
        }else{
            $this->error('备餐失败！',1,2);
        }
    }

    //取餐
    public function takeFood()
    {
        $id = I('orderIds');
        $number1 = I('number1');
        $number2 = I('number2');
        $number3 = I('number3');
        $number4 = I('number4');
        //dump($number1);dump($number2);dump($number3);dump($number4);
        if($number1 == '' || $number2 == '' || $number3 == '' || $number4 == ''){
            $this->error('请填写四位有效数字！',1,2);
        }
        $number = $number1.$number2.$number3.$number4;
        $pass = M('order_goods')->where(array('order_id'=>$id,'state'=>2))->field("type,day,meal_code")->order("id asc")->find();
        if($pass['meal_code'] == $number){
            $res = M('order_goods')->where(array('order_id'=>$id,'state'=>2,'type'=>$pass['type'],'day'=>$pass['day']))->save(array('state'=>3,'end_status'=>1));
            if($res !== false){
                $passTure = M('order_goods')->where(array('order_id'=>$id,'state'=>1))->order("id asc")->find();
                if(empty($passTure)){
                    M('orders')->where(array('order_id'=>$id))->setField("order_status",5);
                    $pass = M('orders')->where(array('order_id'=>$id))->field("order_no,ma_id,stall_id,real_money,payment_time")->find();
                    $f['order_no'] = $pass['order_no'];
                    $f['ma_id'] = $pass['ma_id'];
                    $f['stall_id'] = $pass['stall_id'];
                    $f['money'] = $pass['real_money'];
                    $f['state'] = 1;
                    $f['type'] = 1;
                    $f['statue'] = 1;
                    $f['creattime'] = $pass['payment_time'];
                    M('finance')->add($f);
                }
                $this->success('取餐成功！',U('index'),1);
            }else{
                $this->error('取餐失败！',1,2);
            }
        }else{
            $this->error('请输入正确的取餐码！',1,2);
        }
    }

    //取餐超时完成
    public function TakeFoodTimeout()
    {
        $id = I('id');
        $pass = M('order_goods')->where(array('order_id'=>$id,'state'=>2))->field("type,day,meal_code")->order("id asc")->find();
        $res = M('order_goods')->where(array('order_id'=>$id,'state'=>2,'type'=>$pass['type'],'day'=>$pass['day']))->save(array('state'=>3,'end_status'=>2));
        if($res !== false){
            $passTure = M('order_goods')->where(array('order_id'=>$id,'state'=>1))->order("id asc")->find();
            if(empty($passTure)){
                M('orders')->where(array('order_id'=>$id))->setField("order_status",5);
                $pass = M('orders')->where(array('order_id'=>$id))->field("order_no,ma_id,stall_id,real_money,payment_time")->find();
                $f['order_no'] = $pass['order_no'];
                $f['ma_id'] = $pass['ma_id'];
                $f['stall_id'] = $pass['stall_id'];
                $f['money'] = $pass['real_money'];
                $f['state'] = 1;
                $f['type'] = 1;
                $f['statue'] = 1;
                $f['creattime'] = $pass['payment_time'];
                M('finance')->add($f);
            }
            $this->success('操作成功！',U('index'),1);
        }else{
            $this->error('操作失败！',1,2);
        }
    }

    //订单详情
    public function orderDetails()
    {
        $order_id = I('order_id');
        $state = I('state');
        $list = M('orders')->where(array('order_id'=>$order_id))->find();
        $dang_id = M('dishes')->where(array('dishes_id'=>$list['dishes_id']))->getField("stall");
        $stall_name = M('stall')->where(array('stall_id'=>$dang_id))->getField("stall_name");
        //$list['stall_name'] = M('')
        //preg_match('/([\d]{3})([\d]{4})([\d]{4})/', $list['stall_tel'],$match);
        //unset($match[0]);
        //$list['stall_tel'] = implode(' ', $match);
        $list['startTime'] = $starTime = date("Y-m-d",$list['effect_time']);
        $list['endTime'] = date('Y-m-d',strtotime("$starTime+1week"));
        $list['endStatus'] = 2;
        if($state == 4){
            $pass = M('order_goods')->where(array('order_id'=>$order_id))->field("type,day")->order("id asc")->find();
            $list['diningType'] = $pass['type'];
            $list['weekType'] = $pass['day'];
            $goodsData = M('order_goods')->where(array('order_id'=>$order_id,'type'=>$pass['type'],'day'=>$pass['day']))->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
        }else{
            $pass = M('order_goods')->where(array('order_id'=>$order_id,'state'=>$state))->field("type,day,eat_finish_time")->order("id asc")->find();
            if($state == 2){
                $dt=date('Y-m-d H:i:s',$pass['eat_finish_time']);
                $endTime = strtotime("$dt+3hour");
                if($endTime<time()){
                    $list['endStatus'] = 1;
                }
            }
            $list['diningType'] = $pass['type'];
            $list['weekType'] = $pass['day'];
            $goodsData = M('order_goods')->where(array('order_id'=>$order_id,'state'=>$state,'type'=>$pass['type'],'day'=>$pass['day']))->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
        }

        $this->assign('stall_name',$stall_name);
        $this->assign('list',$list);
        $this->assign('state',$state);
        $this->assign('goodsData',$goodsData);
        $this->display();
    }

    //周餐小订单商品
    public function NextOrder()
    {
        $orderId    = I('orderId');
        $diningType = I('diningType');
        $weekType   = I('weekType');
        $list = M('order_goods')->where(array('order_id'=>$orderId,'type'=>$diningType,'day'=>$weekType))->field("id,dishes_name,state,dishes_nums,dishes_price")->order("id asc")->select();
        if($list){
            $this->ajaxReturn($list);
        }else{
            $this->ajaxReturn(404);
        }
    }

}