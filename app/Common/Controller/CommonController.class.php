<?php
namespace Common\Controller;
use Think\Controller;
class CommonController extends Controller{
	protected function _initialize(){
		if (!file_exists('./data/install.lock')){
            //不存在，则进入安装
           // header('Location: ' . U('Install/index/index'));
            exit();
        }

        //返回登录前页面
		if(preg_match('/(Login)|(Register)/i', $_SERVER['HTTP_REFERER'])===0){
    		$_SESSION['gogo']= $_SERVER['HTTP_REFERER'];
    	}

		//网站信息
		$where['aid']=array('in','1,17,7,36,37,38,40,41,42,43,44,');
		$sysinof = M('sysconfig')->where($where)->select();
		$sys='';
		foreach ($sysinof as $value){
			$sys[$value['varname']] = $value['value'];
		}
		$this->assign('sysinof',$sys);

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
    //空操作
    public function _empty(){
        $this->error('此操作无效');
    }
}