<?php
// +----------------------------------------------------------------------
// |  我的订单
// +----------------------------------------------------------------------
namespace Home\Controller;
class MyorderController extends BaseController
{
    //我的订单（普通订单、周餐订单）
    public function index()
    {
        $member_id = $_SESSION['member_list_id'];
        //$member_id = 1;
        $where['member_list_id'] = $member_id;
        $passStatus = M('orders')->where(array('order_status'=>1,'member_list_id'=>$member_id))->field("order_id,dishes_id,create_time,order_type,refund")->select();
        foreach ($passStatus as $k=>$v){
            if($v['order_type'] == 2){
                if(time()-$v['create_time']>15*60){
                    M('orders')->where(array('order_id'=>$v['order_id']))->setField("order_status",7);
                    M('dishes')->where(array('dishes_id'=>$v['dishes_id']))->setInc("num",1);
                }
            }else{
                if(time()-$v['create_time']>15*60){
                    M('orders')->where(array('order_id'=>$v['order_id']))->setField('order_status',7);
                    // 增加库存
                    $order_goods_db = M('order_goods');
                    $dishes_db = M('dishes');
                    $list = $order_goods_db->where(array('order_id'=>$v['order_id']))->select();
                    foreach ($list as $k1 => $v2){
                        $dishes_db->where(array('dishes_id'=>$v2['dishes_id']))->setInc('num',$v2['dishes_nums']);
                    }
                }
            }
        }
        $orderData = M('orders')->join('AS a LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where($where)
            ->field("a.order_id,a.order_status,a.real_money,a.deliver_type,a.order_type,b.image,b.stall_name,a.refund")->order("a.create_time desc")->select();
        foreach($orderData as $key=>$value){
            if($value['order_type'] == 1){
                $orderData[$key]['goodsData'] = M('order_goods')->where(array('order_id'=>$value['order_id']))->field("id,dishes_name,dishes_nums,discount_price,dishes_price")->order("id asc")->select();
            }else{
                if($value['order_status'] == 4){
                    $passTwo = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>2))->field("type,day,eat_finish_time")->order("id asc")->find();
                    if($passTwo){
                        $orderData[$key]['state'] = 2;//待取餐
                        $orderData[$key]['diningTypeTwo'] = $passTwo['type'];
                        $orderData[$key]['weekTypeTwo'] = $passTwo['day'];
                        $orderData[$key]['goodsData'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>2,'type'=>$passTwo['type'],'day'=>$passTwo['day']))->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
                    }else{
                        $pass = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>1))->field("type,day,eat_finish_time")->order("id asc")->find();
                        $orderData[$key]['state'] = 1;//备餐中
                        $orderData[$key]['diningTypeTwo'] = $pass['type'];
                        $orderData[$key]['weekTypeTwo'] = $pass['day'];
                        $orderData[$key]['goodsData'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
                    }
                }elseif($value['order_status'] == 5 || $value['order_status'] == 6 || $value['order_status'] == 7 || $value['order_status'] == 1 || $value['order_status'] == 2 || $value['order_status'] == 8){
                    if($value['order_status'] == 7){
                        $orderData[$key]['state'] = 4;//已取消
                    }elseif($value['order_status'] == 1){
                        $orderData[$key]['state'] = 5;//待付款
                    }elseif($value['order_status'] == 2){
                        $orderData[$key]['state'] = 6;//已付款
                    }else{
                        $orderData[$key]['state'] = 3;//已完成
                    }
                    $pass = M('order_goods')->where(array('order_id'=>$value['order_id']))->field("type,day,eat_finish_time")->order("id asc")->find();
                    $orderData[$key]['diningTypeTwo'] = $pass['type'];
                    $orderData[$key]['weekTypeTwo'] = $pass['day'];
                    $orderData[$key]['goodsData'] = M('order_goods')->where(array('order_id'=>$value['order_id'],'type'=>$pass['type'],'day'=>$pass['day']))->field("id,dishes_name,dishes_nums,dishes_price")->order("id asc")->select();
                }
            }
        }
        //dump($orderData);
        $ma_id = session('ma_id');
        $maInfo = M('merchant')->where(array('ma_id'=>$ma_id))->find();
        if(time() >= strtotime(date("Y-m-d").date("H:i:s",$maInfo['start_time'])) && time() <= strtotime(date("Y-m-d").date("H:i:s",$maInfo['end_time']))){
            $maInfo['is_yy'] = 1;
        }else{
            $maInfo['is_yy'] = 2;
        }
        $infos = M('member_list')->where(array('member_list_id'=>$member_id))->field("status")->find();
        $this->assign('infos',$infos);
        $this->assign('maInfo',$maInfo);
        $this->assign('orderData',$orderData);
        $this->display();
    }

    //周餐订单详情
    public function orderDetails()
    {
        $order_id = I('order_id');
        $ordersDb = M('orders');
        $passStatus = $ordersDb->where(array('order_id'=>$order_id))->field("dishes_id,create_time,order_status,stall_id,success_time")->find();
        if($passStatus['order_status'] == 1){
            if(time()-$passStatus['create_time']>15*60){
                $ordersDb->where(array('order_id'=>$order_id))->setField("order_status",7);
                M('dishes')->where(array('dishes_id'=>$passStatus['dishes_id']))->setInc("num",1);
            }else{
                $timeT = $passStatus['create_time']+15*60-time();

                $this->assign('order_id',$order_id);
                $this->assign('timeT',$timeT);
            }
        }
        $list = $ordersDb->join('AS a LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')->where(array('a.order_id'=>$order_id))->field("a.*,b.stall_name")->find();
        $list['startTime'] = $starTime = date("Y-m-d",$list['effect_time']);
        $list['endTime'] = date('Y-m-d',strtotime("$starTime+1week"));
        if($list['order_status'] == 1 || $list['order_status'] == 2 || $list['order_status'] == 5 || $list['order_status'] == 6 || $list['order_status'] == 7 || $list['order_status'] == 8){
            $pass = M('order_goods')->where(array('order_id'=>$order_id))->field("type,day")->order("id asc")->find();
            $list['diningType'] = $pass['type'];
            $list['weekType'] = $pass['day'];
            $goodsData = M('order_goods')->where(array('order_id'=>$order_id,'type'=>$pass['type'],'day'=>$pass['day']))->field("id,dishes_name,dishes_nums,dishes_price,meal_code")->order("id asc")->select();
            $list['meal_code'] = $goodsData[0]['meal_code'];
        }else{
            $passTwo = M('order_goods')->where(array('order_id'=>$order_id,'state'=>2))->field("type,day,eat_finish_time")->order("id asc")->find();
            if($passTwo){
                $list['state'] = 2;//待取餐
                $list['diningType'] = $passTwo['type'];
                $list['weekType'] = $passTwo['day'];
                $goodsData = M('order_goods')->where(array('order_id'=>$order_id,'state'=>2,'type'=>$passTwo['type'],'day'=>$passTwo['day']))->field("id,dishes_name,dishes_nums,dishes_price,meal_code,eat_finish_time")->order("id asc")->select();
                $list['meal_code'] = $goodsData[0]['meal_code'];
                //取餐超时时间
                $dd = M('stall')->where(array('a.stall_id'=>$passStatus['stall_id']))
                    ->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->getField("b.timeout");
                $dt=date('Y-m-d H:i:s',$goodsData[0]['eat_finish_time']);
                $dd = $dd*60;
                $endTimes = date("Y-m-d H:i:s",strtotime("$dt   +".$dd."   minute"));
                $this->assign("endTimes",$endTimes);
            }else{
                $pass = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1))->field("type,day,eat_finish_time")->order("id asc")->find();
                $list['state'] = 1;//备餐中
                $list['diningType'] = $pass['type'];
                $list['weekType'] = $pass['day'];
                $goodsData = M('order_goods')->where(array('order_id'=>$order_id,'state'=>1,'type'=>$pass['type'],'day'=>$pass['day']))->field("id,dishes_name,dishes_nums,dishes_price,meal_code")->order("id asc")->select();
                $list['meal_code'] = $goodsData[0]['meal_code'];
            }
        }
        if($list['order_status'] == 6){
            $comment_list = M('comment')->join("AS a LEFT JOIN __MEMBER_LIST__ as b on a.member_list_id = b.member_list_id")->where(array('a.order_id'=>$order_id))->field("a.*,b.member_list_headpic,b.member_list_nickname")->find();
            if (!empty($comment_list['image'])){
                $comment_list['image'] = explode(',',$comment_list['image']);
            }
        }
        $this->assign('list',$list);
        $this->assign('goodsData',$goodsData);
        $this->assign('comment_list',$comment_list);
        $this->display();
    }

    //周餐小订单商品
    public function NextOrder()
    {
        $orderId    = I('orderId');
        $diningType = I('diningType');
        $weekType   = I('weekType');
        $list = M('order_goods')->where(array('order_id'=>$orderId,'type'=>$diningType,'day'=>$weekType))->field("id,dishes_name,state,dishes_nums,dishes_price")->order("id asc")->select();
        $this->ajaxReturn($list);
    }

    //取消订单
    public function cancelOrder()
    {
        $ordersDb = M('orders');
        $order_id = I('id');
        $res = $ordersDb->where(array('order_id'=>$order_id))->save(array('order_status'=>7));
        if($res !== false){
            $dishes_id = $ordersDb->where(array('order_id'=>$order_id))->getField("dishes_id");
            M('dishes')->where(array('dishes_id'=>$dishes_id))->setInc("num",1);
            $this->success('订单取消成功！',U('index'),1);
        }else{
            $this->error('订单取消失败！',1,2);
        }
    }

    //判断是否打烊
    public function goPayMoney()
    {
        $ordersDb = M('orders');
        $order_id = I('id');
        $dishes_id = $ordersDb->where(array('order_id'=>$order_id))->getField("dishes_id");
        $shopData = M('dishes')->join("AS a LEFT JOIN __MERCHANT__ as c on a.ma_id = c.ma_id")->where("a.dishes_id='$dishes_id'")->field("a.num,c.start_time,c.end_time")->find();
        $startTime = date("Y-m-d",time())." ".date("H:i",$shopData['start_time']);
        $endTime = date("Y-m-d",time())." ".date("H:i",$shopData['end_time']);
        $status = M('orders')->where(array('order_id'=>$order_id))->getField("order_status");
        if($status != 1){
            $arr['memage'] = '订单异常,请刷新页面';
            $arr['code'] = 2;
            $this->ajaxReturn($arr);
        }
        if(strtotime($startTime)>time() || strtotime($endTime)<time()){
            $arr = array();
            $arr['memage'] = '餐厅已打烊！餐厅营业时间为：'.date("H:i",$shopData['start_time']).'～'.date("H:i",$shopData['end_time']);
            $arr['code'] = 2;
            $this->ajaxReturn($arr);
        }else{
            $arr = array();
            $arr['memage'] = '下单成功！';
            $arr['url'] = '/index.php?m=Home&c=Package&a=pay&order_id='.$order_id;
            $arr['code'] = 1;
            $this->ajaxReturn($arr);
        }
    }

    //送达订单
    public function deliveryOrder()
    {
        $order_id = I('id');
        $res = M('orders')->where(array('order_id'=>$order_id))->save(array('order_status'=>5));
        $orderInfo = M('orders')->where(array('order_id'=>$order_id))->find();
        if($res !== false){
            M('dishes')->where(array('dishes_id'=>$orderInfo['dishes_id']))->setInc('on_the_pin',1);
            M('stall')->where(array('stall_id'=>$orderInfo['stall_id']))->setInc('on_the_pin',1);
            $this->success('订单送达成功！',U('index'),1);
        }else{
            $this->error('订单送达失败！',1,2);
        }
    }

    //评价订单
    public function evaluation()
    {
        $order_id = I('order_id');
        $type = M('orders')->where(array('order_id'=>$order_id))->getField("deliver_type");
        if($type == 1){
            $list = M('orders')->where(array('order_id'=>$order_id))->join('AS a LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')
            ->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.ps_id = c.member_list_id')
            ->field("a.order_id,a.ma_id,a.ps_id,a.stall_id,c.member_name,c.member_list_headpic,c.member_list_nickname,b.image,b.stall_name")
            ->find();
        }else{
            $list = M('orders')->where(array('order_id'=>$order_id))->join('AS a LEFT JOIN __STALL__ AS b ON a.stall_id = b.stall_id')
            ->field("a.order_id,a.ma_id,a.ps_id,a.stall_id,b.image,b.stall_name")
            ->find();
        }
        $this->assign('type',$type);
        $this->assign('list',$list);
        $this->display();
    }

    //评价订单操作
    public function evaluationSub()
    {
        $data['order_id']       = I('order_id');
        $pass = M('orders')->where(array('order_id'=>$data['order_id']))->field("order_status,member_list_id,dishes_id,deliver_type,order_type")->find();
        if($pass['order_status'] == 6){
            $this->success('已评价！',U('index'),1);
        }
        $data['ma_id']          = I('ma_id');
        $data['stall_id']       = I('stall_id');
        if($pass['deliver_type'] == 1){
            $data['marki_score']    = I('eveSenderEveXing');
            $data['marki_content']  = I('markiText');
            $data['ps_id']          = I('ps_id');
        }
        $data['member_list_id'] = $pass['member_list_id'];
        $data['dish_score']     = I('productsXing');
        $data['service_score']  = I('serviceXing');
        $data['content']        = I('stallText');
        $data['addtime']        = time();
        $data['image']          = I('files');
        $res = M('comment')->add($data);
        if($res !== false){
            $dishesDb = M('dishes');
            M('orders')->where(array('order_id'=>$data['order_id']))->setField("order_status",6);
            if($pass['deliver_type'] == 1){
                $memberScore = M('member_list')->where(array('member_list_id'=>$data['ps_id']))->getField("score");
                $memberScore = ceil(($memberScore+$data['marki_score'])/2);
                M('member_list')->where(array('member_list_id'=>$data['ps_id']))->setField("score",$memberScore);
            }
            $stallScore = M('stall')->where(array('stall_id'=>$data['stall_id']))->getField("score");
            $stallScore = ceil(($stallScore+($data['dish_score']+$data['service_score'])/2)/2);
            M('stall')->where(array('stall_id'=>$data['stall_id']))->setField("score",$stallScore);
            if($pass['order_type'] == 1){
                $goodsData = M('order_goods')->where(array('order_id'=>$data['order_id']))->field("dishes_id")->select();
                foreach ($goodsData as $key=>$value){
                    $dishesScore = $dishesDb->where(array('dishes_id'=>$value['dishes_id']))->getField("score");
                    $dishesScore = ceil(($dishesScore+$data['dish_score'])/2);
                    $dishesDb->where(array('dishes_id'=>$value['dishes_id']))->setField("score",$dishesScore);
                }
            }else{
                $goodsData = M('order_goods')->where(array('order_id'=>$data['order_id']))->limit(1)->getField('meal_id');
                $dishesScore = $dishesDb->where(array('dishes_id'=>$goodsData))->getField("score");
                $dishesScore = ceil(($dishesScore+$data['dish_score'])/2);
                $dishesDb->where(array('dishes_id'=>$goodsData))->setField("score",$dishesScore);
            }
            $this->success('评价成功！',U('index'),1);
        }else{
            $this->error('评价失败！',1,2);
        }
    }

    //图片缓存
    public function upload()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728000;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','mp3');// 设置附件上传类型
        $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $upload->saveRule  =     'time';
        $info   =   $upload->upload();
        if($info){
            foreach($info as $file){ //循环存储图片到服务器
                $img['name'] = $_FILES['file']['name'];
                $img['url']  = substr(C('UPLOAD_DIR'),1).$file['savepath'].$file['savename'];
            }
            $this->make_json_result(1,$img);
        }else{
            $this->make_json_error('上传失败');
        }
    }

    //删除图片缓存
    public function delFile()
    {
        $imageUrl = I('imageUrl');
        unlink('.'.$imageUrl);
        $this->ajaxReturn(1);
    }

    //订单超时取消
    public function timeTOrder()
    {
        $order_id = I('order_id');
        $ordersDb = M('orders');
        $dishes_id = $ordersDb->where(array('order_id'=>$order_id))->getField("dishes_id");
        $ordersDb->where(array('order_id'=>$order_id))->setField("order_status",7);
        M('dishes')->where(array('dishes_id'=>$dishes_id))->setInc("num",1);
        $this->ajaxReturn(1);
    }

    // 订单详情
    public function orderDetail(){
        $order_id = I('order_id',1);
        $info = M('orders')->where(array('order_id'=>$order_id))->field('order_status,create_time,refund')->find();
        if($info['order_status'] == 1){
            if(time()-$info['create_time']>15*60){
                $res = M('orders')->where(array('order_id'=>$order_id))->setField('order_status',7);
                // 增加库存
                $order_goods_db = M('order_goods');
                $dishes_db = M('dishes');
                $list = $order_goods_db->where(array('order_id'=>$order_id))->select();
                foreach ($list as $k => $v){
                    $dishes_db->where(array('dishes_id'=>$v['dishes_id']))->setInc('num',$v['dishes_nums']);
                }
            }else{
                $timeT = $info['create_time']+15*60-time();
                $this->assign('order_id',$order_id);
                $this->assign('timeT',$timeT);
            }
        }
        $infos = M('orders as a')
            ->join("LEFT JOIN sm_stall as b on a.stall_id = b.stall_id")
            ->field('a.*,b.stall_name')
            ->where(array('order_id'=>$order_id))
            ->find();
        if($infos['order_status'] == 3){
            //取餐超时时间
            $dd = M('stall')->where(array('a.stall_id'=>$infos['stall_id']))
                ->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->getField("b.timeout");
            $dt=date('Y-m-d H:i:s',$infos['success_time']);
            $dd = $dd*60;
            $endTimes = date("Y-m-d H:i:s",strtotime("$dt   +".$dd."   minute"));
            $this->assign("endTimes",$endTimes);
        }
        $list = M('order_goods as a')
            ->join("LEFT JOIN sm_dishes as b on a.dishes_id = b.dishes_id")
            ->where(array('order_id'=>$order_id))
            ->field('a.*,b.statue,b.price')
            ->select();
        $eval = M('comment as a')
            ->join("LEFT JOIN sm_member_list as b on a.member_list_id = b.member_list_id")
            ->field('a.*,b.member_list_nickname,b.member_list_headpic')
            ->where(array('a.order_id'=>$order_id))
            ->find();
        $eval['edish_score'] = 5-$eval['dish_score'];
        $eval['eservice_score'] = 5-$eval['service_score'];
        if($infos['deliver_type'] == 1){
            $eval['emarki_score'] = 5-$eval['marki_score'];
        }
        if (!empty($eval['image'])){
            $eval['image'] = explode(',',$eval['image']);
        }
        $ps = M('member_list')->where(array('member_list_id'=>$infos['ps_id']))->find();
        $ma_id = session('ma_id');
        $maInfo = M('merchant')->where(array('ma_id'=>$ma_id))->find();
        if(time() >= strtotime(date("Y-m-d").date("H:i:s",$maInfo['start_time'])) && time() <= strtotime(date("Y-m-d").date("H:i:s",$maInfo['end_time']))){
            $maInfo['is_yy'] = 1;
        }else{
            $maInfo['is_yy'] = 2;
        }
        $this->assign('maInfo',$maInfo);
        //dump($eval);die;
        $this->assign('ps',$ps);
        $this->assign('list',$list);
        $this->assign('infos',$infos);
        $this->assign('eval',$eval);
        $this->display();
    }

    // 取消订单
    public function qxOrder(){
        $order_id = I('order_id');
        $res = M('orders')->where(array('order_id'=>$order_id))->setField('order_status',7);
        if($res == false){
            $this->make_json_error('取消失败！',1);
        }
        // 增加库存
        $order_goods_db = M('order_goods');
        $dishes_db = M('dishes');
        $list = $order_goods_db->where(array('order_id'=>$order_id))->select();
        foreach ($list as $k => $v){
            $dishes_db->where(array('dishes_id'=>$v['dishes_id']))->setInc('num',$v['dishes_nums']);
        }
    }

    //申请退款
    public function refundOrder()
    {
        $order_id = I('order_id');
        $data['refund'] = 1;
        $data['user_refuse_reason'] = I('user_refuse_reason');
        $data['refund_time'] = time();
        if($data['user_refuse_reason'] == ''){
            $this->error('退款原因不能为空！',1,2);
        }
        $res = M('orders')->where(array('order_id'=>$order_id))->save($data);
        if($res !== false){
            $this->success('您的退款申请已提交,若档口未在1小时内处理此申请，则自动将订单金额退还给您！',U('index'),1);
        }else{
            $this->error('订单申请退款失败！',1,2);
        }
    }

    //判断订单状态是否正常
    public function passOrder()
    {
        $order_id = I('order_id');
        $status = M('orders')->where(array('order_id'=>$order_id))->getField("order_status");
        if($status != 1){
            $this->error('订单异常,请刷新页面！',1,1);
        }else{
            $this->success('正在跳转！',U('Home/Stall/payNow',array('order_id'=>$order_id)),2);
        }

    }

    // 完成订单
    public function sdOrder(){
        $order_id = I('order_id');
        $res = M('orders')->where(array('order_id'=>$order_id))->setField('order_status',5);
        $infos = M('orders as a')
            ->join("LEFT JOIN sm_member_list as b on a.ps_id = b.member_list_id")
            ->where(array('order_id'=>$order_id))
            ->field('a.*,b.openid')
            ->find();
        // 增加财务审计
        if($infos['deliver_type'] ==1){
            $dataF1['order_no'] = $infos['order_no'];
            $dataF1['ma_id'] = $infos['ma_id'];
            $dataF1['stall_id'] = $infos['stall_id'];
            $dataF1['money'] = $infos['express_money'];
            $dataF1['state'] = 2;
            $dataF1['type'] = 1;
            $dataF1['statue'] = 2;
            $dataF1['creattime'] =$infos['payment_time'];
            $res2 = M('finance')->add($dataF1);
        }
        $dataF['order_no'] = $infos['order_no'];
        $dataF['ma_id'] = $infos['ma_id'];
        $dataF['stall_id'] = $infos['stall_id'];
        $dataF['money'] = $infos['real_money'];
        $dataF['state'] = 1;
        $dataF['type'] = 1;
        $dataF['statue'] = 1;
        $dataF['creattime'] =$infos['payment_time'];
        $res1 = M('finance')->add($dataF);
        //增加积分记录
        $inr['order_no'] = $infos['order_no'];
        $inr['ma_id'] = $infos['ma_id'];
        $inr['member_list_id'] = $infos['member_list_id'];
        $inr['state'] = 1;
        $inr['type'] = 1;
        $inr['integral'] = $infos['integral'];
        $inr['creattime'] = time();
        M('integral_statistics')->add($inr);
        M('member_list')->where(array('member_list_id'=>$infos['member_list_id']))->setInc("integral",$infos['integral']);
        $ordersData = M('orders')->where(array('order_id'=>$order_id))->field("member_list_id,order_no,create_time,integral,stall_id")->find();
        //发送模板
        if(C('IsSetMessages')){
            $ma_id = M('stall')->where(array('stall_id'=>$infos['stall_id']))->getField("ma_id");
            
            $shopData = M('merchant')->where(array('ma_id'=>$infos['ma_id']))->field("appid,appsecret,been_completed_id,integral_change_id")->find();
            $memberData = M('member_list')->where(array('member_list_id'=>$ordersData['member_list_id']))->field("openid,member_list_nickname,telphone,member_name,integral")->find();

            $in['type'] = 8;
            $in['appid'] = $shopData['appid'];
            $in['appsecret'] = $shopData['appsecret'];
            $in['template_id'] = $shopData['integral_change_id'];
            $in['openid'] = $memberData['openid'];
            $in['title'] = '您好，您的会员积分信息有了新的变更。';
            $in['keyword1'] = $memberData['member_list_nickname'];
            $in['keyword2'] = $memberData['telphone'];
            $in['keyword3'] = '您有'.$ordersData['integral'].'积分入户哦！';
            $in['keyword4'] = $memberData['integral'];
            setMessages($in);
        }
        //完成之后增加销量
        $orderGoods = M('order_goods')->where(array('order_id'=>$order_id))->select();
        $dishes_db = M('dishes');
        $totalNum;
        foreach ($orderGoods as $key => $value) {
            $dishes_db->where(array('dishes_id'=>$value['dishes_id']))->setInc('on_the_pin',$value['dishes_nums']);
            $totalNum+=$value['dishes_nums'];
        }
        //档口增加销量
        M('stall')->where(array('stall_id'=>$ordersData['stall_id']))->setInc('on_the_pin',$totalNum);
        // 配送员分成
//        $tade_no = create_pay_no();
//        $qiyeModel = new qiyeModel();
//        $total_fee = $infos['express_money']*100;
//        $check_name = 'NO_CHECK';
//        $orderBody = '用户提现';
//        //查找用户id
//        $openid = $infos['openid'];
//        $response = $qiyeModel->getPrePayOrder($total_fee,$check_name,$orderBody,$openid,$tade_no);
//        if($response['result_code'] == 'SUCCESS'){
//            //提现成功以后 更新表状态
//            //并且记录 流水等等
//
//        }else{
//            //失败
//            $msg = $response['err_code_des'];
//            $this->error("$msg",U("txList"),0);
//        }
        if($res == false){
            $this->make_json_error('收货失败！',1);
        }
    }
}