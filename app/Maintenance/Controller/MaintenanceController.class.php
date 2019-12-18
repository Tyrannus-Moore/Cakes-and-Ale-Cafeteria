<?php
// +----------------------------------------------------------------------
// | 功能:维护管理
// +----------------------------------------------------------------------
// | 作者:崔骏
// +----------------------------------------------------------------------
// | 时间:2019.1.18
// +----------------------------------------------------------------------
namespace Maintenance\Controller;
use Common\Controller\AuthController;
class MaintenanceController extends AuthController
{
    //帮助说明
    public function helpIllustrate()
    {
        if(IS_POST){
            $pass = M("baise")->where(array('type'=>"1"))->find();
            $data["baise_name"] = I("baise_name");
            $data["content"] = I("content");
            if($data["content"] == ''){
                $this->error('内容不能为空',1,2);
            }
            $data['addtime'] = time();
            if($pass){
                M("baise")->where(array('type'=>"1"))->save($data);
            }else{
                $data['type'] = 1;
                M("baise")->add($data);
            }
            $this->success('修改成功',U('helpIllustrate'),1);
        }else{
            $info = M("baise")->where(array('type'=>"1"))->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    //常见问题
    public function Problems()
    {
        $where = array();
        //搜索
        $search = I('search');
        if($search){
            if($search['baise_name']){
                $where['baise_name'] =  array('like','%'.trim($search['baise_name']).'%');
            }
            if($search['due_deadline']){
                $startTime = substr($search['due_deadline'],0,10);
                $endTime = substr($search['due_deadline'],13);
                if($startTime && $endTime){
                    $where['addtime'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
        }
        $where['type'] = 2;
        $count = M('baise')->where($where)->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $list = M('baise')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('search',$search);
        $this->display();
    }

    //常见问题添加
    public function problemsAdd()
    {
        if(IS_POST){
            $data["baise_name"] = I("baise_name");
            $data["content"] = I("content");
            if($data["content"] == ''){
                $this->error('内容不能为空',1,2);
            }
            $data['addtime'] = time();
            $data['type'] = 2;
            M("baise")->add($data);
            $this->success('添加成功',U('Problems'),1);
        }else{
            $baise_id = I('baise_id');
            $info = M("baise")->where(array('baise_id'=>$baise_id))->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    //常见问题编辑
    public function problemsEdit()
    {
        if(IS_POST){
            $baise_id = I('baise_id');
            $data["baise_name"] = I("baise_name");
            $data["content"] = I("content");
            if($data["content"] == ''){
                $this->error('内容不能为空',1,2);
            }
            $data['addtime'] = time();
            M("baise")->where(array('baise_id'=>$baise_id))->save($data);
            $this->success('修改成功',U('Problems',array('p'=>I('p',1))),1);
        }else{
            $baise_id = I('baise_id');
            $info = M("baise")->where(array('baise_id'=>$baise_id))->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    //常见问题删除
    public function problemsDel()
    {
        $baise_id = I('baise_id');
        M("baise")->where(array('baise_id'=>$baise_id))->delete();
        $this->success('删除成功',U('Problems',array('p'=>I('p',1))),1);
    }
}