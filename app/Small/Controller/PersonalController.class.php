<?php
namespace Small\Controller;

use Think\Controller;
use Small\Controller\BaseController;

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
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $arr = json_decode(file_get_contents("php://input") , true);

        $arr['member_list_id'] = $member_list_id;
        $arr['ma_id'] = $ma_id;
        // $data['phone'] = $phone;
        // $data['content'] = $content;
        $arr['addtime'] = time();

        $res = M('feedback_mini')->add($arr);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '反馈成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '反馈失败';
        }

        $this->ajaxReturn($data);
    }

    
    //意见反馈
    public function feedback_jiu()
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

    // 短信验证码发送
    public function sendsms(){
        $sms = new \Org\Util\Sms;

        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $mobile = I('mobile');

        if (empty($mobile)) {
            $data['code'] = 200;
            $data['msg'] = "请输入手机号";
        }

        //会员注册
        $message_code = M('message_code')->where(array('mobile'=>$mobile))->find();
        if($message_code){
            if (time() - $message_code['addtime'] <= 60) {
                $data['code'] = 100;
                $data['msg'] = "请在60秒之后获取";
            }else{
                $code = $this->get_code(6);
                $arr = [
                    'addtime'   =>  time(),
                    'code'      =>  $code,
                ];
                $res = M("message_code")->where(array('id'=>$message_code['id']))->save($arr);
                $sms::sendSms($mobile,'SMS_168340881',['code'=>$code]);
                $data['code'] = 200;
                $data['msg'] = "获取成功";
                $data['data'] = array('code'=>$code);
            }
        }else{
            $code = $this->get_code(6);
            $arr['mobile'] = $mobile;
            $arr['addtime'] = time();
            $arr['code'] = $code;
            $res = M('message_code')->add($arr);
            $sms::sendSms($mobile,'SMS_168340881',['code'=>$code]);
            if ($res !== false) {
                $data['code'] = 200;
                $data['msg'] = "获取成功";
                $data['data'] = array('code'=>$code);
            } else {
                $data['code'] = 100;
                $data['msg'] = "获取失败,请稍候重试";
            }
        }
        $this->ajaxReturn($data);
    }

    //生成随机数length--自定义长度
    public function get_code( $length = 0 ){
        $str = substr(rand(10000000 , 99999999) , 0, $length);//md5加密，time()当前时间戳
        return $str;
    }

    //个人资料编辑详情
    public function personal_info()
    {
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

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
        $faculty = M('faculty')->where(array('school_id'=>$school_info['school_id']))->order('num desc')->select();
        $member_class = M('faculty')->where(array('faculty_name'=>$faculty[0]['faculty_name']))->getField('num');
        foreach ($faculty as $key=>$value){
            $yuan[$key]['value'] = $value['faculty_id'];
            $yuan[$key]['text'] = $value['faculty_name'];
        }
        for ($x=0; $x<$member_class; $x++) {
            $ban[$x]['value'] = $x;
            $y = $x+1;
            $ban[$x]['text'] = $y.'班';
        }

        $infos['birthary_time'] = date("Y-m-d" , $infos['birthary_time']);
        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['data'] = $infos;
        $data['school_info'] = $school_info;
        $data['faculty'] = $yuan;
        $data['ban'] = $ban;
        $data['member_class'] = $member_class;
        $this->ajaxReturn($data);
    }

    //个人资料
    public function personal_edit()
    {
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        // $member_list_id = 1926;

        // $json = '{"member_list_nickname":"\u6d4b\u8bd5\u6d4b\u8bd51","member_list_sex":1,"birthary_time":1548172800,"telphone":17600969393,"school_id":2,"faculty":"\u6c7d\u8f66\u7cfb","member_class":"3\u73ed","stature":171,"weight":57.5,"code":893746}';

        $arr = json_decode(file_get_contents('php://input') , true);
        // $arr = json_decode($json , true);

        if ($arr['have_code']) {
            $code_info = M('message_code')->where(array('mobile'=>$arr['telphone']))->find();
            if ($arr['code'] !== $code_info['code']) {
                $data['code'] = 100;
                $data['msg'] = '验证码错误';
            }
        }

        if ($arr['faculty'] == '') {
            $data['code'] = 100;
            $data['msg'] = '请选择院系';
        }
        if ($arr['member_class'] == '') {
            $data['code'] = 100;
            $data['msg'] = '请选择班级';
        }

        $arr['birthary_time'] = strtotime($arr['birthary_time']);
        $arr['is_perfect'] = 1;

        $res = M("member_list")->where(array('member_list_id'=>$member_list_id))->save($arr);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = "保存成功";
        }else{
            $data['code'] = 100;
            $data['msg'] = '保存失败';
        }

        $this->ajaxReturn($data);
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

    //配送员入驻信息
    public function delivery_info(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $infos = M("member_list")->alias('a')
            ->field("a.type,a.member_name,a.telphone,a.member_list_sex,a.id_card,a.card_zheng,a.card_fan,a.refusal_reason,a.state")
            //->join("LEFT JOIN __SCHOOL__ as b on a.school_id = b.school_id")
            ->where(array('a.member_list_id'=>$member_list_id))
            ->find();

        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['infos'] = $infos;
        $this->ajaxReturn($data);
        // $this->assign('infos',$infos);
        // $this->display();
    }

    //提交配送员信息
    public function delivery()
    {
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $arr = json_decode(file_get_contents("php://input") , true);

        //验证
        $is_perfect = M("member_list")->where(array('member_list_id'=>$member_list_id))->getField('is_perfect');
        if ($is_perfect == 2) 
        {
            $data['code'] = 100;
            $data['msg'] = "请完善个人信息";
        }

        $code_info = M('message_code')->where(array('mobile'=>$arr['telphone']))->find();
        if ($arr['code'] !== $code_info['code']) {
            $data['code'] = 100;
            $data['msg'] = '验证码错误';
        }

        $arr['state'] = 1;
        $arr['application_time'] = time();
        //dump($data);exit;
        $res = M('member_list')->where(array('member_list_id'=>$member_list_id))->save($arr);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = "申请成功，等待审核";
        }else{
            $data['code'] = 100;
            $data['msg'] = "申请失败，请重新申请";
        }

        $this->ajaxReturn($data);
    }


    //我的地址
    public function address_list()
    {
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        
        $list = M('user_address_list')->alias('a')
            ->field("a.*")
            ->where(array('is_del'=>2,'member_list_id'=>$member_list_id))
            ->order("address_id DESC")
            ->select();

        foreach ($list as $key => $value) {
            $proviceid_info = M('user_address')->field('title')->where(array('id' => $value['proviceid']))->find();
            $cityid_info = M('user_address')->field('title')->where(array('id' => $value['cityid']))->find();
            $countyid_info = M('user_address')->field('title')->where(array('id' => $value['countyid']))->find();
            $list[$key]['proviceid'] = $proviceid_info['title'];
            $list[$key]['cityid'] = $cityid_info['title'];
            $list[$key]['countyid'] = $countyid_info['title'];
        }
        if($list){
            $data['code'] = 200;
            $data['msg'] = '获取成功';
            $data['data'] = $list;
        }else{
            $data['code'] = 100;
            $data['msg'] = '暂无数据';
        }
        $this->ajaxReturn($data);
    }

    //获取联动信息
    public function user_address(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];
        
        $add_list = M('user_address')->field("id,title,pid")->where(array('status'=>1,'ma_id'=>$ma_id))->select();


        $list = $this->Tree($add_list);
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

    //添加地址
    public function address_add()
    {
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $arr = json_decode(file_get_contents('php://input') , true);
        if (empty($arr)) {
            $data['code'] = 100;
            $data['msg'] = '信息为空，请输入信息';
        }

        $arr['member_list_id'] = $member_list_id;

        $merchantData = M('merchant')->where(array('ma_id'=>$ma_id))->field("longitude,latitude")->find();
        $distance = getDistanceByGaoDe($merchantData['longitude'],$merchantData['latitude'],$lng,$lat);
        
        // if($distance>3){
        //     $data['code'] = 100;
        //     $data['mag'] = '该位置距离商家已超过3km,不能添加';
        //     $this->ajaxReturn($data);
        // }
        $res = M('user_address_list')->add($arr);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '添加成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '添加失败，请稍后重试';
        }
        $this->ajaxReturn($data);
    }


    //获取要编辑的地址信息
    public function address_info(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $address_id = I('address_id');

        $infos = M('user_address_list')->where(array('address_id'=>$address_id))->find();

        $info = M('merchant')->where(array('ma_id'=>session('ma_id')))->field('longitude,latitude')->find();
        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['data'] = $infos;
        $data['position'] = $info;
        $this->ajaxReturn($data);
    }

    //编辑地址
    public function address_edit()
    {
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $arr = json_decode(file_get_contents('php://input') , true);
        if (empty($arr)) {
            $data['cood'] = 100;
            $data['msg'] = '信息为空，请输入信息';
        }

        $arr['member_list_id'] = $member_list_id;
        $address_id = $arr['address_id'];

        $merchantData = M('merchant')->where(array('ma_id'=>$ma_id))->field("longitude,latitude")->find();
        $distance = getDistanceByGaoDe($merchantData['longitude'],$merchantData['latitude'],$lng,$lat);
        // if($distance>3){
        //     $this->error('该位置距离商家已超过3km,不能修改！',U('address_list'),0);
        // }
        $res = M('user_address_list')->where(array('address_id'=>$address_id))->save($arr);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '编辑成功！';
        }else{
            $data['code'] = 100;
            $data['msg'] = '编辑失败，请稍后重试！';
        }
        $this->ajaxReturn($data);
    }

    //删除地址
    public function address_del()
    {
        $address_id = I('address_id');
        $res = M('user_address_list')->where(array('address_id'=>$address_id))->setField('is_del',1);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '删除成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '删除失败，请稍后重试';
        }
        $this->ajaxReturn($data);
    }

    //设为默认地址
    public function address_default()
    {
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $address_id = I('address_id');

        M('user_address_list')->where(array('member_list_id'=>$member_list_id))->setField('is_default',2);
        $res = M('user_address_list')->where(array('address_id'=>$address_id))->setField('is_default',1);

        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '设置成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '设置失败，请稍后重试';
        }
        $this->ajaxReturn($data);
    }

    //我的收藏
    public function mycollection()
    {
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];
        // $member_list_id = 1744;

        $list = M('member_collection')->alias('a')
            ->field("a.*,b.stall_name,b.score,b.image,b.on_the_pin,b.stall_type,b.is_freeze")
            ->join('LEFT JOIN __STALL__ AS b ON a.stall_id=b.stall_id')
            ->where(array('a.member_list_id'=>$member_list_id))
            ->order('addtime desc')
            ->select();

        foreach ($list as $key => $value) {
            $list[$key]['image'] = "http://mccygood.com".$value['image'];
        }

        $data['code'] = 200;
        $data['msg'] = "获取成功";
        $data['data'] = $list;

        $this->ajaxReturn($data);
    }


    //删除收藏
    public function mycollection_del()
    {
        $collection_id = I('collection_id');

        $res = M('member_collection')->where(array('collection_id'=>$collection_id))->delete();

        if ($res !== false){
            $data['code'] = 200;
            $data['msg'] = "删除收藏成功";
        }else{
            $data['code'] = 100;
            $data['msg'] = "删除收藏失败";
        }
        $this->ajaxReturn($data);
    }

    //我的积分
    public function myIntegral(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        // $member_list_id = I('member_list_id');
        $integral = M('integral_statistics')->where(array('member_list_id'=>$member_list_id , 'type'=>1))->getField('sum(integral) as integral');

        $member_info = M('member_list')->field('member_list_nickname,telphone,member_list_headpic')->where(array('member_list_id'=>$member_list_id))->find();
        if (substr($member_info['member_list_headpic'], 0 , 5) != "https") {
            $member_info['member_list_headpic'] = 'http://'.$_SERVER['SERVER_NAME'].$member_info['member_list_headpic'];
        }
//        $member_info['member_list_headpic'] = $member_info['member_list_headpic'];

        $data['code'] = 200;
        $data['msg'] = '获取成功';
        if (empty($integral)) {
            $integral = 0;
        }
        $data['data']['integral'] = $integral;
        $data['data']['member_info'] = $member_info;

        $this->ajaxReturn($data);

    }


    //上传
    public function uploads(){
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        date_default_timezone_set("Asia/Shanghai"); //设置时区
        $code = $_FILES['file'];//获取小程序传来的图片
        if(is_uploaded_file($_FILES['file']['tmp_name'])) {  
            //把文件转存到你希望的目录（不要使用copy函数）  
            $uploaded_file=$_FILES['file']['tmp_name'];  
            $username = "wxupload_".$member_list_id;
            //我们给每个用户动态的创建一个文件夹  
            $user_path = $_SERVER['DOCUMENT_ROOT']."/data/upload/am_pro/".$username;

            $url = 'http://'.$_SERVER['SERVER_NAME']."/data/upload/am_pro/".$username;

            //判断该用户文件夹是否已经有这个文件夹  
            if(!file_exists($user_path)) {  
                //mkdir($user_path); 
                mkdir($user_path,0777,true); 
            }  

            //$move_to_file=$user_path."/".$_FILES['file']['name'];  
            $file_true_name=$_FILES['file']['name'];
            $time = time();
            $rand = rand(1,1000);
            $move_to_file = $user_path."/".$time.$rand."-".date("Y-m-d").substr($file_true_name,strrpos($file_true_name,"."));
            $show_url = $url."/".$time.$rand."-".date("Y-m-d").substr($file_true_name,strrpos($file_true_name,"."));
            $return_url = "/data/upload/am_pro/".$username."/".$time.$rand."-".date("Y-m-d").substr($file_true_name,strrpos($file_true_name,"."));
        // 查找“.”在字符串中最后一次出现的位置  
        // echo "$uploaded_file   $move_to_file";  
            if(move_uploaded_file($uploaded_file,iconv("utf-8","gb2312",$move_to_file))) {  
                $data['code'] = 200;
                $data['msg'] = '上传成功';
                $data['show_url'] = $show_url;
                $data['url'] = $return_url;
            } else {  
                $data['code'] = 100;
                $data['msg'] = '上传失败，请稍后重试';
            } 
        } else {  
            $data['code'] = 100;
            $data['msg'] = '上传失败，请稍后重试';

        }
        $this->ajaxReturn($data);
    }

}