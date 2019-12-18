<?php
namespace Small\Controller;

use Think\Controller;
use Small\Controller\BaseController;

class IndexController extends BaseController
{
    public function errors()
    {
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_id = $sessions['member_list_id'];

        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>$member_id,'is_del'=>2))->getField('status');

        $data['code'] = 200;
        $data['msg'] = "获取成功";
        $data['data']['status'] = $status;

        $this->ajaxReturn($data);
    }
}