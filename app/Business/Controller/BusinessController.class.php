<?php
// +----------------------------------------------------------------------
// | 功能:商家管理
// +----------------------------------------------------------------------
namespace Business\Controller;
use Common\Controller\AuthController;
use Org\Util\Stringnew;
class BusinessController extends AuthController
{
    //商家信息列表
    public function businessList()
    {
        $where = array();
        //搜索
        $search = I('search');
        if($search){
            if($search['ma_tel']){
                $where['a.ma_tel'] =  array('like','%'.trim($search['ma_tel']).'%');
            }
            if($search['ma_merchantname']){
                $where['a.ma_merchantname'] =  array('like','%'.trim($search['ma_merchantname']).'%');
            }
            if($search['is_open']){
                $where['a.is_open'] =  array('eq',$search['is_open']);
            }
            if($search['school_id']){
                $where['b.school_id'] =  array('eq',$search['school_id']);
            }
            if($search['due_deadline']){
                $startTime = substr($search['due_deadline'],0,10);
                $endTime = substr($search['due_deadline'],13);
                if($startTime && $endTime){
                    $where['a.due_deadline'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
        }
        $where['delete'] = 1;
        $count = M('merchant')->where($where)->join("AS a LEFT JOIN __SCHOOL__ AS b ON a.school_id = b.school_id")->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        $list = M('merchant')->where($where)->join("AS a LEFT JOIN __SCHOOL__ AS b ON a.school_id = b.school_id")
            ->limit($Page->firstRow.','.$Page->listRows)->field("a.ma_id,a.ma_merchantname,a.ma_tel,a.address,a.school_id,is_open,a.due_deadline,b.name as schoolname")->order('a.addtime DESC')->select();
        $schoolData = M('school')->where("is_del=1")->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('search',$search);
        $this->assign('schoolData',$schoolData);
        $this->display();
    }

    //商家状态操作
    public function businessState()
    {
        $id=I('x');
        $search = I('search');
        if (empty($id)){
            $this->error('商家ID不存在',U('businessList'),0);
        }
        $status=M('merchant')->where(array('ma_id'=>$id))->getField('is_open');//判断当前状态情况
        if($status==1){
            $statedata = array('is_open'=>2);
            M('merchant')->where(array('ma_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('businessList',array('p'=>I('p',1),'search'=>$search)),0);
        }else{
            $statedata = array('is_open'=>1);
            M('merchant')->where(array('ma_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('businessList',array('p'=>I('p',1),'search'=>$search)),0);
        }
    }

    //添加商家
    public function businessAdd()
    {
        if(IS_POST){
            $data['ma_merchantname']= I('ma_merchantname');
            $data['ma_tel']         = I('ma_tel');
            $data['ma_account']     = I('ma_account');
            $pass = M('merchant')->where(array('ma_account'=>$data['ma_account']))->find();
            if($pass){
                $this->error("此账号已添加！",U("businessList"),2);
            }
            $admin_pwd_salt=Stringnew::randString(10);
            $data['ma_pwd_salt']=$admin_pwd_salt;
            $data['ma_pwd']=encrypt_password(I('ma_pwd'),$admin_pwd_salt);
            $data['mention']    = I('mention');
            $data['school_id']  = I('school_id');
            $data['proviceid']  = I('provinceid');
            $data['cityid']     = I('cityid');
            $data['countyid']   = I('countyid');
            $data['address']    = I('address');
            $dt                 = explode(",",I("map"));
            $data["latitude"]   = $dt[1];
            $data["longitude"]  = $dt[0];
            if($data["latitude"] == ''){
                $this->error("地图定位不能为空！",U("businessList"),2);
            }
            $Ttime = I('start_time');
            $array=explode('-', $Ttime);
            $data['start_time'] = strtotime(date('Y-m-d',time())." ".$array[0]);
            $data['end_time'] = strtotime(date('Y-m-d',time())." ".$array[1]);
            if($data['start_time'] == '' || $data['end_time'] == ''){
                $this->error("营业时间不能为空！",U("businessList"),2);
            }
            if($data['start_time']>=$data['end_time']){
                $this->error("营业开始时间要小于营业结束时间！",U("businessList"),2);
            }
            $data['due_deadline'] = strtotime(I('due_deadline')." 23:59:59");
            $data['addtime'] = time();
            $res = M('merchant')->add($data);
            if($res){
                $this->success("添加成功！",U("businessList"),1);
            }else{
                $this->error("添加失败！",U("businessList"),2);
            }

        }
        //查找学校
        $schoolData = M('school')->where("is_del=1")->select();
        $this->assign('schoolData',$schoolData);
        //查找省市县
        $province = getRegion(0);
        $this->assign('province',$province);
        $this->assign('time',date('Y-m-d',time()));
        $this->display();
    }

    //公众号设置
    public function operatorList()
    {
        if(IS_AJAX){
            $ma_id                  = trim(I('ma_id'));
            $data['appid']          = trim(I('appid'));
            $data['appsecret']      = trim(I('appsecret'));
            $data['partnerid']      = trim(I('partnerid'));
            $data['keycode']        = trim(I('keycode'));
            $data['partner_name']   = trim(I('partner_name'));
            $data['token']          = trim(I('token'));
            $data['wenben']         = trim(I("wenben"));
            $data['qs_through_id']  = trim(I("qs_through_id"));
            $data['qs_refused_id']  = trim(I("qs_refused_id"));
            $data['take_food_id']   = trim(I("take_food_id"));
            $data['been_completed_id']  = trim(I("been_completed_id"));
            $data['yqc_food_id']        = trim(I("yqc_food_id"));
            $data['refund_refused_id']  = trim(I("refund_refused_id"));
            $data['refund_through_id']  = trim(I("refund_through_id"));
            $data['integral_change_id'] = trim(I("integral_change_id"));
            $old_wenben = M("merchant")->where(array("ma_id"=>$ma_id))->getField("wenben");
            if($_FILES){ //images 是你上传的名称
                //获取图片上传后路径
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     314572800;// 设置附件上传大小
                $upload->exts      =     array('pem');// 设置附件上传类型
                $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
                $upload->savePath  =     ''; // 设置附件上传（子）目录
                $upload->saveRule  =     'time';
                $info   =   $upload->upload();
                if($info){
                    foreach($info as $k=> $file){
                        $img[$k]=substr(C('UPLOAD_DIR'),1).$file['savepath'].$file['savename'];
                    }
                }else{
                    $this->error("上传失败");
                }
                $data['payment_certificate'] = $img['payment_certificate'];
                $data['keycode_certificate'] = $img['keycode_certificate'];
            }
            if($old_wenben != $data['wenben']){
                file_put_contents('MP_verify_'.$data['wenben'].'.txt', $data['wenben']);
                $old_file = 'MP_verify_'.$old_wenben.'.txt';
                if($old_file){
                    unlink($old_file);
                }
            }
            M("merchant")->where(array("ma_id"=>$ma_id))->save($data);
            $this->success("保存成功！",U("businessList",array('p'=>I('p',1))),1);
        }else{
            $ma_id = I("ma_id");
            $storeData = M("merchant")->where(array("ma_id"=>$ma_id))->find();
            $this->assign('storeData',$storeData);
            $this->display();
        }
    }

    //商家编辑
    public function businessEdit()
    {
        $ma_id = I('ma_id');
        if(IS_POST){
            $ma_id = I('ma_id');
            $data['ma_merchantname']= I('ma_merchantname');
            $data['ma_tel']         = I('ma_tel');
            $data['ma_account']     = I('ma_account');
            $pass = M('merchant')->where(array('ma_account'=>$data['ma_account'],'ma_id'=>array('neq',$ma_id)))->find();
            if($pass){
                $this->error("此账号已添加！",U("businessList"),2);
            }
            $ma_pwd = I('ma_pwd');
            if($ma_pwd){
                $admin_pwd_salt=Stringnew::randString(10);
                $data['ma_pwd_salt']=$admin_pwd_salt;
                $data['ma_pwd']=encrypt_password(I('ma_pwd'),$admin_pwd_salt);
            }
            $data['mention']   = I('mention');
            $data['school_id'] = I('school_id');
            $data['proviceid'] = I('provinceid');
            $data['cityid']    = I('cityid');
            $data['countyid']  = I('countyid');
            $data['address']   = I('address');
            $dt                = explode(",",I("map"));
            $data["latitude"]  = $dt[1];
            $data["longitude"] = $dt[0];
            if($data["latitude"] == ''){
                $this->error("地图定位不能为空！",U("businessList"),2);
            }
            $Ttime = I('start_time');
            $array=explode('-', $Ttime);
            $data['start_time'] = strtotime(date('Y-m-d',time())." ".$array[0]);
            $data['end_time'] = strtotime(date('Y-m-d',time())." ".$array[1]);
            if($data['start_time'] == '' || $data['end_time'] == ''){
                $this->error("营业时间不能为空！",U("businessList"),2);
            }
            if($data['start_time']>=$data['end_time']){
                $this->error("营业开始时间要小于营业结束时间！",U("businessList"),2);
            }
            $data['due_deadline'] = strtotime(I('due_deadline')." 23:59:59");
            $data['addtime'] = time();
            $res = M('merchant')->where("ma_id='$ma_id'")->save($data);
            if($res){
                $this->success("编辑成功！",U("businessList",array('p'=>I('p',1))),1);
            }else{
                $this->error("编辑失败！",U("businessList"),2);
            }

        }
        //商家信息
        $info = M('merchant')->where("ma_id='$ma_id'")->find();
        $info['lat_lon'] = $info['longitude'].','.$info['latitude'];
        $info['st_en'] = date('H:i:s',$info['start_time']).' - '.date('H:i:s',$info['end_time']);
        $this->assign('info',$info);
        //查找学校
        $schoolData = M('school')->where("is_del=1")->select();
        $this->assign('schoolData',$schoolData);
        //省份列表
        $province = M('region')->where(array('pid'=>0))->order('cityid')->select();
        //市列表
        $city = M('region')->where(array('pid'=>$info['proviceid']))->order('cityid')->select();
        //县区列表
        $county = M('region')->where(array('pid'=>$info['cityid']))->order('cityid')->select();
        $this->assign('province',$province);
        $this->assign('city',$city);
        $this->assign('county',$county);
        $this->assign('time',date('Y-m-d',time()));
        $this->display();
    }

    //商家档口列表
    public function businessStall()
    {
        $where = array();
        $search = I('search');
        $ma_id = I('ma_id');
        if($search){
            if($search['is_freeze']){
                $where['is_freeze'] =  array('eq',$search['is_freeze']);
            }
            if($search['addtime']){
                $startTime = substr($search['addtime'],0,10);
                $endTime = substr($search['addtime'],13);
                if($startTime && $endTime){
                    $where['addtime'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
            if($search['stall_name']){
                $where['stall_name'] = array('like','%'.trim($search['stall_name']).'%');
            }
            if($search['stall_tel']){
                $where['stall_tel'] = array('like','%'.trim($search['stall_tel']).'%');
            }
        }
        $where['delete'] = array('eq',2);
        $where['ma_id'] = array('eq',$ma_id);
        //分页
        $count= M('stall')->where($where)->count();
        $Page= new \Think\Page($count,15);
        $page= $Page->show();
        $list = M('stall')->where($where)->order("addtime DESC")->select();
        $map = array();
        foreach ($list as $key=>$value){
            $starttime       = strtotime(date('Y') . '-' . date('m') . '-1');
            $endtime       = strtotime(date('Y') . '-' . date('m') . '-' . date('t', strtotime($starttime)));
            $map['b.stall_id'] = $value['stall_id'];
            $map['b.order_status'] = 5;
            $map['b.create_time'] = array('egt',$starttime);
            $map['b.create_time'] = array('elt',$endtime);
            $goodsList = M('orders')->join("AS b LEFT JOIN __ORDER_GOODS__ as c on b.order_id = c.order_id")->where($map)->sum('dishes_nums');
            if(empty($goodsList)){
                $list[$key]['sales'] = 0;
            }else{
                $list[$key]['sales'] = $goodsList;
            }
        }
        $shopName = M('merchant')->where("ma_id='$ma_id'")->getField("ma_merchantname");
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->assign('ma_id',$ma_id);
        $this->assign('shopName',$shopName);
        $this->display();
    }

    //档口状态操作
    public function stallState()
    {
        $id=I('x');
        $search = I('search');
        $ma_id = I('ma_id');
        if (empty($id)){
            $this->error('用户ID不存在',U('businessStall',array('p'=>I('p',1),'ma_id'=>$ma_id,'search'=>$search)),0);
        }
        $status=M('stall')->where(array('stall_id'=>$id))->getField('is_freeze');//判断当前状态情况
        if($status==1){
            $statedata = array('is_freeze'=>2);
            M('stall')->where(array('stall_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('businessStall',array('p'=>I('p',1),'ma_id'=>$ma_id,'search'=>$search)),0);
        }else{
            $statedata = array('is_freeze'=>1);
            M('cart')->where(array('stall_id'=>$id))->delete();
            M('member_collection')->where(array('stall_id'=>$id))->delete();
            M('stall')->where(array('stall_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('businessStall',array('p'=>I('p',1),'ma_id'=>$ma_id,'search'=>$search)),0);
        }
    }

    //档口详情
    public function stallDetail()
    {
        $stall_id = I('stall_id');
        $ma_id = I('ma_id');
        $infos = M('stall')->where(array('stall_id'=>$stall_id))->find();
        $starttime       = strtotime(date('Y') . '-' . date('m') . '-1');
        $endtime       = strtotime(date('Y') . '-' . date('m') . '-' . date('t', strtotime($starttime)));
        $where1['b.stall_id'] = $stall_id;
        $where1['b.order_status'] = 5;
        $where1['b.create_time'] = array('egt',$starttime);
        $where1['b.create_time'] = array('elt',$endtime);
        $goodsList = M('orders')->alias('b')->join("LEFT JOIN __ORDER_GOODS__ as c on b.order_id = c.order_id")->where($where1)->sum('dishes_nums');
        if(empty($goodsList)){
            $infos['sales'] = 0;
        }else{
            $infos['sales'] = $goodsList;
        }
        $shopName = M('merchant')->where("ma_id='$ma_id'")->getField("ma_merchantname");
        $this->assign('shopName',$shopName);
        $this->assign('infos',$infos);
        $this->display();
    }

    //删除商家
    public function businessDel()
    {
        $ma_id = I('ma_id');
        $where['ma_id'] = array('eq',$ma_id);
        $where['order_status'] = array('in','2,3,4');
        $pass = M('orders')->where($where)->find();
        if($pass){
            $this->error('该商户存在未完成订单，不允许删除',1,2);
        }
        $res = M('merchant')->where(array('ma_id'=>$ma_id))->setField('delete',2);
        if($res){
            M('stall')->where(array('ma_id'=>$ma_id))->setField("delete",1);
            M('dishes')->where(array('ma_id'=>$ma_id))->setField("is_del",1);
            M('dishes_category')->where(array('ma_id'=>$ma_id))->setField("is_del",1);
            M('orders')->where(array('order_status'=>1,'ma_id'=>$ma_id))->setField("order_status",7);
            M('member_list')->where(array('ma_id' =>$ma_id))->setField('is_del',1);
            /*$list = $ordersDb->where(array('order_status'=>1,'ma_id'=>$ma_id))->field("order_id,order_type,dishes_id")->select();
            foreach ($list as $key=>$value){
                if($value['order_type'] == 2){
                    $dishesDb->where(array('dishes_id'=>$value['dishes_id']))->setInc("num",1);
                }else{
                    $goodsData = $orderGoodsDb->where(array('order_id'=>$value['order_id']))->field("dishes_id")->select();
                    foreach ($goodsData as $k=>$v){
                        $dishesDb->where(array('dishes_id'=>$v['dishes_id']))->setInc('num',$v['dishes_nums']);
                    }
                }
                $ordersDb->where(array('order_id'=>$value['order_id']))->setField("order_status",7);
            }*/
            $this->success('删除成功',U('businessList',array('p'=>I('p',1),'ma_id'=>$ma_id)),0);
        }else{
            $this->error('删除失败',1,2);
        }
    }

}