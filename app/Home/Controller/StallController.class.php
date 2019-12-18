<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\qiyeModel;
class StallController extends BaseController
{

    // 档口详情
    public function stallDetail(){
        $member_list_id = session('member_list_id');
        $ma_id = session('ma_id');
        $stall_id = I('stall_id');
        // 档口信息
        $stallInfo = M('stall')->where(array('stall_id'=>$stall_id))->find();

        if($stallInfo['is_freeze'] == 1){
            echo "<script>alert('该档口已打烊！');history.go(-1);</script>";
            die();
            // $this->error('该档口已打烊！',0);die;
        }

        $stallInfo['score'] = sprintf("%.1f",$stallInfo['score']);
        // 是否需收藏
        $is_sc = M('member_collection')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))->select();
        if(empty($is_sc)){
            $stallInfo['is_sc'] = 2;
        }else{
            $stallInfo['is_sc'] = 1;
        }
        // 推荐菜品
        $tuijian = M('dishes')->where(array('stall'=>$stall_id,'statue'=>2,'state'=>1,'is_del'=>2))->order('sort ASC')->limit(6)->select();
        // 类型
        $type = M('dishes_category')->where(array('ma_id'=>$ma_id,'is_del'=>2))->order('sort asc')->select();
        $dishes_db = M('dishes');
        $discount_db = M('discount');
        $cart_db = M('cart');
        $count = $discount_db->where(array('ma_id'=>$ma_id,'start_time'=>array('elt',date("Hi")),'end_time'=>array('egt',date("Hi"))))->field('discount,start_time,end_time')->find();
        foreach ($type as $k => $v){
            $list = $dishes_db->where(array('stall'=>$stall_id,'state'=>1,'is_del'=>2,'dishes_cate_id'=>$v['dishes_cate_id']))->order('sort ASC')->select();
            if(empty($list)){
                unset($type[$k]);
            }else{
                foreach ($list as $ke => $va){
                    $list[$ke]['score'] = sprintf("%.1f",$va['score']);
                    if($va['statue'] == 3){
                        if(!empty($count)){
                            $list[$ke]['discount'] = $count['discount'];
                            $list[$ke]['start_time'] = substr_replace($count['start_time'], ':', -2, 0);
                            $list[$ke]['end_time'] = substr_replace($count['end_time'], ':', -2, 0);
                            $list[$ke]['real'] = sprintf("%.2f",$va['price']*$count['discount']/10);
                        }
                    }elseif($va['statue'] == 2 && $va['discount']!=10){
                        $list[$ke]['discount'] = $va['discount'];
                        $list[$ke]['real'] = sprintf("%.2f",$va['price']*$va['discount']/10);
                    }
                    $num = $cart_db->where(array('stall_id'=>$stall_id,'dishes_id'=>$va['dishes_id'],'member_list_id'=>$member_list_id))->getField('number');
                    if(empty($num)){
                        $list[$ke]['number'] = 0;
                    }else{
                        $list[$ke]['number'] = $num;
                    }
                }
                $type[$k]['dishes'] = $list;
            }
        }
        $end = 5-$stallInfo['score'];
        $is_p = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('is_perfect');
        $maInfo = M('merchant')->where(array('ma_id'=>$ma_id))->find();
        if(time() >= strtotime(date("Y-m-d").date("H:i:s",$maInfo['start_time'])) && time() <= strtotime(date("Y-m-d").date("H:i:s",$maInfo['end_time']))){
            $maInfo['is_yy'] = 1;
        }else{
            $maInfo['is_yy'] = 2;
        }
        $this->assign('maInfo',$maInfo);
        $this->assign('is_p',$is_p);
        $this->assign('end',$end);
        $this->assign('tuijian',$tuijian);
        $this->assign('type',$type);
        $this->assign('stallInfo',$stallInfo);
        //判断现在是打几折
        $this->display();
    }

    // 收藏
    public function collection(){
        $member_list_id = session('member_list_id');
        $stall_id = I('stall_id');
        $member_collection_db = M('member_collection');
        $res = $member_collection_db->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))->getField('collection_id');
        if(empty($res)){
            $data['member_list_id'] = $member_list_id;
            $data['stall_id'] = $stall_id;
            $data['addtime'] = time();
            $member_collection_db->add($data);
            $this->ajaxReturn(1);
        }else{
            $member_collection_db->where(array('collection_id'=>$res))->delete();
            $this->ajaxReturn(2);
        }
    }

    // 购物车
    public function cart(){
        $member_list_id = session('member_list_id');
        $ma_id = session('ma_id');
        $stall_id = I('stall_id');
        $list = M('cart as a')
            ->join("LEFT JOIN sm_dishes as b on a.dishes_id = b.dishes_id")
            ->where(array('a.stall_id'=>$stall_id,'a.member_list_id'=>$member_list_id))
            ->order('a.addtime DESC')
            ->select();
        $discount_db = M('discount');
        $count = $discount_db->where(array('ma_id'=>$ma_id,'start_time'=>array('elt',date("Hi")),'end_time'=>array('egt',date("Hi"))))->getField('discount');
        foreach ($list as $ke => $va){
            if($va['statue'] == 3){
                if(!empty($count)){
                    $list[$ke]['discount'] = $count;
                    $list[$ke]['real'] = sprintf("%.2f",$va['price']*$count/10);
//                    dump(sprintf("%.2f",$va['price']*$count/10));
                }
            }elseif($va['statue'] == 2 && $va['discount']!=10){
                $list[$ke]['discount'] = $va['discount'];
                $list[$ke]['real'] = sprintf("%.2f",$va['price']*$va['discount']/10);
            }
        }
        $this->ajaxReturn($list);
    }

    // 删除购物车
    public function delCar(){
        $member_list_id = session('member_list_id');
        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');
        $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->delete();
        $count = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))->count();
//        dump(M('cart')->getLastSql());die;
        if($res !== false){
            $this->ajaxReturn($count);
        }
    }

    // 添加购物车
    public function addCar(){
        $member_list_id = session('member_list_id');
        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');
        $data['member_list_id'] = $member_list_id;
        $data['stall_id'] = $stall_id;
        $data['dishes_id'] = $dishes_id;
        $data['number'] = 1;
        $data['addtime'] = time();
        $res = M('cart')->add($data);
        if($res !== false){
            $this->ajaxReturn(1);
        }
    }

    // 清空购物车
    public function clearCar(){
        $member_list_id = session('member_list_id');
        $stall_id = I('stall_id');
        $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))->delete();
        if($res !== false){
            $this->ajaxReturn(1);
        }
    }

    // 数量加
    public function incCar(){
        $member_list_id = session('member_list_id');
        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');
        $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->setInc('number',1);
        if($res !== false){
            $this->ajaxReturn(1);
        }
    }

    // 数量减
    public function decCar(){
        $member_list_id = session('member_list_id');
        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');
        $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->setDec('number',1);
        if($res !== false){
            $this->ajaxReturn(1);
        }
    }

    // 档口评价
    public function evaluationList(){
        $stall_id = I('stall_id');
        $comment_db = M('comment');
        $order_goods_db = M('order_goods');
        //总条数
        $num = 5;
        $wherea['a.is_del']  = 2;
        $wherea['a.member_list_id']  = session('member_list_id');
        $wherea['_logic'] = 'or';
        $where['_complex'] = $wherea;
        $where['a.stall_id'] = $stall_id;
        $count = M('comment as a')->where(array('stall_id'=>$stall_id,'is_del'=>2))->count();
        $totalPage = ceil($count/$num);//总计页数
        $data['totalPage'] = $totalPage;
        $data['list']  = M('comment as a')
            ->join("LEFT JOIN sm_orders as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_member_list as c on b.member_list_id = c.member_list_id")
            ->where($where)
            ->limit($num)
            ->order('a.addtime DESC')
            ->field('c.member_list_headpic,c.member_list_nickname,a.dish_score,a.service_score,a.addtime,a.image,a.content,a.order_id')
            ->select();
        // echo M()->_sql();
        foreach ($data['list'] as $k => $v){
            $score = $v['dish_score']+$v['service_score'];
            $data['list'][$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $data['list'][$k]['score'] = ceil($score/2);
            $data['list'][$k]['endscore'] = 5-ceil($score/2);
            if(!empty($v['image'])){
                $data['list'][$k]['images'] = explode(',',$v['image']);
            }
            $names = $order_goods_db->where(array('order_id'=>$v['order_id']))->select();
            if(strlen($names[0]['dishes_name']) > 15){
                $names[0]['dishes_name'] = substr($names[0]['dishes_name'],0,15).'...';
            }
            if(strlen($names[1]['dishes_name']) > 15){
                $names[1]['dishes_name'] = substr($names[1]['dishes_name'],0,15).'...';
            }
            if(count($names) > 2){
                $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'].'...';
            }else{
                if(empty($names[1]['dishes_name'])){
                    $names = $names[0]['dishes_name'];
                }else{
                    $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'];
                }
            }
            $data['list'][$k]['names'] = $names;
        }
        // dump($data);
        $this->make_json_result(1,$data);
    }

    // 评价加载更多
    public function eitemList(){
        $p = I('k',0,'intval');
        $num = 5;//每页记录数
        $limitpage = ($p)*$num;//每次查
        $stall_id = I('stall_id');
        $wherea['a.is_del']  = 2;
        $wherea['a.member_list_id']  = session('member_list_id');
        $wherea['_logic'] = 'or';
        $where['_complex'] = $wherea;
        $where['a.stall_id'] = $stall_id;
        $order_goods_db = M('order_goods');
        $data['list']  = M('comment as a')
            ->join("LEFT JOIN sm_member_list as c on a.member_list_id = c.member_list_id")
            ->where($where)
            ->limit($limitpage,$num)
            ->order('addtime DESC')
            ->field('c.member_list_headpic,c.member_list_nickname,a.dish_score,a.service_score,a.addtime,a.image,a.content,a.order_id')
            ->select();
        foreach ($data['list'] as $k => $v){
            $score = $v['dish_score']+$v['service_score'];
            $data['list'][$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $data['list'][$k]['score'] = ceil($score/2);
            $data['list'][$k]['endscore'] = 5-ceil($score/2);
            if(!empty($v['image'])){
                $data['list'][$k]['images'] = explode(',',$v['image']);
            }
            $names = $order_goods_db->where(array('order_id'=>$v['order_id']))->select();
            if(strlen($names[0]['dishes_name']) > 15){
                $names[0]['dishes_name'] = substr($names[0]['dishes_name'],0,15).'...';
            }
            if(strlen($names[1]['dishes_name']) > 15){
                $names[1]['dishes_name'] = substr($names[1]['dishes_name'],0,15).'...';
            }
            if(count($names) > 2){
                $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'].'...';
            }else{
                if(empty($names[1]['dishes_name'])){
                    $names = $names[0]['dishes_name'];
                }else{
                    $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'];
                }
            }
            $data['list'][$k]['names'] = $names;
        }
        $this->make_json_result(1,$data);
    }

    // 餐品详情
    public function dishesDetail(){
        $member_list_id = session('member_list_id');
        $dishes_id = I('dishes_id');
        $ma_id = session('ma_id');
        $discount_db = M('discount');
        $cart_db = M('cart');
        $infos = M('dishes')->where(array('dishes_id'=>$dishes_id))->find();
        $infos['score'] = sprintf("%.1f",$infos['score']);
        if($infos['statue'] == 3){
            $count = $discount_db->where(array('ma_id'=>$ma_id,'start_time'=>array('elt',date("Hi")),'end_time'=>array('egt',date("Hi"))))->field('discount,start_time,end_time')->find();
            if(!empty($count)){
                $infos['discount'] = $count['discount'];
                $infos['start_time'] = substr_replace($count['start_time'], ':', -2, 0);
                $infos['end_time'] = substr_replace($count['end_time'], ':', -2, 0);
                $infos['real'] = sprintf("%.2f",$infos['price']*$count['discount']/10);
            }
        }elseif($infos['statue'] == 2 && $infos['discount']!=10){
            $infos['discount'] = $infos['discount'];
            $infos['real'] = sprintf("%.2f",$infos['price']*$infos['discount']/10);
        }
        $num = $cart_db->where(array('stall_id'=>$infos['stall'],'dishes_id'=>$infos['dishes_id'],'member_list_id'=>$member_list_id))->getField('number');
        if(empty($num)){
            $infos['number'] = 0;
        }else{
            $infos['number'] = $num;
        }
        $order_goods_db = M('order_goods');
        $wherea['a.is_del']  = 2;
        $wherea['a.member_list_id']  = session('member_list_id');
        $wherea['_logic'] = 'or';
        $where['_complex'] = $wherea;
        $where['b.dishes_id'] = $dishes_id;
        $where['d.order_type'] = 1;
        $evaluation = M('comment as a')
            ->join("LEFT JOIN sm_order_goods as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_orders as d on a.order_id = d.order_id")
            ->join("LEFT JOIN sm_member_list as c on d.member_list_id = c.member_list_id")
            ->where($where)
            ->limit(3)
            ->order('a.addtime DESC')
            ->field('c.member_list_headpic,c.member_list_nickname,a.dish_score,a.service_score,a.addtime,a.image,a.content,a.order_id')
            ->select();
        foreach ($evaluation as $k => $v){
            //$score = $v['dish_score']+$v['service_score'];
            $score = $v['dish_score'];
            $evaluation[$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $evaluation[$k]['score'] = ceil($score);
            $evaluation[$k]['endscore'] = 5-ceil($score);
            if(!empty($v['image'])){
                $evaluation[$k]['images'] = explode(',',$v['image']);
            }
            $names = $order_goods_db->where(array('order_id'=>$v['order_id']))->select();
            if(count($names) > 2){
                $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'].'...';
            }else{
                if(empty($names[1]['dishes_name'])){
                    $names = $names[0]['dishes_name'];
                }else{
                    $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'];
                }
            }
            $evaluation[$k]['names'] = $names;
        }
//        dump($evaluation);die;
        $is_p = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->getField('is_perfect');
        $maInfo = M('merchant')->where(array('ma_id'=>$ma_id))->find();
        if(time() >= strtotime(date("Y-m-d").date("H:i:s",$maInfo['start_time'])) && time() <= strtotime(date("Y-m-d").date("H:i:s",$maInfo['end_time']))){
            $maInfo['is_yy'] = 1;
        }else{
            $maInfo['is_yy'] = 2;
        }
        $this->assign('maInfo',$maInfo);
        $this->assign('is_p',$is_p);
        $this->assign('evaluation',$evaluation);
        $this->assign('infos',$infos);
        $this->display();
    }

    // 餐品评价页面
    public function evaluationG(){
        $dishes_id = I('dishes_id');
        $this->assign('dishes_id',$dishes_id);
        $this->display();
    }

    // 餐品评价加载
    public function evaluationD(){
        $dishes_id = I('dishes_id');
        $order_goods_db = M('order_goods');
        //总条数
        $num = 10;
        $wherea['a.is_del']  = 2;
        $wherea['a.member_list_id']  = session('member_list_id');
        $wherea['_logic'] = 'or';
        $where['_complex'] = $wherea;
        $where['b.dishes_id'] = $dishes_id;
        $where['d.order_type'] = 1;
        $count = M('comment as a')
            ->join("LEFT JOIN sm_order_goods as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_orders as d on a.order_id = d.order_id")
            ->join("LEFT JOIN sm_member_list as c on d.member_list_id = c.member_list_id")
            ->where($where)
            ->count();
        $totalPage = ceil($count/$num);//总计页数
        $data['totalPage'] = $totalPage;
        $evaluation = M('comment as a')
            ->join("LEFT JOIN sm_order_goods as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_orders as d on a.order_id = d.order_id")
            ->join("LEFT JOIN sm_member_list as c on d.member_list_id = c.member_list_id")
            ->where($where)
            ->limit($num)
            ->order('a.addtime DESC')
            ->field('c.member_list_headpic,c.member_list_nickname,a.dish_score,a.service_score,a.addtime,a.image,a.content,a.order_id')
            ->select();
        foreach ($evaluation as $k => $v){
            //$score = $v['dish_score']+$v['service_score'];
            $score = $v['dish_score'];
            $evaluation[$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $evaluation[$k]['score'] = ceil($score);
            $evaluation[$k]['endscore'] = 5-ceil($score);
            if(!empty($v['image'])){
                $evaluation[$k]['images'] = explode(',',$v['image']);
            }
            $names = $order_goods_db->where(array('order_id'=>$v['order_id']))->select();
            if(count($names) > 2){
                $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'].'...';
            }else{
                if(empty($names[1]['dishes_name'])){
                    $names = $names[0]['dishes_name'];
                }else{
                    $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'];
                }
            }
            $evaluation[$k]['names'] = $names;
        }
        $data['list'] = $evaluation;
        $this->make_json_result(1,$data);
    }

    // 餐品评价加载更多
    public function eitemListG(){
        $p = I('k',0,'intval');
        $dishes_id = I('dishes_id');
        $num = 10;//每页记录数
        $limitpage = ($p)*$num;//每次查
        $order_goods_db = M('order_goods');
        $wherea['a.is_del']  = 2;
        $wherea['a.member_list_id']  = session('member_list_id');
        $wherea['_logic'] = 'or';
        $where['_complex'] = $wherea;
        $where['b.dishes_id'] = $dishes_id;
        $where['d.order_type'] = 1;
        $evaluation = M('comment as a')
            ->join("LEFT JOIN sm_order_goods as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_member_list as c on a.member_list_id = c.member_list_id")
            ->where($where)
            ->limit($limitpage,$num)
            ->order('a.addtime DESC')
            ->field('c.member_list_headpic,c.member_list_nickname,a.dish_score,a.service_score,a.addtime,a.image,a.content,a.order_id')
            ->select();
        foreach ($evaluation as $k => $v){
            //$score = $v['dish_score']+$v['service_score'];
            $score = $v['dish_score'];
            $evaluation[$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $evaluation[$k]['score'] = ceil($score);
            $evaluation[$k]['endscore'] = 5-ceil($score);
            if(!empty($v['image'])){
                $evaluation[$k]['images'] = explode(',',$v['image']);
            }
            $names = $order_goods_db->where(array('order_id'=>$v['order_id']))->select();
            if(count($names) > 2){
                $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'].'...';
            }else{
                if(empty($names[1]['dishes_name'])){
                    $names = $names[0]['dishes_name'];
                }else{
                    $names = $names[0]['dishes_name'].'、'.$names[1]['dishes_name'];
                }
            }
            $evaluation[$k]['names'] = $names;
        }
        $data['list'] = $evaluation;
        $this->make_json_result(1,$data);
    }

    //取餐码
    public function getMakeCode($ma_id)
    {
        $res = rand(10000,99999);
        $where['a.meal_code'] = $res;
        $where['b.ma_id'] = $ma_id;
        $where['b.order_status'] = array('lt',5);
        $where['b.order_type'] = 1;
        $pass = M('order_goods')->join("AS a left join __ORDERS__ as b on a.order_id = b.order_id")->where($where)->find();
        if($pass){
            $res = $this->getMakeCode($ma_id);
        }
        return $res;
    }

    // 确认订单
    public function confirmOrder(){
        $member_list_id = session('member_list_id');
        $stall_id = I('stall_id');
        if(IS_POST){
            if(empty($stall_id)){
                $this->error('请选择菜品',0);die;
            }
            $cart_db = M('cart');
            $dishes_db = M('dishes');
            $order_goods_db = M('order_goods');
            // 检查库存
            $list = $cart_db->where(array('stall_id'=>$stall_id,'member_list_id'=>$member_list_id))->select();
            if(empty($list)){
                $this->error('请重新添加菜品',0);die;
            }
            foreach ($list as $k => $v){
                $kucun = $dishes_db->where(array('dishes_id'=>$v['dishes_id']))->find();
                if($v['number'] > $kucun['num']){
                    $this->error($kucun['dishes_name'].'库存不足！',0);die;
                }
            }
            // 下单
            $data['order_no'] = create_pay_no();
            $data['member_list_id'] = $member_list_id;
            $data['ma_id'] = session('ma_id');
            $data['stall_id'] = $stall_id;
            $data['deliver_type'] = I('deliver_type');

            //判断配送方式是否存在这个方式
            $status = M('merchant')->where(array('ma_id'=>session('ma_id')))->getField('status');
            if(strstr($status, I('deliver_type')) == false){
                $this->error('配送方式已改变，请刷新页面',0);die;
            }
            if(I('deliver_type') == 1){
                $data['express_money'] = I('express_money');
                $data['username'] = I('username');
                $data['phone'] = I('phone');
                $data['address'] = I('address');
                $ress = M('user_address_list')->where(array('address_id'=>I('address_id')))->find();
                $data['longitude'] = $ress['longitude'];
                $data['latitude'] = $ress['latitude'];

            }else{
                $data['stall_address'] = I('stall_address');
                $data['stall_tel'] = I('stall_tel');
                $data['telphone'] = M('member_list')->where(array('member_list_id'=>$member_list_id))->getField('telphone');
            }
            $data['total_money'] = I('total_money');
            $express_time = I('express_time');
            if(empty($express_time)){
                $express_time = time()+900;
            }else{
                $str = date("Y-m-d ").$express_time;
                $express_time = strtotime($str);
            }
            $data['express_time'] = $express_time;
            $data['discount'] = I('discount');
            $data['real_money'] = I('real_money');
            $data['integral'] = I('integral');
            $data['order_note'] = I('order_note');
            $data['create_time'] = time();
            $order_id = M('orders')->add($data);
            // file_put_contents('999.log', var_export($order_id,TEUE).'|'.  PHP_EOL,FILE_APPEND);
            if(!empty($order_id)){
                //$meal_code = rand(1000,9999);
                $meal_code = $this->getMakeCode(session('ma_id'));
                // file_put_contents('999.log', var_export($list,TEUE).'|'.  PHP_EOL,FILE_APPEND);
                foreach ($list as $k => $v){
                    $dishes_db->where(array('dishes_id'=>$v['dishes_id']))->setDec('num',$v['number']);
                    $dish_info = $dishes_db->where(array('dishes_id'=>$v['dishes_id']))->find();
                    // file_put_contents('999.log', var_export($dish_info,TEUE).'|'.  PHP_EOL,FILE_APPEND);
                    $dataG['order_id'] = $order_id;
                    $dataG['dishes_id'] = $dish_info['dishes_id'];
                    $dataG['dishes_nums'] = $v['number'];
                    $dataG['dishes_price'] = $dish_info['price'];
                    if($dish_info['statue'] == 3 && !empty($data['discount'])){
                        $dataG['discount_price'] = $dish_info['price']*$data['discount']/10;
                    }elseif($dish_info['statue'] == 2 && $dish_info['discount']!=10){
                        $dataG['discount_price'] = $dish_info['price']*$dish_info['discount']/10;
                    }else{
                        $dataG['discount_price'] = $dish_info['price'];
                    }
                    $dataG['meal_code'] = $meal_code;
                    $dataG['addtime'] = time();
                    $dataG['dishes_name'] = $dish_info['dishes_name'];
                    $dataG['pic_url'] = $dish_info['pic_url'];
                    $order_goods_db->add($dataG);
                }
                // 清空购物车
                $cart_db->where(array('stall_id'=>$stall_id,'member_list_id'=>$member_list_id))->delete();
                $this->success('下单成功！',$order_id,1);
            }else{
                $this->error('下单失败！',$order_id,0);
            }
        }
        $ma_id = session('ma_id');
        $merchant = M('merchant')->where(array('ma_id'=>$ma_id))->find();
        $stallInfo = M('stall as a')->join("LEFT JOIN sm_merchant as b on a.ma_id = b.ma_id")->where(array('stall_id'=>$stall_id))->find();
        $list = M('cart as a')
            ->join("LEFT JOIN sm_dishes as b on a.dishes_id = b.dishes_id")
            ->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))
            ->order('a.addtime DESC')
            ->select();
        $discount_db = M('discount');
        $count = $discount_db->where(array('ma_id'=>$ma_id,'start_time'=>array('elt',date("Hi")),'end_time'=>array('egt',date("Hi"))))->getField('discount');
        foreach ($list as $k => $v){
            if($v['statue'] == 3){
                if(!empty($count)){
                    $list[$k]['real'] = sprintf("%.2f",$v['price']*$count/10);
                    $list[$k]['allmoney'] = sprintf("%.2f",$list[$k]['real']*$v['number'],2);
                }else{
                    $list[$k]['real'] = $v['price'];
                    $list[$k]['allmoney'] = sprintf("%.2f",$v['price']*$v['number'],2);
                }
            }elseif($v['statue'] == 2 && $v['discount'] != 10){
                $list[$k]['real'] = sprintf("%.2f",$v['price']*$v['discount']/10);
                $list[$k]['allmoney'] = sprintf("%.2f",$list[$k]['real']*$v['number'],2);
            }else{
                $list[$k]['real'] = $v['price'];
                $list[$k]['allmoney'] = sprintf("%.2f",$v['price']*$v['number'],2);
            }
            // 原价
            $stallInfo['allmoney'] += sprintf("%.2f",$v['price']*$v['number'],2);
            // 折扣
            $stallInfo['allmoneys'] += $list[$k]['allmoney'];
        }
        $value = M('sysconfig')->where(array('varname'=>'cfg_integral'))->getField('value');
        $stallInfo['integral'] = floor($stallInfo['allmoneys']*$value);
        $stallInfo['allmoney'] = sprintf("%.2f",$stallInfo['allmoneys'],2);
        $stallInfo['allmoneys'] = sprintf("%.2f",$stallInfo['allmoneys'],2);

        
//        dump($stallInfo);die;
        $this->assign('merchant',$merchant);
        
        $this->assign('list',$list);
        
        $this->assign('count',$count);
        $res = $this->getHot();
        $this->assign('res',$res);
        $this->assign('all',I('all'));
        //判断字符串是否有配送 1配送 2 自提 1,2   1  2
        //
        $shopInfo = M('merchant')->where(array('ma_id'=>session('ma_id')))->field('status')->find();

         if($shopInfo['status'] =='1,2') {
            $a=1;
            $b=1;
            $address_id = I('address_id');
            if($address_id){
                if($stallInfo['allmoneys'] >= 10){
                    $stallInfo['p_money'] = $stallInfo['allmoneys']*0.1;
                }
                $stallInfo['allmoneys'] = sprintf("%.2f",$stallInfo['allmoneys']+$stallInfo['p_money'],2);
            }

         }elseif ($shopInfo['status'] =='1') {
            $a =1;
            $b = 2;
            $stallInfo['allmoneys'] = sprintf("%.2f",$stallInfo['allmoneys']+$stallInfo['p_money'],2);
         }else{
            $a =2;
            $b = 1;
         }

         $this->assign('stallInfo',$stallInfo);
        if($a != 2){ 
            $address_id = I('address_id');
            if(empty($address_id)){
                if(session('longitude') && session('latitude')){
                    $longitude = session('longitude');
                    $latitude = session('latitude');
                    $where['a.member_list_id'] = $member_list_id;
                    $where['a.is_del'] = 2;
                    $address = M('user_address_list as a')->field("a.*,ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$latitude."*PI()/180-a.latitude*PI()/180)/2),2)+COS(27.237843*PI()/180)*COS(a.latitude*PI()/180)*POW(SIN((".$longitude."*PI()/180-a.longitude*PI()/180)/2),2)))*1000) AS juli")->where($where)->order("juli asc")->find();
                }else{
                    $address= M('user_address_list')->where(array('member_list_id'=>session('member_list_id'),'is_default'=>1))->find();
                }
            }else{
                $this->assign('address_id',$address_id);
                $address= M('user_address_list')->where(array('address_id'=>$address_id))->find();
            }

            $address['proviceid'] = M('user_address')->where(array('id'=>$address['proviceid']))->getField('title');
            $address['cityid'] = M('user_address')->where(array('id'=>$address['cityid']))->getField('title');
            $address['countyid'] = M('user_address')->where(array('id'=>$address['countyid']))->getField('title');

        }
        // dump($a);
        // dump($b);
        $memInfo = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->field('stature,weight')->find();
        $this->assign('memInfo',$memInfo);
        $this->assign('shopInfo',$shopInfo);
        
        $this->assign('address',$address);
        $this->assign('a',$a);
        $this->assign('b',$b);
        $this->display();
    }

    // 去支付
    public function payNow(){
        $ma_id = session('ma_id');
        $order_id = I('order_id');
        $infos = M('orders')->where(array('order_id'=>$order_id))->find();
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
        $openid = M('member_list')->where(array('member_list_id'=>session('member_list_id')))->getField('openid');
        // var_dump(session('member_list_id'));
        if($openid)
        {
            $notify_url = 'http://'.$_SERVER['HTTP_HOST'].'/ordera.php';
            //=========步骤2：使用统一支付接口，获取prepay_id============
            $unifiedOrder = new \UnifiedOrder_pub();
            $unifiedOrder->setParameter("openid",$openid);//微信用户
            $unifiedOrder->setParameter("body",'微信订单支付');//商品描述
            $unifiedOrder->setParameter("out_trade_no",$infos['order_no']);//商户订单号
            $money = $infos['real_money'];
            // $money = 0.01;
            $unifiedOrder->setParameter("total_fee",intval($money*100));//总金额
            $unifiedOrder->setParameter("notify_url",$notify_url);//通知地址
            $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
            $prepay_id = $unifiedOrder->getPrepayId();
            // var_dump($prepay_id);
//            file_put_contents('123.log',$prepay_id);
            $JsApi_pub->setPrepayId($prepay_id['prepay_id']);

            $jsApiParameters = $JsApi_pub->getParameters();
            // var_dump($jsApiParameters);die;
//            file_put_contents('1234.log',json_encode($jsApiParameters));
            $this->assign('jsApiParameters',$jsApiParameters);
            //支付成功跳转页面
            $success_returnUrl='http://'.$_SERVER['HTTP_HOST'].'/index.php?m=Home&c=Myorder&a=index';
            //支付失败跳转页面
            $error_returnUrl='http://'.$_SERVER['HTTP_HOST'].'/index.php?m=Home&c=Myorder&a=index';
            $this->assign('success_returnUrl',$success_returnUrl);
            $this->assign('error_returnUrl',$error_returnUrl);
        }
        $this->assign('infos',$infos);
        $this->display();
    }

    // 获取热量
    public function getHot(){
        $member_list_id = session('member_list_id');
        $infos = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_del'=>2))->find();
        $hour = date("H");
        if($infos['member_list_sex'] == 1){
            $w = $infos['stature']-105;
            if($hour < 10){
                if($infos['weight'] < $w*0.95){
                    $data['start'] = 725;
                    $data['end'] = 1305;
                    
                }else if($infos['weight'] > $w*1.05){
                    $data['start'] = 145;
                    $data['end'] = 725;
                    
                }else{
                    $data['start'] = 435;
                    $data['end'] = 1015;
                    
                }
            }else if(10 < $hour && $hour < 16){
                if($infos['weight'] < $w*0.95){
                    $data['start'] = 1160;
                    $data['end'] = 1740;
                }else if($infos['weight'] > $w*1.05){
                    $data['start'] = 580;
                    $data['end'] = 1160;
                   
                }else{
                    $data['start'] = 870;
                    $data['end'] = 1450;
                    
                }
            }else{
                if($infos['weight'] < $w*0.95){
                    $data['start'] = 1015;
                    $data['end'] = 1595;
                    
                }else if($infos['weight'] > $w*1.05){
                    $data['start'] = 435;
                    $data['end'] = 1015;
                }else{
                    $data['start'] = 725;
                    $data['end'] = 1305;
                    
                }
            }
        }else{
            $w = $infos['stature']-107.5;
            if($hour < 10){
                if($infos['weight'] < $w*0.95){
                    $data['start'] = 650;
                    $data['end'] = 1170;
                }else if($infos['weight'] > $w*1.05){
                    $data['start'] = 130;
                    $data['end'] = 650;
                }else{
                    $data['start'] = 390;
                    $data['end'] = 910;
                    
                }
            }else if(10 < $hour && $hour < 16){
                if($infos['weight'] < $w*0.95){
                    $data['start'] = 1040;
                    $data['end'] = 1560;
                    
                }else if($infos['weight'] > $w*1.05){
                    $data['start'] = 520;
                    $data['end'] = 1040;
                    
                }else{
                    $data['start'] = 780;
                    $data['end'] = 1330;
                    
                }
            }else{
                if($infos['weight'] < $w*0.95){
                    $data['start'] = 910;
                    $data['end'] = 1430;
                    
                }else if($infos['weight'] > $w*1.05){
                    $data['start'] = 390;
                    $data['end'] = 910;
                    
                }else{
                    $data['start'] = 650;
                    $data['end'] = 1170;
                    
                }
            }
        }
        return $data;
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
            ->order('address_id desc')
            ->select();
        $stall_id = I('stall_id');
        $this->assign('stall_id',$stall_id);
        $goods_id = I('goods_id');
        $this->assign('goods_id',$goods_id);
        $nums = I('nums');
        $this->assign('nums',$nums);
        foreach ($list as $key=>$val){
            $list[$key]['proviceid'] = M('user_address')->where(array('id'=>$val['proviceid']))->getField('title');
            $list[$key]['cityid'] = M('user_address')->where(array('id'=>$val['cityid']))->getField('title');
            $list[$key]['countyid'] = M('user_address')->where(array('id'=>$val['countyid']))->getField('title');
        }
        $this->assign('list',$list);
        $this->display();
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

    //添加地址
    public function address_add()
    {
        $stall_id = I('stall_id');
        $goods_id = I('goods_id');
        $nums = I('nums');
        if(IS_AJAX){
            $member_list_id = $_SESSION['member_list_id'];
            $name = I('name');
            $phone = I('phone');
            $proviceid = I('proviceid');
            $cityid = I('cityid');
            $countyid = I('countyid');
            $address = I('address');
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
            if($res !== false){
                if($stall_id){
                    $this->success('添加成功！',U('address_list',array('stall_id'=>$stall_id)));
                }else{
                    $this->success('添加成功！',U('address_list',array('goods_id'=>$goods_id,'nums'=>$nums)));
                }
                
            }else{
                if($stall_id){
                    $this->error('添加失败！',U('address_list',array('stall_id'=>$stall_id)));
                }else{
                    $this->error('添加失败！',U('address_list',array('goods_id'=>$goods_id,'nums'=>$nums)));
                }
                //$this->error('添加失败！',U('address_add',array('stall_id'=>$stall_id)),0);
            }
        }else{
            $this->assign('stall_id',$stall_id);
            $this->assign('goods_id',$goods_id);
            $this->assign('nums',$nums);
            $infos = M('merchant')->where(array('ma_id'=>session('ma_id')))->field('longitude,latitude')->find();
            $this->assign('infos',$infos);
            $this->display();
        }
    }

    //编辑地址
    public function address_edit()
    {
        $stall_id = I('stall_id');
        $goods_id = I('goods_id');
        $nums = I('nums');
        if(IS_AJAX){
            $address_id = I('address_id');
            $name = I('name');
            $phone = I('phone');
            $proviceid = I('proviceid');
            $cityid = I('cityid');
            $countyid = I('countyid');
            $address = I('address');
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
                if($stall_id){
                    $this->success('编辑成功！',U('address_list',array('stall_id'=>$stall_id)));
                }else{
                    $this->success('编辑成功！',U('address_list',array('goods_id'=>$goods_id,'nums'=>$nums)));
                }
                //$this->success('编辑成功！',U('address_list',array('stall_id'=>$stall_id)));
            }else{
                if($stall_id){
                    $this->error('编辑失败！',U('address_list',array('stall_id'=>$stall_id)));
                }else{
                    $this->error('编辑失败！',U('address_list',array('goods_id'=>$goods_id,'nums'=>$nums)));
                }
                //$this->error('编辑失败！',U('address_list',array('stall_id'=>$stall_id)));
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
            $this->assign('stall_id',$stall_id);
            $this->assign('infos',$infos);
            $this->assign('goods_id',$goods_id);
            $this->assign('nums',$nums);
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
}
