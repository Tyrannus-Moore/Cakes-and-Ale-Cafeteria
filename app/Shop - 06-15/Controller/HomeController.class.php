<?php
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
class HomeController extends ShopAuthController{
    // 轮播图
    public function banner(){
        $ma_id = session('ma_id');
        $list = M('merchant_banner')->where(array('ma_id'=>$ma_id))->select();
        $count = count($list);
        $this->assign('count',$count);
        $this->assign('list',$list);
        $this->display();
    }

    // 轮播图新增
    public function bannerAdd(){
        $ma_id = session('ma_id');
        if (IS_AJAX){
            $count = M('merchant_banner')->where(array('ma_id'=>$ma_id))->count();
            if($count >4){
                $this->error('最多五张轮播图！',U('banner'),0);
            }
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath  =     'Banner/'; // 设置附件上传（子）目录
            $upload->saveRule  =     'time';
            //$upload->autoSub = false;
            $info   =   $upload->upload();
            if($info) {
                $data['image'] = substr(C('UPLOAD_DIR'),1).$info['img']['savepath'].$info['img']['savename'];//如果上传成功则完成路径拼接
            }else{
                $this->error('图片必须',U('bannerAdd'),0);
            }

            $data['ma_id'] = $ma_id;
            $data['name'] = I('name');
            $data['url'] = I('url');
            $data['edittime'] = time();
            $res = M('merchant_banner')->add($data);
            if($res){
                $this->success('添加成功！',U('banner'),1);
            }else{
                $this->error('添加失败！',U('banner'),0);
            }
        }else{
            $this->display();
        }
    }

    // 轮播图修改
    public function bannerEdit(){
        $banner_id = I('banner_id');
        if (IS_AJAX){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath  =     'Banner/'; // 设置附件上传（子）目录
            $upload->saveRule  =     'time';
            //$upload->autoSub = false;
            $info   =   $upload->upload();
            if($info) {
                $data['image'] = substr(C('UPLOAD_DIR'),1).$info['img']['savepath'].$info['img']['savename'];//如果上传成功则完成路径拼接
            }

            $data['name'] = I('name');
            $data['url'] = I('url');
            $data['edittime'] = time();
            $res = M('merchant_banner')->where(array('banner_id'=>$banner_id))->save($data);
            if($res){
                $this->success('修改成功！',U('banner'),1);
            }else{
                $this->error('修改失败！',U('banner'),0);
            }
        }else{
            $infos = M('merchant_banner')->where(array('banner_id'=>$banner_id))->find();
            $this->assign('infos',$infos);
            $this->display();
        }
    }

    // 轮播图删除
    public function bannerDel(){
        $banner_id = I('banner_id');
        $res = M('merchant_banner')->where("banner_id='$banner_id'")->delete();
        if($res){
            $this->success('删除成功！',U('banner'),1);
        }else{
            $this->error('删除失败！',U('banner'),0);
        }
    }

    // 餐厅饱和度
    public function saturated(){
        $ma_id = session('ma_id');
        if (IS_AJAX){
            $status = I('status');
            $res = M('merchant')->where(array('ma_id'=>$ma_id))->setField('saturation',$status);
            if($res !== false){
                $this->success('修改成功！',U('saturated'),1);
            }else{
                $this->error('修改失败！',U('saturated'),0);
            }
        }else{
            $status = M('merchant')->where(array('ma_id'=>$ma_id))->getField('saturation');
            $this->assign('status',$status);
            $this->display();
        }
    }

    // 配送费设置
    public function distribution(){
        $ma_id = session('ma_id');
        if (IS_AJAX){
            $data['p_money'] = I('value');
            if(!I('status')){
                $this->error('请选择配送方式！',U('distribution'),0);
            }
            $data['status'] = implode(',', I('status'));
            $res = M('merchant')->where(array('ma_id'=>$ma_id))->save($data);
            if($res !== false){
                $this->success('修改成功！',U('distribution'),1);
            }else{
                $this->error('修改失败！',U('distribution'),0);
            }
        }else{
            $value = M('merchant')->where(array('ma_id'=>$ma_id))->getField('p_money');
            $this->assign('value',$value);
            $status = M('merchant')->where(array('ma_id'=>$ma_id))->getField('status');
            $this->assign('status',$status);
            $this->assign('a',1);
            $this->assign('b',2);

            $this->display();
        }
    }

    // 折扣设置
    public function discount()
    {
        $where = array();
        $discount_db = M('discount');
        $ma_id = session('ma_id');
        $where['ma_id'] = $ma_id;
        //分页
        $count= $discount_db->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $discount_db->where($where)->order("addtime DESC")->select();
        foreach ($list as $k => $v){
            $list[$k]['start_time'] =  substr_replace($v['start_time'], ':', -2, 0);
            $list[$k]['end_time'] =  substr_replace($v['end_time'], ':', -2, 0);
        }
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->display();
    }

    // 新增折扣
    public function discountAdd()
    {
        $ma_id = session('ma_id');
        if (IS_AJAX){
            $discount = I('discount');
            $start_time = str_replace(':','',I('start_time'));
            $end_time = str_replace(':','',I('end_time'));

            $ret1 = M('discount')->where(array('ma_id'=>$ma_id,'start_time'=>array('ELT',$start_time),'end_time'=>array('EGT',$start_time)))->find();
            if(!empty($ret1)){
                $this->error('时间不允许重复！');
            }
            $ret2 = M('discount')->where(array('ma_id'=>$ma_id,'start_time'=>array('ELT',$end_time),'end_time'=>array('EGT',$end_time)))->find();
            if(!empty($ret2)){
                $this->error('时间不允许重复！');
            }
            $ret3 = M('discount')->where(array('ma_id'=>$ma_id,'start_time'=>array('EGT',$start_time),'end_time'=>array('ELT',$end_time)))->find();
            if(!empty($ret3)){
                $this->error('时间不允许重复！');
            }
            $data['ma_id'] = $ma_id;
            $data['start_time'] = $start_time;
            $data['end_time'] = $end_time;
            $data['discount'] = $discount;
            $data['addtime'] = time();
            $res = M('discount')->add($data);
            if($res){
                $this->success('添加成功！',U('discount'),1);
            }else{
                $this->error('添加失败！',U('discount'),0);
            }
        }
        $this->display();
    }

    // 编辑折扣
    public function discountEdit()
    {
        $discount_db = M('discount');
        $discount_id  = I('discount_id');
        $ma_id = session('ma_id');
        if (IS_AJAX){
            $discount = I('discount');
            $start_time = str_replace(':','',I('start_time'));
            $end_time = str_replace(':','',I('end_time'));
            $ret1 = M('discount')->where(array('ma_id'=>$ma_id,'start_time'=>array('ELT',$start_time),'end_time'=>array('EGT',$start_time),'discount_id'=>array('NEQ',$discount_id)))->find();
            if(!empty($ret1)){
                $this->error('时间不允许重复！');
            }
            $ret2 = M('discount')->where(array('ma_id'=>$ma_id,'start_time'=>array('ELT',$end_time),'end_time'=>array('EGT',$end_time),'discount_id'=>array('NEQ',$discount_id)))->find();
            if(!empty($ret2)){
                $this->error('时间不允许重复！');
            }
            $ret3 = M('discount')->where(array('ma_id'=>$ma_id,'start_time'=>array('EGT',$start_time),'end_time'=>array('ELT',$end_time),'discount_id'=>array('NEQ',$discount_id)))->find();
            if(!empty($ret3)){
                $this->error('时间不允许重复！');
            }
            $data['start_time'] = $start_time;
            $data['end_time'] = $end_time;
            $data['discount'] = $discount;
            $res = M('discount')->where(array('discount_id'=>$discount_id))->save($data);
            if($res !== false){
                $this->success('修改成功！',U('discount'),1);
            }else{
                $this->error('修改失败！',U('discount'),0);
            }
        }
        $infos = $discount_db->where(array('discount_id'=>$discount_id))->find();
        $infos['start_time'] =  substr_replace($infos['start_time'], ':', -2, 0);
        $infos['end_time'] =  substr_replace($infos['end_time'], ':', -2, 0);
        $this->assign('infos',$infos);
        $this->display();
    }

    // 删除
    public function discountDel()
    {
        $discount_db = M('discount');
        $discount_id = I('discount_id');
        $res = $discount_db->where(array('discount_id'=>$discount_id))->delete();
        if($res !== false){
            $this->success('删除成功！',U("discount"),1);
        }else{
            $this->success('删除失败！',U("discount"),0);
        }
    }

    /*******************************新增功能*************************************/
    // 超时期限
    public function timeout(){
        $ma_id = session('ma_id');
        if (IS_AJAX){
            $value = I('value');
            $res = M('merchant')->where(array('ma_id'=>$ma_id))->setField('timeout',$value);
            if($res !== false){
                $this->success('修改成功！',U('timeout'),1);
            }else{
                $this->error('修改失败！',U('timeout'),0);
            }
        }else{
            $value = M('merchant')->where(array('ma_id'=>$ma_id))->getField('timeout');
            $this->assign('value',$value);
            $this->display();
        }
    }

    //帮助中心
    public function help()
    {
        $class = M('news_category')->order('addtime desc')->select();
        foreach ($class as $key=>$value){
            $class[$key]['help'] = M('news_list')->where(array('new_cate_id'=>$value['new_cate_id']))->order('addtime desc')->select();
        }

        $this->assign('class',$class);
        $this->display();
    }

    //帮助详情
    public function helpDetail()
    {
        $help_id = I('help_id');
        $infos = M('news_list')->alias('a')
            ->join("LEFT JOIN __NEWS_CATEGORY__ as b ON a.new_cate_id = b.new_cate_id")
            ->where(array('help_id'=>$help_id))
            ->find();


        $this->assign('infos',$infos);
        $this->assign('p',I('p',1));
        $this->display();
    }



}