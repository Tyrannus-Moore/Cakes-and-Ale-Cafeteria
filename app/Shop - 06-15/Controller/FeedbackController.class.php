<?php
// +----------------------------------------------------------------------
// | 功能:用户管理
// +----------------------------------------------------------------------
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
use Think\Verify;
use Org\Util\Stringnew;
class FeedbackController extends ShopAuthController
{
    // 反馈列表
    public function feedbackList()
    {
        $where = array();
        $feedback_db = M('feedback');
        $ma_id = session('ma_id');
        $where['a.ma_id'] = $ma_id;
        $search = I('search');
        if($search){
            if($search['start_time']){
                $where['a.addtime'] = array('egt',strtotime($search['start_time']));
            }
            if($search['end_time']){
                $where['a.addtime'] = array('elt',strtotime($search['end_time']." 23:59:59"));
            }
            if($search['start_time'] && $search['end_time']){
                $where['a.addtime'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time']." 23:59:59")));
            }
            if($search['member_list_nickname']){
                $where['b.member_list_nickname'] = array('like','%'.trim($search['member_list_nickname']).'%');
            }
            if($search['telphone']){
                $where['a.phone'] = array('like','%'.trim($search['telphone']).'%');
            }
        }
        //分页
        $count= $feedback_db->alias('a')
            ->join('LEFT JOIN __MEMBER_LIST__ AS b ON a.member_list_id = b.member_list_id')
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $feedback_db->alias('a')
            ->field("a.*,b.member_list_nickname")
            ->join('LEFT JOIN __MEMBER_LIST__ AS b ON a.member_list_id = b.member_list_id')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order("a.addtime DESC")
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //详情
    public function feedbackDetail()
    {
        $feedback_id = I('feedback_id');
        $infos = M('feedback')->alias('a')
            ->field("a.*,b.member_list_nickname")
            ->join('LEFT JOIN __MEMBER_LIST__ AS b ON a.member_list_id = b.member_list_id')
            ->where(array('feedback_id'=>$feedback_id))
            ->find();

        $this->assign('infos',$infos);
        $this->display();
    }


}