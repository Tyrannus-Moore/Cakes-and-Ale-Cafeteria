<?php
// +----------------------------------------------------------------------
// | 功能:配送员管理
// +----------------------------------------------------------------------
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
use Think\Verify;
use Org\Util\Stringnew;
class MarkiController extends ShopAuthController
{
    // 人员管理
    public function markiList()
    {
        $where = array();
        $member_list_db = M('member_list');
        $ma_id = session('ma_id');
        $where['ma_id'] = $ma_id;
        $where['is_open'] = 1;
        $where['status'] = 2;
        $where['state'] = 2;
        $search = I('search');
        if($search){
            if($search['member_name']){
                $where['member_name'] = array('like','%'.trim($search['member_name']).'%');
            }
            if($search['telphone']){
                $where['telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['type']){
                $where['type'] = $search['type'];
            }
        }
        //分页
        $count= $member_list_db->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $member_list_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order("addtime DESC")->select();

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //人员详情
    public function markiDetail()
    {
        $member_list_id = I('member_list_id');
        $infos = M('member_list')->alias('a')
            ->field("a.*,b.name")
            ->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
            ->where(array('member_list_id'=>$member_list_id))
            ->find();

        $this->assign('infos',$infos);
        $this->assign('p',I('p',1));
        $this->display();
    }

    //评价列表
    public function evaluation()
    {
        $comment_db = M('comment');
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
        $count= $comment_db->alias('a')->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $comment_db->alias('a')
            ->field("a.marki_score,a.marki_content,a.addtime,b.member_list_nickname")
            ->join("LEFT JOIN __MEMBER_LIST__ as b on a.member_list_id=b.member_list_id")
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
        $orders_db = M('orders');
        $member_list_id = I('member_list_id');
        //验证
        $ret = $orders_db->where(array('order_type'=>1,'is_grab'=>2,'order_status'=>4,'ps_id'=>$member_list_id))->count();
        if (!empty($ret)){
            $this->error('有配送中订单，不允许删除该配送员！');
        }
        $orders_db->where(array('order_type'=>1,'order_status'=>3,'ps_id'=>$member_list_id))->setField('is_grab',1);
        $res = M('member_list')->where(array('member_list_id'=>$member_list_id))->setField('status',1);
        if($res !== false){
            $this->success('删除成功！',U("markiList",array('p'=>I('p',1))),1);
        }else{
            $this->success('删除失败！',U("markiList",array('p'=>I('p',1))),0);
        }
    }

    //认证申请
    public function applyList()
    {
        $where = array();
        $member_list_db = M('member_list');
        $ma_id = session('ma_id');
        $where['ma_id'] = $ma_id;
        $where['is_open'] = 1;
        $where['state'] = 1;
        $search = I('search');
        if($search){
            if($search['start_time']){
                $where['addtime'] = array('egt',strtotime($search['start_time']));
            }
            if($search['end_time']){
                $where['addtime'] = array('elt',strtotime($search['end_time']." 23:59:59"));
            }
            if($search['start_time'] && $search['end_time']){
                $where['addtime'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time']." 23:59:59")));
            }
            if($search['member_name']){
                $where['member_name'] = array('like','%'.trim($search['member_name']).'%');
            }
            if($search['telphone']){
                $where['telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['type']){
                $where['type'] = $search['type'];
            }
        }
        //分页
        $count= $member_list_db->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $member_list_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order("application_time DESC")->select();

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //审核通过
    public function audit_pass()
    {
        $member_list_id = I('member_list_id');
        $data = array();
        $data['status'] = 2;
        $data['state'] = 2;
        $data['audit_time'] = time();
        $res = M('member_list')->where(array('member_list_id'=>$member_list_id))->save($data);
        if($res !== false){
            if(C('IsSetMessages')){
                $shopData = M('merchant')->where(array('ma_id'=>session('ma_id')))->field("appid,appsecret,qs_through_id")->find();
                $memberData = M('member_list')->where(array('member_list_id'=>$member_list_id))->field("openid,member_name,application_time,telphone")->find();
                $info['type'] = 1;
                $info['appid'] = $shopData['appid'];
                $info['appsecret'] = $shopData['appsecret'];
                $info['template_id'] = $shopData['qs_through_id'];
                $info['openid'] = $memberData['openid'];
                $info['title'] = '您的骑手申请已通过！';
                $info['keyword1'] = $memberData['member_name'];
                //$info['keyword2'] = $memberData['application_time'];
                $info['keyword2'] = $memberData['telphone'];
                setMessages($info);
            }
            $this->success('审核成功',U('applyList',array('p'=>I('p',1))),1);
        }else{
            $this->error('审核失败',U('applyList',array('p'=>I('p',1))),0);
        }
    }

    //审核拒绝
    public function withB()
    {
        $member_list_id = I('member_list_id');
        $p = I('p');

        $this->assign("p",$p);
        $this->assign("member_list_id",$member_list_id);
        $this->display();
    }

    //驳回
    public function rejected()
    {
        $p = I('p');
        $member_list_id = I('member_list_id');
        $refusal_reason = I('refusal_reason');
        $data['state'] = 3;
        $data['audit_time'] = time();
        $data['refusal_reason'] = $refusal_reason;

        $res = M('member_list')->where(array('member_list_id'=>$member_list_id))->save($data);
        if($res){
            if(C('IsSetMessages')){
                $shopData = M('merchant')->where(array('ma_id'=>session('ma_id')))->field("appid,appsecret,qs_refused_id")->find();
                $memberData = M('member_list')->where(array('member_list_id'=>$member_list_id))->field("openid,member_name,application_time,refusal_reason,audit_time")->find();
                $info['type'] = 2;
                $info['appid'] = $shopData['appid'];
                $info['appsecret'] = $shopData['appsecret'];
                $info['template_id'] = $shopData['qs_refused_id'];
                $info['openid'] = $memberData['openid'];
                $info['title'] = '您的骑手申请已被拒绝！';
                $info['keyword1'] = '平台审核';
                $info['keyword2'] = $memberData['audit_time'];
                $info['keyword3'] = '不通过';
                $info['keyword4'] = $memberData['refusal_reason'];
                setMessages($info);
            }
            $this->success("已驳回！",U("applyList",array("p"=>$p)),1);
        }else{
            $this->error("操作失败！",U("applyList",array("p"=>$p)),0);
        }
    }





}