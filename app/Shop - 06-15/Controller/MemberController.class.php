<?php
// +----------------------------------------------------------------------
// | 功能:用户管理
// +----------------------------------------------------------------------
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
use Think\Verify;
use Org\Util\Stringnew;
class MemberController extends ShopAuthController
{
    // 用户列表
    public function memberList()
    {
        $where = array();
        $member_list_db = M('member_list');
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
                $where['a.member_list_nickname'] = array('like','%'.trim($search['member_list_nickname']).'%');
            }
            if($search['telphone']){
                $where['a.telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['is_open']){
                $where['a.is_open'] = $search['is_open'];
            }
        }
        //分页
        $count= $member_list_db->alias('a')->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $member_list_db->alias('a')
            ->field("a.*,b.name")
            ->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order("a.addtime DESC")
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //编辑用户
    public function memberEdit()
    {
        $member_list_db = M('member_list');
        if (IS_AJAX){
            $ma_id = session('ma_id');
            $member_list_id = I('member_list_id');
            $telphone = I('telphone');//手机号
            $id_card = I('id_card');//身份证号
            //验证
            $ret1 = $member_list_db->where(array('ma_id'=>$ma_id,'member_list_id'=>array('neq',$member_list_id),'telphone'=>$telphone))->count();
            if ($ret1){
                $this->error('手机号已存在！');
            }
            /*$ret2 = $member_list_db->where(array('ma_id'=>$ma_id,'member_list_id'=>array('neq',$member_list_id),'id_card'=>$id_card))->count();
            if ($ret2){
                $this->error('身份证号已存在！');
            }*/
            //图片
            if($_FILES){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
                $upload->savePath  =     'Member/'; // 设置附件上传（子）目录
                $upload->saveRule  =     'time';
                //$upload->autoSub = false;
                $info   =   $upload->upload();
                if($info) {
                    foreach($info as $file){
                        if ($file['key']=='img'){//单图路径数组
                            $data["member_list_headpic"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
                        }
                        /*if ($file['key']=='card_zheng'){//单图路径数组
                            $data["card_zheng"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
                        }
                        if ($file['key']=='card_fan'){//单图路径数组
                            $data["card_fan"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
                        }*/
                    }
                }
            }
            //院系
            $faculty = I('faculty');
            $infos = M('faculty')->where(array('faculty_id'=>$faculty))->find();
            $data['faculty'] = $infos['faculty_name'];//院系
            $data['member_class'] = I('member_class');//班级

            $data['member_list_nickname'] = I('member_list_nickname');//昵称
            $data['member_list_sex'] = I('member_list_sex');//性别
            $data['stature'] = I('stature');//身高
            $data['weight'] = I('weight');//体重
            $data['telphone'] = I('telphone');//手机号
            //$data['id_card'] = $id_card;//身份证号
            $data['birthary_time'] = strtotime(I('birthary_time'));//出生日期

            $res = $member_list_db->where("member_list_id='$member_list_id'")->save($data);
            if ($res !== false){
                $this->success('编辑成功！',U('memberList',array('p'=>I('p',1))),1);
            }else{
                $this->error('没有任何修改！');
            }
        }else{
            $member_list_id = I('member_list_id');
            $infos = $member_list_db->alias('a')
                ->field("a.*,b.name")
                ->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
                ->where(array('member_list_id'=>$member_list_id))
                ->find();
            //院系
            $faculty = M('faculty')->where(array('school_id'=>$infos['school_id']))->select();
            $member_class = M('faculty')->where(array('faculty_name'=>$infos['faculty']))->getField('num');

            $this->assign('infos',$infos);
            $this->assign('faculty',$faculty);
            $this->assign('member_class',$member_class);
            $this->assign('p',I('p',1));
            $this->display();
        }
    }

    //获取二级分类名称
    public function faculty()
    {
        $faculty_id = I('id');
        $infos = M('faculty')->where(array('faculty_id'=>$faculty_id))->find();
        $str = '';
        for ($i=1; $i<=$infos['num']; $i++) {
            $str .= "<option value='".$i."班'>".$i."班</option>";
        }
        $this->ajaxReturn($str);
    }

    //用户详情
    public function memberDetail()
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

    // 冻结账号
    public function memberDong()
    {
        $member_list_id = I('member_list_id');
        M('member_list')->where(array('member_list_id'=>$member_list_id))->setField('is_open',2);
        $this->success("冻结用户成功！",U("memberList",array('p'=>I('p',1))),1);
    }

    // 解封账号
    public function memberJie()
    {
        $member_list_id = I('member_list_id');
        M('member_list')->where(array('member_list_id'=>$member_list_id))->setField('is_open',1);
        $this->success("解封用户成功！",U("memberList",array('p'=>I('p',1))),1);
    }

    // 删除用户
    public function memberDel()
    {
        $member_list_id = I('member_list_id');
        $res = M('member_list')->where(array('member_list_id'=>$member_list_id))->delete();
        if($res !== false){
            $this->success('删除成功！',U("memberList",array('p'=>I('p',1))),1);
        }else{
            $this->error('删除失败！',U("memberList",array('p'=>I('p',1))),0);
        }
    }

}