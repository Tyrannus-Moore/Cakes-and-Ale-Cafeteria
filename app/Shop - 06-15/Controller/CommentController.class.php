<?php
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
use Think\Verify;
use Org\Util\Stringnew;
class CommentController extends ShopAuthController
{
    // 评论列表
    public function commentList()
    {
        $where = array();
        $ma_id = session('ma_id');
        $where['a.ma_id'] = $ma_id;
        $where['a.is_del'] = 2;
        $search = I('search');
        if($search){
            if($search['order_no']){
                $where['b.order_no'] = array('like','%'.trim($search['order_no']).'%');
            }
            if($search['member_name']){
                $where['c.member_name'] = array('like','%'.trim($search['member_name']).'%');
            }
            if($search['telphone']){
                $where['c.telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['stall_name']){
                $where['d.stall_name'] = array('like','%'.trim($search['stall_name']).'%');
            }
            if($search['start_time']){
                $where['a.addtime'] = array('egt',strtotime($search['start_time']));
            }
            if($search['end_time']){
                $where['a.addtime'] = array('elt',strtotime($search['end_time']." 23:59:59"));
            }
            if($search['start_time'] && $search['end_time']){
                $where['a.addtime'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time']." 23:59:59")));
            }
        }
        //分页
        $count= M('comment as a')
            ->join("LEFT JOIN sm_orders as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_member_list as c on b.member_list_id = c.member_list_id")
            ->join("LEFT JOIN sm_stall as d on a.stall_id = d.stall_id")
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('comment as a')
            ->join("LEFT JOIN sm_orders as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_member_list as c on b.member_list_id = c.member_list_id")
            ->join("LEFT JOIN sm_stall as d on a.stall_id = d.stall_id")
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->field('a.comment_id,b.order_no,c.member_list_nickname as member_name,c.telphone,d.stall_name,a.dish_score,a.service_score,a.addtime')
            ->order("a.addtime DESC")
            ->select();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    // 详情
    public function comment(){
        $comment_id = I('comment_id');
        $infos = M('comment as a')
            ->join("LEFT JOIN sm_orders as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_member_list as c on b.member_list_id = c.member_list_id")
            ->join("LEFT JOIN sm_stall as d on a.stall_id = d.stall_id")
            ->where(array('comment_id'=>$comment_id))
            ->field('a.comment_id,b.order_no,c.member_list_nickname as member_name,c.telphone,d.stall_name,a.dish_score,a.service_score,a.addtime,a.content,a.image')
            ->find();
        $imgs = explode(',',$infos['image']);
        $this->assign('infos',$infos);
        $this->assign('imgs',$imgs);
        $this->display();
    }

    // 删除
    public function commentDel()
    {
        $comment_db = M('comment');
        $comment_id = I('comment_id');
        $res = $comment_db->where(array('comment_id'=>$comment_id))->setField('is_del',1);
        if($res !== false){
            $this->success('删除成功！',U("commentList"),1);
        }else{
            $this->success('删除失败！',U("commentList"),0);
        }
    }
}