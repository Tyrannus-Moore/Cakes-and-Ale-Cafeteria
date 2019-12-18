<?php
namespace Home\Controller;

use Think\Controller;
use Home\Controller\BaseController;

class IndexController extends BaseController
{
    public function errors()
    {
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('status');
        $this->assign('status',$status);
        $this->display();
    }
}