<?php
namespace Home\Controller;

use Think\Controller;

class TestController
{
    public function get_address(){
        $ma_id = 1;
        $member_list_id = $_SESSION['member_list_id'];

        $add_list = M('user_address')->field("id,title,pid")->where(array('status'=>1,'ma_id'=>$ma_id))->select();

        $new_lis = [];
        foreach ($add_list as $key=>$val){
            $new_list[$key]['pid'] = $val['pid'];
            $new_list[$key]['value'] = $val['id'];
            $new_list[$key]['text'] = $val['title'];
        }

        print_r($new_list);

        $list = $this->Tree($new_list);
        if ($list) {
            $data['code'] = 200;
            $data['msg'] = '获取成功';
            $data['data'] = $list;
        } else {
            $data['code'] = 100;
            $data['msg'] = '暂无数据';
        }
        echo json_encode($data);
    }


    //递归调用--树处理
    public function Tree($arr,$pid=0){
        $res = [];//定义一个空数组
        foreach($arr as $v){
            if($v['pid'] == $pid){
                $data = $this->Tree($arr,$v['value']);
                if (!empty($data)) {
                    $v['children'] = $data;
                }
                $res[] = $v;
            }
        }
        return $res;
    }




}