<?php
// +----------------------------------------------------------------------
// |  积分商城
// +----------------------------------------------------------------------
// |  2019-1-21
// +----------------------------------------------------------------------
// |  杨少雄
// +----------------------------------------------------------------------
namespace Home\Controller;

// use Think\Controller;
use Home\Controller\BaseController;

class IntegralController extends BaseController
// class IntegralController extends Controller
{
    // public function _initialize(){
    //     session('member_list_id',1);
    //     session('ma_id',1);
    // }
    //积分商城
    public function shop()
    {
        //轮播图
        $banner = M('banner')->where(array('is_show'=>1))->select();
        $this->assign('banner',$banner);
        //商品分来
        $category = M('goods_category')->where(array('is_del'=>1))->select();
        $this->assign('category',$category);
        //用户的当前积分
        $integral = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->find();
        $this->assign('integral',$integral);
        //商品列表
        $goods_db = M('goods');
        $where['is_del'] = 1;
        $where['state'] = 1;
        $list = $goods_db->where($where)->order('sort asc,addtime desc')->limit(20)->select();
        $this->assign('list',$list);
        $count = $goods_db->where($where)->count();
        $totalPage = ceil($count/20);//总计页数
        $this->assign('totalPage',$totalPage);
        //判断是不是配送员
        $status = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('status');
        $this->assign('status',$status);
        $this->display();
    }
    //查询
    public function search()
    {
         //商品列表
        $goods_db = M('goods');
        $where['is_del'] = 1;
        $where['state'] = 1;
        //分类
        if(I('cat_id')!=0){
            $where['cat_id'] = I('cat_id');
        }
        if(I('goods_name')){
            $where['goods_name'] = array('like','%'.trim(I('goods_name')).'%');
        }
        // dump($where);
        $list = $goods_db->where($where)->order('addtime desc')->limit(20)->select();
        $count = $goods_db->where($where)->count();
        $list['totalPage'] = ceil($count/20);//总计页数
        $this->ajaxReturn($list);
    }
    //下滑分页
    public function listNext(){

        $p = I('p',0,'intval');
        $size = 20;//每页记录数
        $limitpage = ($p-1)*$size;//每次查
         //商品列表
        $goods_db = M('goods');
        $where['is_del'] = 1;
        $where['state'] = 1;
        //分类
        if(I('cat_id')){
            $where['cat_id'] = I('cat_id');
        }
        if(I('goods_name')){
            $where['goods_name'] = array('like','%'.trim(I('goods_name')).'%');
        }
        $list = $goods_db->where($where)->order('sort asc,addtime desc')->limit($limitpage,$size)->select();
        $this->ajaxReturn($list);
    }
    //商品详情
    public function detail()
    {
        //轮播图
        $goods_id = I('goods_id');
        $goodsInfo = M('goods')->where(array('goods_id'=>$goods_id))->find();
        // dump($goodsInfo);
        $this->assign('goodsInfo',$goodsInfo);
        //用户的当前积分
        $integral = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->find();
        $this->assign('integral',$integral);
        // dump($integral);
        $this->display();
    }
    //确认订单页面
    public function confirmOrder()
    {
        //轮播图
        $goods_id = I('goods_id');

        $goodsInfo = M('goods')->where(array('goods_id'=>$goods_id))->find();
        // dump($goodsInfo);
        $this->assign('goodsInfo',$goodsInfo);   
        $nums = I('nums');
        $this->assign('nums',$nums);
        $this->assign('goods_id',$goods_id);
        $this->assign('total',$nums*$goodsInfo['intergral']);
        //查找用户的配送地址
        $address_id = I('address_id');
        if($address_id){
            $address = M('member_address')->where(array('address_id'=>$address_id))->find();
        }else{
            $address = M('member_address')->where(array('member_list_id'=>session('member_list_id'),'is_default'=>1,'is_del'=>2))->find(); 
        }
        $address['address'] = $address['proviceid'].$address['cityid'].$address['countyid'].$address['address'];
        $this->assign('address_id',$address_id);
        $this->assign('address',$address);
         //用户的当前积分
        $infos = M('member_list')->where(array('a.member_list_id'=>session('member_list_id'),'a.is_del'=>2))->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->find();
        //自取地址
        if($infos['mention'] == 1){
            $infos['ma_address'] = $infos['address'];
            $infos['ma_tel'] = $infos['ma_tel'];
        }else{
            $res = M('merchant')->where(array('school_id'=>$infos['school_id'],'mention'=>1))->limit(1)->find();
            $infos['ma_address'] = $res['address'];
            $infos['ma_tel'] = $res['ma_tel'];
        }
        $this->assign('infos',$infos);
        //查找邮费
        $freight = M('sysconfig')->where(array('varname'=>'cfg_freight'))->getField('value');
        $this->assign('freight',$freight);
        $this->display();
    }
    //自提的确认订单
    public function mention(){
        $goods_id = I('goods_id');
        $nums = I('nums');
        $goodsInfo = M('goods')->where(array('goods_id'=>$goods_id))->find();
        $data['ordersn'] = $this->create_ordersn();
        $data['ma_id'] = session('ma_id');
        $data['goods_id'] = $goods_id;
        $data['member_list_id'] = session('member_list_id');
        $total = $nums*$goodsInfo['intergral'];
        $data['use_integral'] = $total;
        $data['addtime'] = time();
        $data['state'] = 2;
        $data['status'] = 4;
        $data['pay_num'] = $nums;
        $data['telphone'] = I('ma_tel');
        $data['address'] = I('ma_address');
        $data['remark'] = I('remark');
        $result = M('integral_order')->add($data);
        if($result){
            //扣除积分
            M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->setDec('integral',$total);
            //减少库存
            M('goods')->where(array('goods_id'=>$goods_id))->setDec('num',$nums);
            //增加积分记录
            $res['order_no'] = $data['ordersn'];
            $res['ma_id'] = session('ma_id');
            $res['member_list_id'] = session('member_list_id');
            $res['state'] = 2;
            $res['type'] = 2;
            $res['integral'] = $total;
            $res['creattime'] = time();
            M('integral_statistics')->add($res);
            $shopData = M('merchant')->where(array('ma_id'=>session('ma_id')))->field("appid,appsecret,take_food_id")->find();
            $memberData = M('member_list')->where(array('member_list_id'=>session('member_list_id')))->field("openid,member_list_nickname,member_name,telphone,integral")->find();
            //查找推送信息
            $in['type'] = 8;
            $in['appid'] = $shopData['appid'];
            $in['appsecret'] = $shopData['appsecret'];
            $in['template_id'] = $shopData['integral_change_id'];
            $in['openid'] = $memberData['openid'];
            $in['title'] = '您好，您的会员积分信息有了新的变更。';
            $in['keyword1'] = $memberData['member_list_nickname'];
            $in['keyword2'] = $memberData['telphone'];
            $in['keyword3'] = '您有'.$total.'积分消费哦！';
            $in['keyword4'] = $memberData['integral'];
            setMessages($in);
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(2);
        }
    }
     //创建唯一订单号
    public function create_ordersn()
    {
        $sn = rand(1000,9999);
        $ordersn = date('YmdHis',time()).$sn;
        $info = M("integral_order")->where(array('ordersn'=>$ordersn))->find();
        //(!empty($info)) && $order_no = $this->create_ordersn();
        if (!empty($info)) {
            $this->create_ordersn();
        }
        return $ordersn;
    }
    //兑换记录
    public function record(){
        $list = M('integral_order as a')->field('a.*,b.pic_url,b.goods_name,b.intergral')->where(array('a.member_list_id'=>session('member_list_id'),'a.status'=>array('not in','0,5')))->join('LEFT JOIN __GOODS__ AS b ON a.goods_id=b.goods_id')->order('a.addtime desc')->select();
        $this->assign('list',$list);
        $this->display();
    }
    //详情
    public function orderDetail(){
        $infos = M('integral_order as a')->field('a.*,b.pic_url,b.goods_name,b.intergral')->where(array('a.member_list_id'=>session('member_list_id'),'a.order_id'=>I('order_id')))->join('LEFT JOIN __GOODS__ AS b ON a.goods_id=b.goods_id')->find();
        $this->assign('infos',$infos);
        $this->display();
    }
    //取货成功
    public function quhuo(){
        $order_id = I('order_id');
        $data['finishtime'] = time();
        $data['status'] = 3;
        $result = M('integral_order')->where(array('order_id'=>$order_id))->save($data);
        if($result){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(2);
        }
    }
    //收货成功
    public function shouhuo(){
        $order_id = I('order_id');
        $data['finishtime'] = time();
        $data['status'] = 3;
        $result = M('integral_order')->where(array('order_id'=>$order_id))->save($data);
        if($result){
            $infos = M('integral_order')->where(array('order_id'=>$order_id))->find();
            $res['order_no'] = $infos['ordersn'];
            $res['ma_id'] = $infos['ma_id'];
            $res['money'] = $infos['pay_money'];
            $res['state'] = 3;
            $res['type'] = 2;
            $res['statue'] = 1;
            $res['creattime'] = $infos['payment_time'];
            M('finance')->add($res);
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(2);
        }
    }
    //积分流水
    public function integralDetail(){
        $list = M('integral_statistics')->where(array('member_list_id'=>session('member_list_id')))->order('creattime desc')->select();
        $this->assign('list',$list);
        $this->display();
    }
    //添加积分订单
    public function createOrder(){
        $goods_id = I('goods_id');
        $nums = I('nums');
        $goodsInfo = M('goods')->where(array('goods_id'=>$goods_id))->find();
        $data['ordersn'] = $this->create_ordersn();
        $data['ma_id'] = session('ma_id');
        $data['goods_id'] = $goods_id;
        $data['member_list_id'] = session('member_list_id');
        $total = $nums*$goodsInfo['intergral'];
        $data['use_integral'] = $total;
        $data['addtime'] = time();
        $data['state'] = 1;
        $data['status'] = 0;
        $data['pay_num'] = $nums;
        $data['telphone'] = I('telphone');
        $data['username'] = I('name');
        $data['address'] = I('address');
        $data['remark'] = I('remark');
        $data['pay_money'] = I('freight');
        $result = M('integral_order')->add($data);
        $da['status'] = 1;
        $da['order_id'] = $result;
        $this->ajaxReturn($da);
        
    }
    //订单支付
    public function payment()
    {
        $ma_id = session('ma_id');
        $order_id = I('order_id');
        $orderInfo = M('integral_order')->where(array('order_id'=>$order_id))->find();
        $this->assign("orderInfo",$orderInfo);
        // var_dump($ma_id);
        //H5 -JS微信支付
        $wechatInfo= M('merchant')->where(array('ma_id'=>$ma_id))->find();
        // var_dump($wechatInfo);
        $appid = $wechatInfo['appid'];
        $appsecret = $wechatInfo['appsecret'];
        $GLOBALS['appid']=$appid;
        $GLOBALS['appsecret']=$appsecret;
        $GLOBALS['mchid']=$wechatInfo['partnerid'];
        $GLOBALS['keys']=$wechatInfo['keycode'];
        $GLOBALS['curl_proxy_port']=0;//8080;
        $GLOBALS['curl_proxy_host']="0.0.0.0";//"10.152.18.220";
        $GLOBALS['report_levenl']=1;
        $GLOBALS['curl_timeout']=60;
        //支付
        header("Content-type:text/html;charset=utf-8");
        Vendor("Pay.Wxpay.WxPayPubHelper");
        $JsApi_pub = new \JsApi_pub();
        //=========步骤1：网页授权获取用户openid============
        //查找openID
        $openid = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->getField('openid');
        // var_dump(session('member_list_id'));
        if($appid && $appsecret)
        {
            //添加待支付的支付记录
            $integral_data = array(
                'pay_no' => $this->create_ordersn(),
                'member_list_id' => session('member_list_id'),
                'pay_money' => $orderInfo['pay_money'],
                'addtime' => time(),
                'object_no'  => $order_id,
                'state'=> 1,
                'subject'=> '支付邮费',
                'body'=> '微信订单支付',
            );
            M('integral_pay_log')->add($integral_data);
        }
        if($openid)
        {
            $notify_url = 'http://'.$_SERVER['HTTP_HOST'].'/Integral.php';
            //=========步骤2：使用统一支付接口，获取prepay_id============
            $unifiedOrder = new \UnifiedOrder_pub();
            $unifiedOrder->setParameter("openid",$openid);//微信用户    
            $unifiedOrder->setParameter("body",'微信订单支付');//商品描述
            $unifiedOrder->setParameter("out_trade_no",$integral_data['pay_no']);//商户订单号 
            $money = $orderInfo['pay_money'];
            $money = 0.01;
            $unifiedOrder->setParameter("total_fee",$money*100);//总金额
            $unifiedOrder->setParameter("notify_url",$notify_url);//通知地址 
            $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
            $prepay_id = $unifiedOrder->getPrepayId();
            // var_dump($prepay_id);
            $JsApi_pub->setPrepayId($prepay_id['prepay_id']);
            
            $jsApiParameters = $JsApi_pub->getParameters();
            // var_dump($jsApiParameters);
            $this->assign('jsApiParameters',$jsApiParameters);
            //   //支付成功跳转页面
            $success_returnUrl='http://'.$_SERVER['HTTP_HOST'].'/index.php?m=Home&c=Integral&a=shop';
            $this->assign('success_returnUrl',$success_returnUrl);
        }
        $this->display();
    }
    // 支付失败
    public function orderDel(){
        $order_id = I('order_id');
        $result = M('orders')->where(array('order_id'=>$order_id))->delete();
        if($result){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(2);
        }
    }
}