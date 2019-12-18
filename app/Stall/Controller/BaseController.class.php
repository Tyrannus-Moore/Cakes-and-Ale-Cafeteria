<?php
// +----------------------------------------------------------------------
// | 功能:登陆基础验证
// +----------------------------------------------------------------------
// | 作者:Z
// +----------------------------------------------------------------------
// | 时间:2018.4.17
// +----------------------------------------------------------------------
namespace Stall\Controller;
use Common\Controller\CommonController;
use Think\Controller;
class BaseController extends Controller
{
    public $stall_id;
    public $ma_id;
    //验证用户是否登录
    protected function _initialize(){
        //$this -> error('请您登陆app');
        //parent::_initialize();
        $this->stall_id = session('stall_id');
        $this->ma_id = session('ma_id');
        //$this->stall_id = 1;
        //$this->ma_id = 1;
        if(!$this->stall_id) {
            $ma_id = $this->ma_id;
            header('Location: '.U('Stall/Index/index',array('ma_id'=>$ma_id)));
            return false;
        }else{
            $where['stall_id'] = $this->stall_id;
            $where['order_status'] = 2;
            $where['refund'] = array('neq',1);
            $startTime = date("Y-m-d",time());
            $where['effect_time'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($startTime."23:59:59")));
            $res = M('orders')->where($where)->field("order_id")->select();
            if($res){
                /*foreach($res as $key=>$value){
                    $pass = M('orders')->where(array('order_id'=>$value['order_id']))->setField("order_status",4);
                    if($pass){
                        M('order_goods')->where(array('order_id'=>$value['order_id']))->setField("state",1);
                    }
                }*/
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
        $pin['FROM_UNIXTIME(addtime,"%Y-%m")'] = date('Y-m',time());
        $the_pin = M('the_pin_log')->where($pin)->find();
        if(empty($the_pin)){
            $Model = new \Think\Model();
            $dishes_db = M('dishes');
            $stall_db = M('stall');
            //时间
            $time = time();
            $starttime = strtotime(date('Y-m-d',strtotime(date("Y-m",strtotime("last month"))))." 00:00:00");
            $endtime = strtotime(date('Y-m-d',strtotime(date("Y-m",$time)))." 00:00:00");
            //统计菜品上月月销
            $sql1 = "SELECT a.dishes_id,d.on_the_pin FROM sm_dishes as a LEFT JOIN
                    (SELECT c.dishes_id,SUM(c.dishes_nums)as on_the_pin FROM sm_orders as b
                    LEFT JOIN sm_order_goods c
                    on b.order_id=c.order_id
                    WHERE b.order_type=1 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
                    GROUP BY dishes_id)
                    d on a.dishes_id=d.dishes_id
                    where a.is_del=2 AND a.type=1";
            $list1 = $Model->query($sql1);
            foreach ($list1 as $key1=>$value1){
                $dishes_db->save($value1);
            }
            $sql2 = "SELECT a.dishes_id,d.on_the_pin FROM sm_dishes as a LEFT JOIN
                    (SELECT b.dishes_id,count(*) as on_the_pin FROM sm_orders as b 
                    WHERE b.order_type=2 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
                    GROUP BY dishes_id)
                    d on a.dishes_id=d.dishes_id
                    where a.is_del=2 AND a.type=2";
            $list2 = $Model->query($sql2);
            foreach ($list2 as $key2=>$value2){
                $dishes_db->save($value2);
            }
            //统计档口上月月销
            $sql3 = "SELECT a.stall_id,d.on_the_pin FROM sm_stall as a LEFT JOIN
                    (SELECT b.stall_id,SUM(c.dishes_nums)as on_the_pin FROM sm_orders as b
                    LEFT JOIN sm_order_goods c
                    on b.order_id=c.order_id
                    WHERE b.order_type=1 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
                    GROUP BY stall_id)
                    d on a.stall_id=d.stall_id
                    where a.delete=2 AND a.stall_type=1";
            $list3 = $Model->query($sql3);
            foreach ($list3 as $key3=>$value3){
                $stall_db->save($value3);
            }
            $sql4 = "SELECT a.stall_id,d.on_the_pin FROM sm_stall as a LEFT JOIN
                    (SELECT b.stall_id,count(*) as on_the_pin FROM sm_orders as b 
                    WHERE b.order_type=2 AND b.order_status IN(5,6) AND b.create_time >=".$starttime." and b.create_time<=".$endtime."
                    GROUP BY stall_id)
                    d on a.stall_id=d.stall_id
                    where a.delete=2 AND a.stall_type=2";
            $list4 = $Model->query($sql4);
            foreach ($list4 as $key4=>$value4){
                $stall_db->save($value4);
            }
            M('the_pin_log')->add(array('addtime'=>time()));
        }
    }
}