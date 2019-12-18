<?php
// +----------------------------------------------------------------------
// | 功能:普通订单管理
// +----------------------------------------------------------------------
// | 作者:裴国朝
// +----------------------------------------------------------------------
// | 时间:2019.1.19
// +----------------------------------------------------------------------
namespace Stall\Controller;
use Think\Controller;
use Stall\Controller\BaseController;

class CsorderController extends BaseController
{
    public function order()
    {
        $ma_id = $this->ma_id;
        $stall_id = $this->stall_id;
        $orders_db = M('orders');

        $count1 = $orders_db->alias('a')
            ->where(array('a.ma_id'=>$ma_id,'a.stall_id'=>$stall_id,'a.order_type'=>1,'a.order_status'=>2))
            ->count();
        $count2 = $orders_db->alias('a')
            ->where(array('a.ma_id'=>$ma_id,'a.stall_id'=>$stall_id,'a.order_type'=>1,'a.order_status'=>3))
            ->count();
        $count3 = $orders_db->alias('a')
            ->where(array('a.ma_id'=>$ma_id,'a.stall_id'=>$stall_id,'a.order_type'=>1,'a.order_status'=>array('in','4,5,6')))
            ->count();

        $this->assign('count1',$count1);
        $this->assign('count2',$count2);
        $this->assign('count3',$count3);
        $this->display();
    }
    public function orders()
    {
        $ma_id = $this->ma_id;
        $stall_id = $this->stall_id;
        $orders_db = M('orders');
        $order_goods_db = M('order_goods');
        $itemIndex = I('itemIndex');

        $psize = I('psize',10); //每页显示条数
        $page = I('page',1);  //当前页
        $offset = ($page - 1) * $psize;

        $where = array();
        $where['a.ma_id'] = $ma_id;//商家
        $where['a.stall_id'] = $stall_id;//档口
        $where['a.order_type'] = 1;//普通菜品
        if($itemIndex == 1){
            $where['a.order_status'] = 3;
        }else if($itemIndex == 2){
            $where['a.order_status'] = array('in','4,5,6');
        }else{
            $where['a.order_status'] = 2;
        }

        $list = $orders_db->alias('a')
            ->field("a.order_id,a.create_time,a.order_status,a.phone,a.deliver_type,a.telphone,a.success_time,a.order_no")
            //->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.member_list_id = c.member_list_id')
            ->where($where)
            ->limit($offset,$psize)
            ->order('create_time desc')
            ->select();
        foreach ($list as $key=>$value){
            $list[$key]['create_time'] = date('Y-m-d H:i',$value['create_time']);
            $list[$key]['goodlist'] = $order_goods_db->field("dishes_name,pic_url,dishes_nums")->where(array('order_id'=>$value['order_id']))->select();
            if($value['deliver_type'] == 2 AND $value['order_status'] == 3 AND time()-$value['success_time'] >= 3600*3){
                $list[$key]['qccs'] = 2;
            }
        }

        if (empty($list)){
            $data['code'] = 0;
        }else{
            $data['code'] = 1;
            $data['list'] = $list;
        }

        $this->ajaxReturn($data);
    }

    //备餐中
    public function order_bcz()
    {
        $ma_id = $this->ma_id;
        $stall_id = $this->stall_id;
        $orders_db = M('orders');
        $order_goods_db = M('order_goods');

        $list = $orders_db->field("order_id,create_time")->where(array('ma_id'=>$ma_id,'stall_id'=>$stall_id,'order_type'=>1,'order_status'=>2))->select();
        foreach ($list as $key=>$value){
            $list[$key]['create_time'] = date('Y-m-d H:i',$value['create_time']);
            $result = $order_goods_db->field("dishes_name,pic_url,dishes_nums")->where(array('order_id'=>$value['order_id']))->find();
            $list[$key]['goodlist'] = $result;
        }

        $topic = json_encode($list);
        echo $topic;
    }

    //备餐完成
    public function order_eef()
    {
        $order_id = I('order_id');
        $deliver_type = I('deliver_type');
        $data['order_status'] = 3;
        $data['success_time'] = time();
        if ($deliver_type == 1){
            $data['is_grab'] = 1;
        }

        $res = M('orders')->where(array('order_id'=>$order_id))->save($data);
        if ($res !== false){
            $this->make_json_result("备餐成功");
        }else{
            $this->make_json_error('操作失败！',1);
        }
    }

    //待取餐
    public function order_dqc()
    {
        $ma_id = $this->ma_id;
        $stall_id = $this->stall_id;
        $orders_db = M('orders');
        $order_goods_db = M('order_goods');

        $list = $orders_db->field("order_id,create_time")->where(array('ma_id'=>$ma_id,'stall_id'=>$stall_id,'order_type'=>1,'order_status'=>3))->select();
        foreach ($list as $key=>$value){
            $list[$key]['create_time'] = date('Y-m-d H:i',$value['create_time']);
            $result = $order_goods_db->field("dishes_name,pic_url,dishes_nums")->where(array('order_id'=>$value['order_id']))->find();
            $list[$key]['goodlist'] = $result;
        }
        dump($list);exit;
        $this->assign('list',$list);
        $this->display();
    }

    //取餐
    public function order_tf()
    {
        $orders_db = M('orders');
        $order_goods_db = M('order_goods');
        $order_id = I('order_id');
        $deliver_type = I('deliver_type');
        $meal_code = I('meal_code');

        $ret = $order_goods_db->field("meal_code")->where(array('order_id'=>$order_id))->find();
		
        if ($ret['meal_code'] == $meal_code){
            if ($deliver_type == 1){
                $data['order_status'] = 4;
            }else{
                $data['state'] = 1;
                $data['order_status'] = 5;

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
            }
            $data['take_time'] = time();
            $orders_db->where(array('order_id'=>$order_id))->save($data);
            $this->make_json_result("取餐成功",200);
        }else{
            $this->make_json_error('请输入正确的取餐码！',1);
        }
    }
    //取餐超时
    public function order_cs()
    {
        $orders_db = M('orders');
        $order_id = I('order_id');

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

            $this->make_json_result("操作成功");
        }else{
            $this->make_json_error('操作失败！',1);
        }
    }

    //已完成
    public function order_ywc()
    {
        $ma_id = $this->ma_id;
        $stall_id = $this->stall_id;
        $orders_db = M('orders');
        $order_goods_db = M('order_goods');

        $list = $orders_db
            ->field("order_id,create_time")
            ->where(array('ma_id'=>$ma_id,'stall_id'=>$stall_id,'order_type'=>1,'order_status'=>array('in','5,6')))
            ->select();
        foreach ($list as $key=>$value){
            $list[$key]['create_time'] = date('Y-m-d H:i',$value['create_time']);
            $result = $order_goods_db->field("dishes_name,pic_url,dishes_nums")->where(array('order_id'=>$value['order_id']))->find();
            $list[$key]['goodlist'] = $result;
        }
        dump($list);exit;
        $this->assign('list',$list);
        $this->display();
    }

    //详情
    public function order_xq()
    {
        $stall_id = $this->stall_id;
        $stall_info = M('stall')->where(array('stall_id'=>$stall_id))->find();
        $order_id = I('order_id');
        $infos = M('orders')->alias('a')
            ->field("a.*,
                c.member_list_nickname,
                c.member_list_headpic as yh_headpic,
                d.member_list_headpic as ps_headpic,
                d.member_name as ps_name,
                d.telphone as ps_telphone")
            //->join('LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')
            ->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.member_list_id = c.member_list_id')
            ->join('LEFT JOIN __MEMBER_LIST__ AS d ON a.ps_id = d.member_list_id')
            //->join('LEFT JOIN __MERCHANT__ AS e ON a.ma_id = e.ma_id')
            ->where(array('a.order_id'=>$order_id))
            ->find();
        $goods_list = M('order_goods')->where(array('order_id'=>$infos['order_id']))->select();
        if($infos['order_status'] == 6){
            $comment_list = M('comment')->where(array('is_del'=>2,'stall_id'=>$stall_id,'order_id'=>$order_id))->find();
            if (!empty($comment_list['image'])){
                $comment_list['image'] = explode(',',$comment_list['image']);
            }
        }

        $this->assign('infos',$infos);
        $this->assign('goods_list',$goods_list);
        $this->assign('stall_info',$stall_info);
        $this->assign('comment_list',$comment_list);
        $this->display();
    }










}