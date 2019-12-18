<?php
// +----------------------------------------------------------------------
// | 功能:用户管理
// +----------------------------------------------------------------------
namespace Member\Controller;
use Common\Controller\AuthController;;
class MemberController extends AuthController
{
    //用户列表
    public function memberList()
    {
        $where = array();
        $search = I('search');
        if($search){
            if($search['member_list_nickname']){
                $where['a.member_list_nickname'] =  array('like','%'.trim($search['member_list_nickname']).'%');
            }
            if($search['telphone']){
                $where['a.telphone'] =  array('like','%'.trim($search['telphone']).'%');
            }
            if($search['is_open']){
                $where['a.is_open'] =  array('eq',$search['is_open']);
            }
            if($search['shopname']){
                $where['b.ma_merchantname'] =  array('like','%'.trim($search['shopname']).'%');
            }
            if($search['school_id']){
                $where['c.school_id'] =  array('eq',$search['school_id']);
            }
            if($search['addtime']){
                $startTime = substr($search['addtime'],0,10);
                $endTime = substr($search['addtime'],13);
                if($startTime && $endTime){
                    $where['a.addtime'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
        }
		$where['a.is_del'] = 2;
        //分页
        $count= M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->join("LEFT JOIN __SCHOOL__ AS c ON a.school_id = c.school_id")->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('member_list')->join("AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id")->join("LEFT JOIN __SCHOOL__ AS c ON a.school_id = c.school_id")
            ->field("a.member_list_id,a.birthary_time,a.faculty,a.ma_id,a.member_list_nickname,a.member_list_sex,a.member_class,a.telphone,a.addtime,a.is_open,b.ma_merchantname,c.school_id,c.name")
            ->where($where)->limit($Page->firstRow.','.$Page->listRows)->order("a.addtime DESC")->select();
        //查找学校
        $schoolData = M('school')->where("is_del=1")->select();
        $this->assign('schoolData',$schoolData);
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //用户状态设置
    public function memberState()
    {
        $id=I('x');
        $search = I('search');
        if (empty($id)){
            $this->error('用户ID不存在',U('memberList'),0);
        }
        $status=M('member_list')->where(array('member_list_id'=>$id))->getField('is_open');//判断当前状态情况
        if($status==1){
            $statedata = array('is_open'=>2);
            M('member_list')->where(array('member_list_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('memberList',array('p'=>I('p',1),'search'=>$search)),0);
        }else{
            $statedata = array('is_open'=>1);
            M('member_list')->where(array('member_list_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('memberList',array('p'=>I('p',1),'search'=>$search)),0);
        }
    }

    //编辑用户
    public function memberEdit()
    {
        if (IS_AJAX){
            $ma_id = I('ma_id');
            $member_list_id = I('member_list_id');
            $telphone = I('telphone');//手机号
            //验证
            $passPhone = M('member_list')->where(array('ma_id'=>$ma_id,'member_list_id'=>array('neq',$member_list_id),'telphone'=>$telphone))->find();
            if($passPhone){
                $this->error("手机号已存在！",U("memberList"),2);
            }
            //图片
            if($_FILES){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
                $upload->savePath  =     'Member/'; // 设置附件上传（子）目录
                $upload->saveRule  =     'time';
                $info   =   $upload->upload();
                if($info) {
                    foreach($info as $file){
                        if ($file['key']=='img'){//单图路径数组
                            $data["member_list_headpic"] = substr(C('UPLOAD_DIR'),1).$file['savepath'].$file['savename'];
                        }
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
            $data['birthary_time'] = strtotime(I('birthary_time'));//出生日期

            $res = M('member_list')->where("member_list_id='$member_list_id'")->save($data);
            if ($res !== false){
                $this->success('编辑成功！',U('memberList',array('p'=>I('p',1))),1);
            }else{
                $this->error('没有任何修改！');
            }
        }else{
            $member_list_id = I('member_list_id');
            $infos = M('member_list')->join("AS a LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
                ->join("LEFT JOIN __MERCHANT__ as c on a.ma_id = c.ma_id")
                ->where(array('a.member_list_id'=>$member_list_id))
                ->field("a.*,b.name,c.ma_merchantname")
                ->find();
            //院系
            $faculty = M('faculty')->where(array('school_id'=>$infos['school_id']))->select();
            foreach ($faculty as $key=>$value){
                if($value['faculty_name'] == $infos['faculty']){
                    $passNum = $value['num'];
                }
            }
            $str = array();
            for ($i=1; $i<=$passNum; $i++) {
                $str[] = $i."班";
            }
            $this->assign('str',$str);
            $this->assign('infos',$infos);
            $this->assign('faculty',$faculty);
            $this->display();
        }
    }

    //用户详情
    public function memberDetail()
    {
        $member_list_id = I('member_list_id');
        $infos = M('member_list')->join("AS a LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
            ->join("LEFT JOIN __MERCHANT__ as c on a.ma_id = c.ma_id")
            ->where(array('a.member_list_id'=>$member_list_id))
            ->field("a.*,b.name,c.ma_merchantname")
            ->find();
        //院系
        $faculty = M('faculty')->where(array('school_id'=>$infos['school_id']))->select();
        $this->assign('infos',$infos);
        $this->assign('faculty',$faculty);
        $this->display();
    }

}