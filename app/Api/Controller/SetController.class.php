<?php
namespace Api\Controller;
use Common\Controller\CommonController;
use Think\Controller;
class SetController extends Controller
{

// 打烊-开启 设置
    public function stallDY(){
        $stall_id = I('stall_id');
        if($stall_id){
            $is_freeze = M('stall')->where(array('stall_id'=>$stall_id))->getField('is_freeze');
            if($is_freeze == 2){
                M('stall')->where(array('stall_id'=>$stall_id))->setField('is_freeze',1);
                M('member_collection')->where(array('stall_id'=>$stall_id))->delete();
                M('cart')->where(array('stall_id'=>$stall_id))->delete();
                $this->make_json_result("冻结档口成功！",array('is_freeze' => 1));
            }else{
                M('stall')->where(array('stall_id'=>$stall_id))->setField('is_freeze',2);
                $this->make_json_result("解封档口成功！",array('is_freeze' => 2));
            }
        }else{
            $this->make_json_error('档口id不会为空',1);
        }
        
    }

// 自动接单开启-关闭 设置
    public function stallAutomatic(){
        $stall_id = I('stall_id');
        if($stall_id){
            $automatic = M('stall')->where(array('stall_id'=>$stall_id))->getField("automatic");
            if($automatic == 2){
                M('stall')->where(array('stall_id'=>$stall_id))->setField('automatic',1);
                $this->make_json_result("设置手动接单成功！",array('automatic' => 1));
            }else{
                M('stall')->where(array('stall_id'=>$stall_id))->setField('automatic',2);
                $this->make_json_result("设置自动接单成功！",array('automatic' => 2));
            }
        }else{
            $this->make_json_error('档口id不会为空',1);
        }
    }


    // 返回状态
    public function returnState(){
        $stall_id = I('stall_id');
        if($stall_id){
            $info = M('stall')->where(array('stall_id'=>$stall_id))->find();
            $this->make_json_result("返回成功",array('is_freeze'=>$info['is_freeze'],'automatic'=>$info['automatic']));
        }else{
            $this->make_json_error('档口id不会为空',1);
        }
    }
}