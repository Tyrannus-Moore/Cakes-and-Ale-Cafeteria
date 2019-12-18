<?php
// +----------------------------------------------------------------------
// | 功能:人员管理
// +----------------------------------------------------------------------
namespace Marki\Controller;
use Common\Controller\AuthController;;
class MarkiController extends AuthController
{
    // 人员管理
    public function markiList()
    {
        $where = array();
        $where['a.is_open'] = 1;
        $where['a.status'] = 2;
        $where['a.state'] = 2;
        $search = I('search');
        if($search){
            if($search['member_name']){
                $where['a.member_name'] = array('like','%'.trim($search['member_name']).'%');
            }
            if($search['telphone']){
                $where['a.telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['shopname']){
                $where['b.ma_merchantname'] =  array('like','%'.trim($search['shopname']).'%');
            }
            if($search['type']){
                $where['a.type'] = $search['type'];
            }
        }
        //分页
        $count= M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->where($where)
            ->field("a.*,b.ma_merchantname as shopname")
            ->limit($Page->firstRow.','.$Page->listRows)->order("a.addtime DESC")->select();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //人员详情
    public function markiDetail()
    {
        $member_list_id = I('member_list_id');
        $infos = M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->join("LEFT JOIN __SCHOOL__ as c on a.school_id = c.school_id")
            ->where(array('a.member_list_id'=>$member_list_id))
            ->field("a.*,b.ma_merchantname as shopname,c.name")
            ->find();
        $this->assign('infos',$infos);
        $this->display();
    }

    //评价列表
    public function evaluation()
    {
        $member_list_id = I('member_list_id');
        $where = array();
        $where['a.is_del'] = 2;
        $where['a.member_list_id'] = $member_list_id;
        $search = I('search');
        if($search){
            if($search['score']){
                $where['a.marki_score'] = $search['score'];
            }
        }
        //分页
        $count= M('comment')->join("AS a LEFT JOIN __MEMBER_LIST__ as b on a.member_list_id=b.member_list_id")->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('comment')->join("AS a LEFT JOIN __MEMBER_LIST__ as b on a.member_list_id=b.member_list_id")
            ->field("a.marki_score,a.marki_content,a.addtime,b.member_list_nickname")
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order('a.addtime DESC')
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->assign('member_list_id',$member_list_id);
        $this->display();
    }

    //删除配送员
    public function markiDel()
    {
        $member_list_id = I('member_list_id');
        $passOrder = M('orders')->where(array('ps_id'=>$member_list_id,'order_status'=>4))->find();
        if($passOrder){
            $this->error('该配送员有配送中的订单不允许删除！',U("markiList",array('p'=>I('p',1))),0);
        }
        $res = M('member_list')->where(array('member_list_id'=>$member_list_id))->setField('status',1);
        if($res !== false){
            M('orders')->where(array('ps_id'=>$member_list_id,'order_status'=>3,'is_grab'=>2))->setField("is_grab",1);
            $this->success('删除成功！',U("markiList",array('p'=>I('p',1))),1);
        }else{
            $this->error('删除失败！',U("markiList",array('p'=>I('p',1))),0);
        }
    }

    //认证申请
    public function applyList()
    {
        $where = array();
        $where['a.is_open'] = 1;
        $where['a.state'] = 1;
        $search = I('search');
        if($search){
            if($search['member_name']){
                $where['a.member_name'] = array('like','%'.trim($search['member_name']).'%');
            }
            if($search['telphone']){
                $where['a.telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['shopname']){
                $where['b.ma_merchantname'] =  array('like','%'.trim($search['shopname']).'%');
            }
            if($search['type']){
                $where['a.type'] = $search['type'];
            }
            if($search['due_deadline']){
                $startTime = substr($search['due_deadline'],0,10);
                $endTime = substr($search['due_deadline'],13);
                if($startTime && $endTime){
                    $where['a.application_time'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
        }
        //分页
        $count= M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->where($where)
            ->field("a.*,b.ma_merchantname as shopname")
            ->limit($Page->firstRow.','.$Page->listRows)->order("a.application_time DESC")->select();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    // 认证详情
    public function applyDetail()
    {
        $member_list_id = I('member_list_id');
        $infos = M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->join("LEFT JOIN __SCHOOL__ as c on a.school_id = c.school_id")
            ->where(array('a.member_list_id'=>$member_list_id))
            ->field("a.*,b.ma_merchantname as shopname,c.name")
            ->find();
        $this->assign('infos',$infos);
        $this->display();
    }
}