<?php
namespace Api\Controller;
use Think\Controller;
use Api\Controller\BaseController;
use Think\Verify;
use Org\Util\Stringnew;
class PersonalController extends BaseController
{
    //个人中心
    public function index()
    {
        $stall_id = $this->stall_id;
        $stallData = M('stall')->alias('a')
            ->field("a.stall_id,a.image,a.stall_name,a.stall_tel,a.score,a.stall_type,a.on_the_pin,b.ma_merchantname,a.tone_music")
            ->join("LEFT JOIN __MERCHANT__ as b ON a.ma_id = b.ma_id ")
            ->where(array('stall_id'=>$stall_id))
            ->find();
        if (empty($stallData)) {
        	$this->make_json_error("用户未登录",512);
        }

        $this->make_json_result('个人中心',$stallData);
    }

    //修改密码
    public function password()
    {
        $stall_id = $this->stall_id;
        $old_password = I('old_password');//旧密码
        $new_password = I('new_password');//新密码
        $con_password = I('con_password');//确认密码
        $stall_db = M('stall');

        //验证
        if (empty($old_password) || empty($new_password) || empty($con_password)){
            $this->make_json_error('参数错误！',10508);
        }
        if($old_password == $new_password){
            $this->make_json_error('新密码与旧密码相同！',10508);
        }
        if($new_password != $con_password){
            $this->make_json_error('新密码与确认密码不同！',10508);
        }
        $ret = $stall_db->alias('a')->where(array('stall_id'=>$stall_id))->find();
        if($ret['st_pwd'] != encrypt_password($old_password,$ret['ma_pwd_salt'])){
            $this->make_json_error('密码错误!',204);
        }

        $ma_pwd_salt = Stringnew::randString(10);
        $data['st_pwd'] = encrypt_password($new_password,$ma_pwd_salt);
        $data['ma_pwd_salt'] = $ma_pwd_salt;
        $res = $stall_db->where(array('stall_id'=>$stall_id))->save($data);
        if($res){
            $this->make_json_error('修改成功!',0);
        }else{
            $this->make_json_error('修改失败！',1);
        }

    }

    //提示音(作废)
    public function tone_music()
    {
        if($_FILES) {
            $stall_id = $this->stall_id;
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 314572800000000;// 设置附件上传大小
            $upload->exts = array('mp3');// 设置附件上传类型
            $upload->rootPath = C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath = 'music/'; // 设置附件上传（子）目录
            $upload->saveRule = 'time';
            $info = $upload->upload();
            if (!empty($info)) {
                $music = substr(C('UPLOAD_DIR'), 1) . $info['music']['savepath'] . $info['music']['savename'];//如果上传成功则完成路径拼接
            } else {
                $this->make_json_error($upload->getError(), 10018);
            }

            $res = M("stall")->where(array('stall_id' => $stall_id))->setField('tone_music', $music);
            if ($res) {
                $this->make_json_error('成功！', 0);
            } else {
                $this->make_json_error('失败！', 10018);
            }
        }else{
            $this->make_json_error('文件不能为空！',10010);
        }
    }

    //提示音列表
    public function music_list()
    {
        $stall_id = $this->stall_id;

        $music_list = M('stall_music')
            ->field("music_id,music,music_type,music_state,music_name")
            ->where(array('stall_id'=>$stall_id))
            ->select();

        $this->make_json_result('提示音列表',$music_list);
    }

    //添加提示音
    public function music_add()
    {
        $stall_id = $this->stall_id;
        $music_name = I('music_name');
        if (empty($music_name)){
            $this->make_json_error('参数不正确！', 10048);
        }
        if($_FILES) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 314572800000000;// 设置附件上传大小
            $upload->exts = array('jpg','mp3');// 设置附件上传类型
            $upload->rootPath = C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath = 'music/'; // 设置附件上传（子）目录
            $upload->saveRule = 'time';
            $info = $upload->upload();
            if (!empty($info)) {
                $music = substr(C('UPLOAD_DIR'), 1) . $info['music']['savepath'] . $info['music']['savename'];//如果上传成功则完成路径拼接
            } else {
                $this->make_json_error($upload->getError(), 10018);
            }

            $data = [
                'stall_id' => $stall_id,
                'music' => $music,
                'music_name' => $music_name,
                'music_type' => 2,
                'music_state' => 2,
                'addtime' => time(),
            ];

            $res = M('stall_music')->add($data);
            if ($res) {
                $this->make_json_error('成功！', 0);
            } else {
                $this->make_json_error('失败！', 10018);
            }
        }else{
            $this->make_json_error('文件不能为空！',10010);
        }
    }

    //删除提示音
    public function music_del()
    {
        $music_id = I('music_id');
        $stall_music_db = M('stall_music');

        if(empty($music_id)){
            $this->make_json_error('参数错误！', 10048);
        }
        $ret = $stall_music_db->where(array('music_id'=>$music_id))->find();
        if ($ret['music_state'] == 1){
            $this->make_json_error('系统默认声音不能删除！', 10048);
        }
        if ($ret['music_type'] == 1){
            $this->make_json_error('提示音使用中，不能删除！', 10048);
        }

        $res = $stall_music_db->where(array('music_id'=>$music_id))->delete();
        if ($res) {
            $this->make_json_error('删除成功！', 0);
        } else {
            $this->make_json_error('删除失败！', 10018);
        }
    }

    //设为默认
    public function music_type()
    {
        $music_id = I('music_id');
        $stall_id = $this->stall_id;
        $stall_music_db = M('stall_music');

        if(empty($music_id)){
            $this->make_json_error('参数错误！', 10048);
        }

        $stall_music_db->where(array('stall_id'=>$stall_id))->setField('music_type',2);
        $res = $stall_music_db->where(array('music_id'=>$music_id))->setField('music_type',1);
        if ($res) {
            $this->make_json_error('设置成功！', 0);
        } else {
            $this->make_json_error('设置失败！', 10018);
        }
    }

    //结算统计
    public function statistics()
    {
        $stall_id = $this->stall_id;
        $start_time = strtotime(date('Y-m-d 00:00:00',time()));
        $end_time = strtotime(date('Y-m-d 23:59:59',time()));

        $finance_db = M('finance');
        //分页
        $page = I('page',1);//页数
        $rows = I('psize',10);//每页显示数量
        $firstRow = ($page-1)*$rows;
        $list = $finance_db
            ->field("order_no,money,state,statue,creattime")
            ->where(array('stall_id'=>$stall_id,'creattime'=>array(array('egt',$start_time),array('elt',$end_time)),'statue'=>1))
            ->limit($firstRow,$rows)
            ->order("creattime desc")
            ->select();
        foreach ($list as $key=>$value){
            $list[$key]['creattime'] = date('Y-m-d H:i:s',$value['creattime']);
        }
        $income = $finance_db
            ->where(array('stall_id'=>$stall_id,'creattime'=>array(array('egt',$start_time),array('elt',$end_time)),'statue'=>1))
            ->sum('money');
        if(empty($income)){
            $income = sprintf("%.2f",0);
        }
        $expend = $finance_db
            ->where(array('stall_id'=>$stall_id,'creattime'=>array(array('egt',$start_time),array('elt',$end_time)),'statue'=>2))
            ->sum('money');
        if(empty($expend)){
            $expend = sprintf("%.2f",0);
        }
        $net_income = sprintf("%.2f",$income - $expend);

        $statisData = [
            'list' => $list,
            'income' => $net_income,
        ];

        $this->make_json_result('个人中心',$statisData);
    }



}