<?php
// +----------------------------------------------------------------------
// |  配送员
// +----------------------------------------------------------------------
namespace Home\Controller;
use Home\Controller\BaseController;

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
        $orders_db = M('orders as a');
        //待抢单
        $sheets = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.is_grab'=>1,'a.order_type'=>1))->field('a.*,b.image,b.stall_name')->order('a.create_time desc')->select();
        $this->assign('sheets',$sheets);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('status');
        $this->assign('status',$status);
        $this->display();
    }
    //待取餐
    public function waitOrder(){
        $orders_db = M('orders as a');
        //订单列表
        $list = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','3'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>session('member_list_id')))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->select();
        $order_goods_db = M('order_goods');
        foreach ($list as $key => $value) {
            $list[$key]['meal_code'] = $order_goods_db->where(array('order_id'=>$value['order_id']))->limit(1)->getField('meal_code');
        }
        // dump($list);
        $this->assign('list',$list);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('status');
        $this->assign('status',$status);
        $this->display();
    }
    //配送中
    public function deliveryOrder(){
        $orders_db = M('orders as a');
        //订单列表
        $list = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','4'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>session('member_list_id')))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->select();
        $order_goods_db = M('order_goods');
        foreach ($list as $key => $value) {
            $list[$key]['meal_code'] = $order_goods_db->where(array('order_id'=>$value['order_id']))->limit(1)->getField('meal_code');
        }
        // dump($list);
        $this->assign('list',$list);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('status');
        $this->assign('status',$status);
        $this->display();
    }
    //已完成
    public function finishOrder(){
        $orders_db = M('orders as a');
        //订单列表
        $list = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_status'=>array('in','5,6'),'a.is_grab'=>2,'a.order_type'=>1,'ps_id'=>session('member_list_id')))->order('a.create_time desc')->field('a.*,b.image,b.stall_name')->select();
        $order_goods_db = M('order_goods');
        foreach ($list as $key => $value) {
            $list[$key]['meal_code'] = $order_goods_db->where(array('order_id'=>$value['order_id']))->limit(1)->getField('meal_code');
        }
        // dump($list);
        $this->assign('list',$list);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('status');
        $this->assign('status',$status);
        $this->display();
    }
    //抢单
    public function sheet(){
        $order_id = I('order_id');
        $data['ps_id'] = session('member_list_id');
        $data['order_status'] = 3;
        $data['is_grab'] = 2;
        //首先判断这个人是否还是配送员
        $is_status = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('status');
        if($is_status == 1){
            $this->ajaxReturn(4);
        }
        //判断该订单是否被抢
        $is_find = M('orders')->where(array('order_id'=>$order_id))->getField('is_grab');
        if($is_find == 2){
            $this->ajaxReturn(3);
        }
        $result = M('orders')->where(array('order_id'=>$order_id))->save($data);
        if($result){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(2);
        }
    }
    //任务详情
    public function taskDetail(){
        $orders_db = M('orders as a');
        $order_id = I('order_id');
        //订单信息
        $infos = $orders_db->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->join('LEFT JOIN __MERCHANT__ AS c ON b.ma_id = c.ma_id')->where(array('a.order_id'=>$order_id))->field('a.*,b.image,b.stall_name,b.stall_tel,c.address as stall_address')->find();
        $this->assign('infos',$infos);
        $order_goods_db = M('order_goods');
        //订单商品信息
        $list = $order_goods_db->where(array('order_id'=>$order_id))->select();
        $this->assign('list',$list);
        //查看此人的兼职类型
        $type = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('type');
        $this->assign('type',$type);
        //取餐超时时间
        $dd = M('stall')->where(array('a.stall_id'=>$infos['stall_id']))
            ->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->getField("b.timeout");
        // var_dump($dd);
        $dt=date('Y-m-d H:i:s',$infos['success_time']);
        $dd = $dd*60;
        $endTimes = date("Y-m-d H:i:s",strtotime("$dt   +".$dd."   minute"));
        $this->assign("endTimes",$endTimes);
        $this->display();
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
}