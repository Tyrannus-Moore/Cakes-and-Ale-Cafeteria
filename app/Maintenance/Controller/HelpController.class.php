<?php
// +----------------------------------------------------------------------
// | 功能:维护管理
// +----------------------------------------------------------------------
namespace Maintenance\Controller;
use Common\Controller\AuthController;
class HelpController extends AuthController
{
    //分类
    public function class_list()
    {
        $news_category_db = M('news_category');
        $count = $news_category_db->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $list = $news_category_db->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    //添加分类
    public function class_add()
    {
        if (IS_AJAX){
            $cate_name = I('cate_name');

            $data['cate_name'] = $cate_name;
            $data['addtime'] = time();

            $res = M('news_category')->add($data);
            $this->success('添加成功',U('class_list'),1);
        }else{
            $this->display();
        }
    }
    //编辑分类
    public function class_edit()
    {
        $new_cate_id = I('new_cate_id');

        $infos = M('news_category')->where(array('new_cate_id'=>$new_cate_id))->find();
        $infos['status'] = 1;

        $this->ajaxReturn($infos,'json');
    }
    //提交编辑
    public function class_edit_submit()
    {
        $new_cate_id = I('new_cate_id');
        $cate_name = I('cate_name');

        $data['cate_name'] = $cate_name;
        $data['addtime'] = time();

        $res = M('news_category')->where(array('new_cate_id'=>$new_cate_id))->save($data);
        if($res!==false){
            $this->success('修改成功',U('class_list'),1);
        }else{
            $this->error('修改失败',U('class_list'),0);
        }
    }
    //删除分类
    public function class_del()
    {
        $new_cate_id = I('new_cate_id');
        $ret = M('news_list')->where(array('new_cate_id'=>$new_cate_id))->find();
        if($ret){
            $this->error('请先删除分类下文章！');
        }

        $res = M('news_category')->where(array('new_cate_id'=>$new_cate_id))->delete();
        if($res!==false){
            $this->success('删除成功',U('class_list'),1);
        }else{
            $this->error('删除失败',U('class_list'),0);
        }
    }

    //文章列表
    public function help_list()
    {
        $news_list_db = M('news_list');
        $count = $news_list_db->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $list = $news_list_db->limit($Page->firstRow.','.$Page->listRows)->order('addtime DESC')->select();

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    //添加文章
    public function help_add()
    {
        if(IS_AJAX){
            $new_cate_id = I('new_cate_id');
            $help_name = I('help_name');
            $help_content = I('help_content');

            $data = [
                'new_cate_id' => $new_cate_id,
                'help_name' => $help_name,
                'help_content' => $help_content,
                'addtime' => time(),
            ];

            $res = M('news_list')->add($data);
            if($res !== false){
                $this->success('添加成功',U('help_list'),1);
            }else{
                $this->error('添加失败',U('help_list'),0);
            }
        }else{
            $list = M('news_category')->order('addtime DESC')->select();

            $this->assign('list',$list);
            $this->display();
        }
    }
    //编辑文章
    public function help_edit()
    {
        if(IS_AJAX){
            $help_id = I('help_id');
            $new_cate_id = I('new_cate_id');
            $help_name = I('help_name');
            $help_content = I('help_content');

            $data = [
                'new_cate_id' => $new_cate_id,
                'help_name' => $help_name,
                'help_content' => $help_content,
                'addtime' => time(),
            ];

            $res = M('news_list')->where(array('help_id'=>$help_id))->save($data);
            if($res !== false){
                $this->success('编辑成功',U('help_list',array('p'=>I('p',1))),1);
            }else{
                $this->error('编辑失败',U('help_list',array('p'=>I('p',1))),0);
            }
        }else{
            $help_id = I('help_id');
            $infos = M('news_list')->where(array('help_id'=>$help_id))->find();
            $list = M('news_category')->order('addtime DESC')->select();

            $this->assign('list',$list);
            $this->assign('infos',$infos);
            $this->assign('p',I('p',1));
            $this->display();
        }
    }
    //删除文章
    public function help_del()
    {
        $help_id = I('help_id');

        $res = M('news_list')->where(array('help_id'=>$help_id))->delete();
        if($res !== false){
            $this->success('删除成功',U('help_list',array('p'=>I('p',1))),1);
        }else{
            $this->error('删除失败',U('help_list',array('p'=>I('p',1))),0);
        }
    }





}