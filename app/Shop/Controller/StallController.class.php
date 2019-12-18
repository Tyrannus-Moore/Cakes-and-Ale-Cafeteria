<?php
// +----------------------------------------------------------------------
// | 功能:档口管理
// +----------------------------------------------------------------------
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
use Think\Verify;
use Org\Util\Stringnew;
class StallController extends ShopAuthController
{
    // 档口列表
    public function stallList()
    {
        $where = array();
        $ma_id = session('ma_id');
        $where['a.delete'] = 2;
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
            if($search['stall_name']){
                $where['a.stall_name'] = array('like','%'.trim($search['stall_name']).'%');
            }
            if($search['stall_tel']){
                $where['a.stall_tel'] = array('like','%'.trim($search['stall_tel']).'%');
            }
            if($search['score']){
                $where['a.score'] = $search['score'];
            }
        }
        //分页
        $count= M('stall')->alias('a')->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('stall')->alias('a')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order("a.sort ASC,a.addtime DESC")
            ->select();

        /*$orders_db = M('orders');
        foreach ($list as $key=>$value){
            //时间
            $time = time();
            $starttime = strtotime(date('Y-m-d',strtotime(date("Y-m",strtotime("last month")))));
            $endtime = strtotime(date('Y-m-d',strtotime(date("Y-m",$time))));
            $where1['b.stall_id'] = $value['stall_id'];
            $where1['b.order_status'] = array('in','5,6');
            $where1['b.create_time'] = array('egt',$starttime);
            $where1['b.create_time'] = array('elt',$endtime);
            $goodsList = $orders_db->alias('b')
                ->join("LEFT JOIN __ORDER_GOODS__ as c on b.order_id = c.order_id")
                ->where($where1)
                ->sum('dishes_nums');
            if(empty($goodsList)){
                $list[$key]['sales'] = 0;
            }else{
                $list[$key]['sales'] = $goodsList;
            }
        }*/

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //添加档口
    public function stallAdd()
    {
        if(IS_AJAX){
            $stall_db = M('stall');
            $ma_id = session('ma_id');
            $st_account = I('st_account');//档口账号
            $stall_name = I('stall_name');//档口名称
            $ret1 = $stall_db->where(array('delete'=>2,'st_account'=>$st_account))->count();
            if ($ret1){
                $this->error('账号已存在！');
            }
            $ret2 = $stall_db->where(array('delete'=>2,'ma_id'=>$ma_id,'stall_name'=>$stall_name))->count();
            if ($ret2){
                $this->error('档口名称已存在！');
            }
            //图片
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath  =     'Stall/'; // 设置附件上传（子）目录
            $upload->saveRule  =     'time';
            //$upload->autoSub = false;
            $info   =   $upload->upload();
            if($info) {
                $data['image'] = substr(C('UPLOAD_DIR'),1).$info['img']['savepath'].$info['img']['savename'];//如果上传成功则完成路径拼接
            }else{
                $this->error('图片必须',U('stallAdd'),0);
            }

            $data['ma_id'] = $ma_id;
            $data['stall_name'] = $stall_name;
            $data['st_account'] = $st_account;
            $ma_pwd_salt=Stringnew::randString(10);
            $data['st_pwd'] = encrypt_password(I('st_pwd'),$ma_pwd_salt);
            $data['ma_pwd_salt'] = $ma_pwd_salt;
            $data['stall_tel'] = I('stall_tel');
            $data['stall_type'] = I('stall_type');
            $data['sort'] = I('sort');
            $data['addtime'] = time();

            $res = $stall_db->add($data);
            if ($res){
                //添加提示音
                $data = [
                    'stall_id' => $res,
                    'music' => '/data/upload/avatar/fb4d7e34-4b82-44d1-8246-0f1ceea61d89.mp3',
                    'music_name' => '系统默认提示音',
                    'music_type' => 1,
                    'music_state' => 1,
                    'addtime' => time(),
                ];
                M('stall_music')->add($data);

                $this->success('添加成功',U('stallList'));
            }else{
                $this->error('添加失败！');
            }
        }else{
            $this->display();
        }
    }

    //档口提示音（测试用）
    public function test($stall_id)
    {
        $stall_music_db = M('stall_music');
        $ret = $stall_music_db->where(array('stall_id'=>$stall_id,'music_state'=>1))->find();
        if (!$ret){
            //添加提示音
            $data = [
                'stall_id' => $stall_id,
                'music' => '/data/upload/avatar/fb4d7e34-4b82-44d1-8246-0f1ceea61d89.mp3',
                'music_name' => '系统默认提示音',
                'music_type' => 2,
                'music_state' => 1,
                'addtime' => time(),
            ];
            $stall_music_db->add($data);
        }
    }

    //编辑档口
    public function stallEdit()
    {
        $stall_db = M('stall');
        if (IS_AJAX){
            $ma_id = session('ma_id');
            $stall_id = I('stall_id');
            $st_account = I('st_account');//档口账号
            $stall_name = I('stall_name');//档口名称
            $st_pwd = I('st_pwd');
            //验证
            $ret1 = $stall_db->where(array('delete'=>2,'stall_id'=>array('neq',$stall_id),'st_account'=>$st_account))->count();
            if ($ret1){
                $this->error('账号已存在！');
            }
            $ret2 = $stall_db->where(array('delete'=>2,'stall_id'=>array('neq',$stall_id),'ma_id'=>$ma_id,'stall_name'=>$stall_name))->count();
            if ($ret2){
                $this->error('档口名称已存在！');
            }
            //图片
            if($_FILES){
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
            }
            //密码
            if(!empty($st_pwd)){
                $ma_pwd_salt = Stringnew::randString(10);
                $data['st_pwd'] = encrypt_password($st_pwd,$ma_pwd_salt);
                $data['ma_pwd_salt'] = $ma_pwd_salt;
            }
            $data['stall_name'] = $stall_name;
            $data['st_account'] = $st_account;
            $data['stall_tel'] = I('stall_tel');
            $data['stall_type'] = I('stall_type');
            $data['sort'] = I('sort');
            $res = $stall_db->where("stall_id='$stall_id'")->save($data);
            if ($res !== false){
                $this->test($stall_id);
                $this->success('编辑成功！',U('stallList',array('p'=>I('p',1))),1);
            }else{
                $this->error('没有任何修改！');
            }
        }else{
            $stall_id = I('stall_id');
            $infos = $stall_db->where(array('stall_id'=>$stall_id))->find();

            $this->assign('infos',$infos);
            $this->assign('p',I('p',1));
            $this->display();
        }
    }

    //档口详情
    public function stallDetail()
    {
        $stall_id = I('stall_id');
        $infos = M('stall')->where(array('stall_id'=>$stall_id))->find();
        /*//时间
        $time = time();
        $starttime = strtotime(date('Y-m-d',strtotime(date("Y-m",strtotime("last month")))));
        $endtime = strtotime(date('Y-m-d',strtotime(date("Y-m",$time))));
        $where1['b.stall_id'] = $stall_id;
        $where1['b.order_status'] = array('in','5,6');
        $where1['b.create_time'] = array('egt',$starttime);
        $where1['b.create_time'] = array('elt',$endtime);
        $goodsList = M('orders')->alias('b')
            ->join("LEFT JOIN __ORDER_GOODS__ as c on b.order_id = c.order_id")
            ->where($where1)
            ->sum('dishes_nums');
        if(empty($goodsList)){
            $infos['sales'] = 0;
        }else{
            $infos['sales'] = $goodsList;
        }*/

        $this->assign('infos',$infos);
        $this->assign('p',I('p',1));
        $this->display();
    }

    // 冻结账号
    public function stallDong()
    {
        $stall_id = I('stall_id');
        M('stall')->where(array('stall_id'=>$stall_id))->setField('is_freeze',1);
        M('member_collection')->where(array('stall_id'=>$stall_id))->delete();
        M('cart')->where(array('stall_id'=>$stall_id))->delete();
        $this->success("冻结档口成功！",U("stallList"),1);
    }

    // 解封账号
    public function stallJie()
    {
        $stall_id = I('stall_id');
        M('stall')->where(array('stall_id'=>$stall_id))->setField('is_freeze',2);
        $this->success("解封档口成功！",U("stallList"),1);
    }

    // 删除档口
    public function stallDel()
    {
        $stall_id = I('stall_id');
        //验证
        $ret = M('orders')->where(array('stall_id'=>$stall_id,'order_status'=>array('LT',5)))->getField('order_id');
        if($ret){
            $this->error('该档口有未完成订单，不允许删除！',U("stallList",array('p'=>I('p',1))),0);
        }
        $res = M('stall')->where(array('stall_id'=>$stall_id))->setField('delete',1);
        if($res !== false){
            M('member_collection')->where(array('stall_id'=>$stall_id))->delete();
            M('cart')->where(array('stall_id'=>$stall_id))->delete();
            $this->success('删除成功！',U("stallList",array('p'=>I('p',1))),1);
        }else{
            $this->error('删除失败！',U("stallList",array('p'=>I('p',1))),0);
        }
    }

}