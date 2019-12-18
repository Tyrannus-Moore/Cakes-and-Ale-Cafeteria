<?php
namespace Home\Controller;

use Think\Controller;
use Home\Controller\BaseController;

class PersonalController extends BaseController
{
    //个人中心
    public function index()
    {
        $member_list_id = $_SESSION['member_list_id'];
        $infos = M("member_list")
            ->field("member_list_nickname,member_list_headpic,telphone,state,status")
            ->where(array('member_list_id'=>$member_list_id))
            ->find();

        $this->assign('infos',$infos);
        $this->display();
    }

    //帮助中心
    public function help()
    {
        $this->display();
    }

    //帮助说明
    public function help_illustrate()
    {
        $infos = M("baise")->where(array('type'=>"1"))->find();

        $this->assign('infos',$infos);
        $this->display();
    }

    //意见反馈
    public function feedback()
    {
        $member_list_id = $_SESSION['member_list_id'];
        if(IS_AJAX){
            $ma_id = $_SESSION['ma_id'];
            $phone = I('telphone');
            $content = filter_Emoji(safe_replace(I('content')));
            if(!preg_match('/^1[3456789]{1}\d{9}$/',$phone)){
                $this->error('手机号格式不正确！',U('feedback'));
            }

            $data['member_list_id'] = $member_list_id;
            $data['ma_id'] = $ma_id;
            $data['phone'] = $phone;
            $data['content'] = $content;
            $data['addtime'] = time();

            $res = M('feedback')->add($data);
            if($res !== false){
                $this->success('反馈成功！',U('feedback'));
            }else{
                $this->error('反馈失败！',U('feedback'));
            }
        }else{
            $telphone = M('member_list')->where(array('member_list_id'=>$member_list_id))->getField('telphone');

            $this->assign('telphone',$telphone);
            $this->display();
        }
    }

    //常见问题
    public function problem_list()
    {
        $list = M('baise')->where(array('type'=>"2"))->order('addtime DESC')->select();

        $this->assign('list',$list);
        $this->display();
    }

    //常见问题-详情
    public function problem_info()
    {
        $baise_id = I('baise_id');
        $infos = M("baise")->where(array('baise_id'=>$baise_id))->find();

        $this->assign('infos',$infos);
        $this->display();
    }

    //完善资料
    public function personal_add()
    {
    	if(IS_AJAX){
            $member_list_id = $_SESSION['member_list_id'];
            $member_list_nickname = I('member_list_nickname');
            $member_list_sex = I('member_list_sex');
            $birthary_time = strtotime(I('birthary_time'));
            $telphone = I('telphone');
            $school_id = I('school_id');
            $faculty = I('faculty');
            $member_class = I('member_class');
            $stature = I('stature');
            $weight = I('weight');
            $href = I('href');
            $data = [
                'member_list_nickname' => $member_list_nickname,
                'member_list_sex' => $member_list_sex,
                'birthary_time' => $birthary_time,
                'telphone' => $telphone,
                'school_id' => $school_id,
                'faculty' => $faculty,
                'member_class' => $member_class,
                'stature' => $stature,
                'weight' => $weight,
                'is_perfect' => 1,
            ];

            $res = M("member_list")->where(array('member_list_id'=>$member_list_id))->save($data);
            if($res !== false){
                $this->success('保存成功！',$href);
            }else{
                $this->error('保存失败！',U('personal_add'),0);
            }
        }else{
            $ma_id = $_SESSION['ma_id'];
            $member_list_id = $_SESSION['member_list_id'];
            $href = I('href');
            $infos = M("member_list")->alias('a')
                ->field("a.member_list_nickname,a.member_list_sex,a.telphone,a.birthary_time,b.name,a.school_id,a.faculty,a.member_class,a.stature,a.weight")
                ->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
                ->where(array('a.member_list_id'=>$member_list_id))
                ->find();
            //学校
            $school_info = M("merchant")->alias('a')
                ->field("b.school_id,b.name")
                ->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
                ->where(array('a.ma_id'=>$ma_id))
                ->find();
            //院系
            $faculty = M('faculty')->where(array('school_id'=>$school_info['school_id']))->select();
            $member_class = M('faculty')->where(array('faculty_name'=>$infos['faculty']))->getField('num');
            foreach ($faculty as $key=>$value){
                $yuan[$key]['value'] = $value['faculty_id'];
                $yuan[$key]['text'] = $value['faculty_name'];
            }
            for ($x=0; $x<$member_class; $x++) {
                $ban[$x]['value'] = $x;
                $y = $x+1;
                $ban[$x]['text'] = $y.'班';
            }

            $this->assign('href',$href);
            $this->assign('infos',$infos);
            $this->assign('school_info',$school_info);
            $this->assign('faculty',json_encode($yuan));
            $this->assign('ban',json_encode($ban));
            $this->assign('member_class',$member_class);
            $this->display();
        }
    }

    //个人资料
    public function personal()
    {
        if(IS_AJAX){
            $member_list_id = $_SESSION['member_list_id'];
            $member_list_nickname = I('member_list_nickname');
            $member_list_sex = I('member_list_sex');
            $birthary_time = strtotime(I('birthary_time'));
            $telphone = I('telphone');
            $school_id = I('school_id');
            $faculty = I('faculty');
            $member_class = I('member_class');
            $stature = I('stature');
            $weight = I('weight');
            $data = [
                'member_list_nickname' => $member_list_nickname,
                'member_list_sex' => $member_list_sex,
                'birthary_time' => $birthary_time,
                'telphone' => $telphone,
                'school_id' => $school_id,
                'faculty' => $faculty,
                'member_class' => $member_class,
                'stature' => $stature,
                'weight' => $weight,
                'is_perfect' => 1,
            ];

            $res = M("member_list")->where(array('member_list_id'=>$member_list_id))->save($data);
            if($res !== false){
                $this->success('保存成功！',U('index'));
            }else{
                $this->error('保存失败！',U('index'),0);
            }
        }else{
            $ma_id = $_SESSION['ma_id'];
            $member_list_id = $_SESSION['member_list_id'];
            $infos = M("member_list")->alias('a')
                ->field("a.member_list_nickname,a.member_list_sex,a.telphone,a.birthary_time,b.name,a.school_id,a.faculty,a.member_class,a.stature,a.weight")
                ->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
                ->where(array('a.member_list_id'=>$member_list_id))
                ->find();
            //学校
            $school_info = M("merchant")->alias('a')
                ->field("b.school_id,b.name")
                ->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
                ->where(array('a.ma_id'=>$ma_id))
                ->find();
            //院系
            $faculty = M('faculty')->where(array('school_id'=>$school_info['school_id']))->select();
            $member_class = M('faculty')->where(array('faculty_name'=>$infos['faculty']))->getField('num');
            foreach ($faculty as $key=>$value){
                $yuan[$key]['value'] = $value['faculty_id'];
                $yuan[$key]['text'] = $value['faculty_name'];
            }
            for ($x=0; $x<$member_class; $x++) {
                $ban[$x]['value'] = $x;
                $y = $x+1;
                $ban[$x]['text'] = $y.'班';
            }
			
            $this->assign('infos',$infos);
            $this->assign('school_info',$school_info);
            $this->assign('faculty',json_encode($yuan));
            $this->assign('ban',json_encode($ban));
            $this->assign('member_class',$member_class);
            $this->display();
        }
    }
    //选择班级
    public function ban()
    {
    	$fac = I('fac');
    	$member_class = M('faculty')->where(array('faculty_id'=>$fac))->getField('num');
    	for ($x=0; $x<$member_class; $x++) {
    		$ban[$x]['value'] = $x;
    		$y = $x+1;
    		$ban[$x]['text'] = $y.'班';
    	}
    	$this->ajaxReturn($ban);
    }

    //配送员入住
    public function delivery()
    {
        if(IS_AJAX){
            $member_list_id = $_SESSION['member_list_id'];
            $type = I('type');
            $member_name = I('member_name');
            $member_list_sex = I('member_list_sex');
            $telphone = I('telphone');
            $id_card = I('id_card');
            //验证
            $is_perfect = M("member_list")->where(array('member_list_id'=>$member_list_id))->getField('is_perfect');
            if ($is_perfect == 2) {
            	$this->error('请先完善个人资料！',U('personal_add'));
            }
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
                        if ($file['key']=='card_zheng'){//单图路径数组
                            $data["card_zheng"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
                        }
                        if ($file['key']=='card_fan'){//单图路径数组
                            $data["card_fan"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
                        }
                    }
                }else{
                    $this->error('身份证照片必填！');
                }
            }

            $data['type'] = $type;
            $data['member_name'] = $member_name;
            $data['member_list_sex'] = $member_list_sex;
            $data['telphone'] = $telphone;
            $data['id_card'] = $id_card;
            $data['state'] = 1;
            $data['application_time'] = time();
			//dump($data);exit;
            $res = M('member_list')->where(array('member_list_id'=>$member_list_id))->save($data);
            if($res !== false){
                $this->success('申请成功，等待审核！',U('index'));
            }else{
                $this->error('申请失败，请重新申请',U('delivery'));
            }
        }else{
            $member_list_id = $_SESSION['member_list_id'];
            $infos = M("member_list")->alias('a')
                ->field("a.type,a.member_name,a.telphone,a.member_list_sex,a.id_card,a.card_zheng,a.card_fan,a.refusal_reason,a.state")
                //->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
                ->where(array('a.member_list_id'=>$member_list_id))
                ->find();

            $this->assign('infos',$infos);
            $this->display();
        }
    }
    //配送员入住详情
    public function deliverys()
    {
        $member_list_id = $_SESSION['member_list_id'];
        $infos = M("member_list")->alias('a')
            ->field("a.type,a.member_name,a.telphone,a.member_list_sex,a.id_card,a.card_zheng,a.card_fan,a.refusal_reason,a.state")
            //->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
            ->where(array('a.member_list_id'=>$member_list_id))
            ->find();

        $this->assign('infos',$infos);
        $this->display();
    }

    //我的地址
    public function address_list()
    {
        $member_list_id = $_SESSION['member_list_id'];
        
        $list = M('user_address_list')->alias('a')
            ->field("a.*")
            /*->join('LEFT JOIN __REGION__ AS b ON a.proviceid=b.cityid')
            ->join('LEFT JOIN __REGION__ AS c ON a.cityid=c.cityid')
            ->join('LEFT JOIN __REGION__ AS d ON a.countyid=d.cityid')*/
            ->where(array('is_del'=>2,'member_list_id'=>$member_list_id))
            ->order("address_id DESC")
            ->select();

        foreach ($list as $key=>$val){
            $list[$key]['proviceid'] = M('user_address')->where(array('id'=>$val['proviceid']))->getField('title');
            $list[$key]['cityid'] = M('user_address')->where(array('id'=>$val['cityid']))->getField('title');
            $list[$key]['countyid'] = M('user_address')->where(array('id'=>$val['countyid']))->getField('title');
        }
//        return print_r($list);
        $this->assign('list',$list);
        $this->display();
    }

    //添加地址
    public function address_add()
    {
        if(IS_AJAX){
            $member_list_id = $_SESSION['member_list_id'];
            $name           = I('name');
            $phone          = I('phone');
            $proviceid      = I('proviceid');
            $cityid         = I('cityid');
            $countyid       = I('countyid');
            $address        = I('address');
//            $lat            = I('lat');
//            $lng            = I('lng');

            $merchantData = M('merchant')->where(array('ma_id'=>session('ma_id')))->field("longitude,latitude")->find();

            $lng = $merchantData['longitude'];
            $lat = $merchantData['latitude'];
            $data = [
                'member_list_id' => $member_list_id,
                'name' => $name,
                'phone' => $phone,
                'proviceid' => $proviceid,
                'cityid' => $cityid,
                'countyid' => $countyid,
                'address' => $address,
                'longitude' => $lng,
                'latitude'  => $lat,
            ];

            $merchantData = M('merchant')->where(array('ma_id'=>session('ma_id')))->field("longitude,latitude")->find();
            $distance = getDistanceByGaoDe($merchantData['longitude'],$merchantData['latitude'],$lng,$lat);
            if($distance>3){
                $this->error('该位置距离商家已超过3km,不能添加！',U('address_add'),0);
            }
            $res = M('user_address_list')->add($data);
//            file_put_contents('./data.log' , json_encode($data)."\n" , FILE_APPEND);
            if($res !== false){
                $this->success('添加成功！',U('address_list'));
            }else{
                $this->error('添加失败！',U('address_list'),0);
            }
        }else{
            $infos = M('merchant')->where(array('ma_id'=>session('ma_id')))->field('longitude,latitude')->find();
            $this->assign('infos',$infos);
            $this->display();
        }
    }

    public function get_address(){
        $ma_id = 1;

        $add_list = M('user_address')->field("id,title,pid")->where(array('status'=>1,'ma_id'=>$ma_id))->select();

        $new_lis = [];
        foreach ($add_list as $key=>$val){
            $new_list[$key]['pid'] = $val['pid'];
            $new_list[$key]['value'] = $val['id'];
            $new_list[$key]['text'] = $val['title'];
        }

        $list = $this->Tree($new_list);
        if ($list) {
            $data['code'] = 200;
            $data['msg'] = '获取成功';
            $data['data'] = $list;
        } else {
            $data['code'] = 100;
            $data['msg'] = '暂无数据';
        }
        $this->ajaxReturn($data);
    }


    //递归调用--树处理
    public function Tree($arr,$pid=0){
        $res = [];//定义一个空数组
        foreach($arr as $v){
            if($v['pid'] == $pid){
                $data = $this->Tree($arr,$v['value']);
                if (!empty($data)) {
                    $v['children'] = $data;
                }
                $res[] = $v;
            }
        }
        return $res;
    }

    //编辑地址
    public function address_edit()
    {
        if(IS_AJAX){
            $address_id     = I('address_id');
            $name           = I('name');
            $phone          = I('phone');
            $proviceid      = I('proviceid');
            $cityid         = I('cityid');
            $countyid       = I('countyid');
            $address        = I('address');
//            $lat            = I('lat');
//            $lng            = I('lng');

            $merchantData = M('merchant')->where(array('ma_id'=>session('ma_id')))->field("longitude,latitude")->find();

            $lng = $merchantData['longitude'];
            $lat = $merchantData['latitude'];

            $data = [
                'name' => $name,
                'phone' => $phone,
                'proviceid' => $proviceid,
                'cityid' => $cityid,
                'countyid' => $countyid,
                'address' => $address,
                'longitude' => $lng,
                'latitude'  => $lat,
            ];
            $merchantData = M('merchant')->where(array('ma_id'=>session('ma_id')))->field("longitude,latitude")->find();
            $distance = getDistanceByGaoDe($merchantData['longitude'],$merchantData['latitude'],$lng,$lat);
            if($distance>3){
                $this->error('该位置距离商家已超过3km,不能修改！',U('address_list'),0);
            }
            $res = M('user_address_list')->where(array('address_id'=>$address_id))->save($data);
            if($res !== false){
                $this->success('编辑成功！',U('address_list'));
            }else{
                $this->error('编辑失败！',U('address_list'));
            }
        }else{
            $address_id = I('address_id');
            $infos = M('user_address_list')->alias('a')
                ->field("a.*")
                /*->join('LEFT JOIN __REGION__ AS b ON a.proviceid=b.cityid')
                ->join('LEFT JOIN __REGION__ AS c ON a.cityid=c.cityid')
                ->join('LEFT JOIN __REGION__ AS d ON a.countyid=d.cityid')*/
                ->where(array('a.address_id'=>$address_id))
                ->find();

            $infos['proviceid'] = M('user_address')->where(array('id'=>$infos['proviceid']))->getField('title');
            $infos['cityid'] = M('user_address')->where(array('id'=>$infos['cityid']))->getField('title');
            $infos['countyid'] = M('user_address')->where(array('id'=>$infos['countyid']))->getField('title');

            $this->assign('infos',$infos);
            $info = M('merchant')->where(array('ma_id'=>session('ma_id')))->field('longitude,latitude')->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    //删除地址
    public function address_del()
    {
        $address_id = I('address_id');
        $res = M('user_address_list')->where(array('address_id'=>$address_id))->setField('is_del',1);
        if($res !== false){
            $this->ajaxReturn('200');
        }else{
            $this->ajaxReturn('1');
        }
    }

    //设为默认地址
    public function address_default()
    {
        $member_list_id = $_SESSION['member_list_id'];
        $address_id = I('address_id');
        M('user_address_list')->where(array('member_list_id'=>$member_list_id))->setField('is_default',2);
        $res = M('user_address_list')->where(array('address_id'=>$address_id))->setField('is_default',1);
        if($res !== false){
            $this->ajaxReturn('200');
        }else{
            $this->ajaxReturn('1');
        }
    }

    //我的收藏
    public function mycollection()
    {
        $member_list_id = $_SESSION['member_list_id'];
        $list = M('member_collection')->alias('a')
            ->field("a.*,b.stall_name,b.score,b.image,b.on_the_pin,b.stall_type")
            ->join('LEFT JOIN __STALL__ AS b ON a.stall_id=b.stall_id')
            ->where(array('a.member_list_id'=>$member_list_id))
            ->order('addtime desc')
            ->select();

        $this->assign('list',$list);
        $this->display();
    }

    //删除收藏
    public function mycollection_del()
    {
        $collection_id = I('collection_id');

        $res = M('member_collection')->where(array('collection_id'=>$collection_id))->delete();
        if ($res !== false){
            $this->ajaxReturn('200');
        }else{
            $this->ajaxReturn('1');
        }
    }









}