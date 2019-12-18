<?php
namespace Small\Controller;
use Think\Controller;
use Small\Model\qiyeModel;

class StallController extends BaseController
{

    // 档口详情
    public function stallDetail(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        // 档口信息
        $stallInfo = M('stall')->where(array('stall_id'=>$stall_id))->find();
        $stallInfo['score'] = sprintf("%.1f",$stallInfo['score']);
        // 是否收藏
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
       
        $stallInfo['image'] = 'http://'.$_SERVER['SERVER_NAME'].$stallInfo['image'];
        $stallInfo['tone_music'] = 'http://'.$_SERVER['SERVER_NAME'].$stallInfo['tone_music'];

        $data['code']  = 200;
        $data['msg'] = '获取成功';
        $data['maInfo'] = $maInfo;
        $data['is_p'] = $is_p;
        $data['end'] = $end;
        $data['tuijian'] = $tuijian;
        $data['type'] = $type;
        $data['stallInfo'] = $stallInfo;
        $this->ajaxReturn($data);

    }

    // 收藏
    public function collection(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        $member_collection_db = M('member_collection');
        $res = $member_collection_db->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))->getField('collection_id');
        if(empty($res)){
            $data['member_list_id'] = $member_list_id;
            $data['stall_id'] = $stall_id;
            $data['addtime'] = time();
            $member_collection_db->add($data);

            $data['code'] = 200;
            $data['is_sc'] = 1;
            $data['msg'] = '收藏成功';
            $this->ajaxReturn($data);
        }else{
            $member_collection_db->where(array('collection_id'=>$res))->delete();
            $data['code'] = 200;
            $data['is_sc'] = 2;
            $data['msg'] = '取消成功';
            $this->ajaxReturn($data);
        }
    }

    // 购物车
    public function cart(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

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
        $data['code'] = 200;
        $data['msg'] = '加载成功';
        $data['data'] = $list;
        $this->ajaxReturn($data);
    }

    // 删除购物车
    public function delCar(){
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');
        $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->delete();
        $count = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))->count();
//        dump(M('cart')->getLastSql());die;
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '操作成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '操作失败，请稍后重试！';
        }
        $this->ajaxReturn($data);
    }

    // 添加购物车
    public function addCar(){
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');
        $data['member_list_id'] = $member_list_id;
        $data['stall_id'] = $stall_id;
        $data['dishes_id'] = $dishes_id;
        $data['number'] = 1;
        $data['addtime'] = time();
        $res = M('cart')->add($data);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '添加成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '添加失败，请稍后重试！';
        }
        $this->ajaxReturn($data);
    }


    // 清空购物车
    public function clearCar(){
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))->delete();
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '操作成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '操作失败，请稍后重试！';
        }
        $this->ajaxReturn($data);

    }

    // 数量加
    public function incCar(){
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');

        $row = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->field('number')->find();
        // echo '<pre>';
        // print_r($row);
        // exit;

        $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->setInc('number',1);
        if($res !== false){
            $data['code'] = 200;
            $data['msg'] = '操作成功';
        }else{
            $data['code'] = 100;
            $data['msg'] = '操作失败，请稍后重试！';
        }
        $this->ajaxReturn($data);
    }

    // 数量减
    public function decCar(){
        $sessions = $this->Sessions();
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        $dishes_id = I('dishes_id');

        $row = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->field('number')->find();

        if($row['number'] >= 2){
            $res = M('cart')->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id,'dishes_id'=>$dishes_id))->setDec('number',1);
            if($res !== false){
                $data['code'] = 200;
                $data['msg'] = '操作成功';
            }else{
                $data['code'] = 100;
                $data['msg'] = '操作失败，请稍后重试！';
            }
        }
        $this->ajaxReturn($data);
    }

    // 档口评价
    public function evaluationList(){
        $stall_id = I('stall_id');
        $comment_db = M('comment');
        $order_goods_db = M('order_goods');
        //总条数
        $num = 10;
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
                $data['list'][$k]['image'] = explode(',',$v['image']);
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
        $data['list'] = $this->thumbUrl($data['list'],'member_list_headpic');
        
        if($data){
            $datas['code'] = 200;
            $datas['msg'] = '获取成功';
            $datas['data'] = $data;
        }else{
            $datas['code'] = 100;
            $datas['msg'] = '获取失败，暂无数据';
        }
        $this->ajaxReturn($datas);
    }

    // 评价加载更多
    public function eitemList(){
        $p = I('p',0,'intval');
        $num = 10;//每页记录数
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
        $data['list'] = $this->thumbUrl($data['list'],'member_list_headpic');
        
        if($data){
            $datas['code'] = 200;
            $datas['msg'] = '获取成功';
            $datas['data'] = $data;
        }else{
            $datas['code'] = 100;
            $datas['msg'] = '获取失败，暂无数据';
        }
        $this->ajaxReturn($datas);
    }


    // 餐品详情
    public function dishesDetail(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $dishes_id = I('dishes_id');
        $discount_db = M('discount');
        $cart_db = M('cart');
        $infos = M('dishes')->where(array('dishes_id'=>$dishes_id))->find();
        $infos['pic_url'] = 'http://'.$_SERVER['SERVER_NAME'].$infos['pic_url'];    //产品展示大图
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
            if (substr($v['member_list_headpic'], 0 , 5) != "https") {
                $evaluation[$k]['member_list_headpic'] = 'http://'.$_SERVER['SERVER_NAME'].$v['member_list_headpic'];
            }
            $score = $v['dish_score'];
            $evaluation[$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $evaluation[$k]['score'] = ceil($score);
            $evaluation[$k]['endscore'] = 5-ceil($score);
            if(!empty($v['image'])){
                $evaluation[$k]['image'] = explode(',',$v['image']);
                //图片路径处理
                foreach ($evaluation[$k]['image'] as $kk => &$vv) {
                    $vv = 'http://'.$_SERVER['SERVER_NAME'].$vv;
                }
                //图片路径处理
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

        $stall_status = M('stall')->where(array('stall_id'=>$infos['stall']))->getField('is_freeze');

        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['maInfo'] = $maInfo;
        $data['is_p'] = $is_p;
        $data['evaluation'] = $evaluation;
        $data['infos'] = $infos;
        $data['is_freeze'] = $stall_status;
        $this->ajaxReturn($data);
    }

    // 餐品评价页面
    public function evaluationG(){
        $dishes_id = I('dishes_id');
        $this->assign('dishes_id',$dishes_id);
        $this->display();
    }


    // 餐品评价加载
    public function evaluationD(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $dishes_id = I('dishes_id');
        $order_goods_db = M('order_goods');
        //总条数
        $num = 10;
        $wherea['a.is_del']  = 2;
        $wherea['a.member_list_id']  = $member_list_id;
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
            if (substr($v['member_list_headpic'], 0 , 5) != "https") {
                $evaluation[$k]['member_list_headpic'] = 'http://'.$_SERVER['SERVER_NAME'].$v['member_list_headpic'];
            }
            
            $score = $v['dish_score'];
            $evaluation[$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $evaluation[$k]['score'] = ceil($score);
            $evaluation[$k]['endscore'] = 5-ceil($score);
            if(!empty($v['image'])){
                $evaluation[$k]['image'] = explode(',',$v['image']);
                //图片路径处理
                foreach ($evaluation[$k]['image'] as $kk => &$vv) {
                    $vv = 'http://'.$_SERVER['SERVER_NAME'].$vv;
                }
                //图片路径处理
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
        // $this->make_json_result(1,$data);
        if($data){
            $datas['code'] = 200;
            $datas['msg'] = '获取成功';
            $datas['data'] = $data;
            $this->ajaxReturn($datas);
        }else{
            $datas['code'] = 100;
            $datas['msg'] = '获取失败，暂无数据';
            $this->ajaxReturn($datas);
        }
    }

    // 餐品评价加载更多
    public function eitemListG(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $p = I('p',0,'intval');
        $dishes_id = I('dishes_id');
        $num = 10;//每页记录数
        $limitpage = ($p)*$num;//每次查
        $order_goods_db = M('order_goods');
        $wherea['a.is_del']  = 2;
        $wherea['a.member_list_id']  = $member_list_id;
        $wherea['_logic'] = 'or';
        $where['_complex'] = $wherea;
        $where['b.dishes_id'] = $dishes_id;
        $where['d.order_type'] = 1;
        $evaluation = M('comment as a')
            ->join("LEFT JOIN sm_order_goods as b on a.order_id = b.order_id")
            ->join("LEFT JOIN sm_orders as d on a.order_id = d.order_id")
            ->join("LEFT JOIN sm_member_list as c on a.member_list_id = c.member_list_id")
            ->where($where)
            ->limit($limitpage,$num)
            ->order('a.addtime DESC')
            ->field('c.member_list_headpic,c.member_list_nickname,a.dish_score,a.service_score,a.addtime,a.image,a.content,a.order_id')
            ->select();
        foreach ($evaluation as $k => $v){
            //$score = $v['dish_score']+$v['service_score'];
            $evaluation[$k]['member_list_headpic'] = 'http://'.$_SERVER['SERVER_NAME'].$evaluation[$k]['member_list_headpic'];
            $score = $v['dish_score'];
            $evaluation[$k]['addtime'] = date("Y-m-d H:i",$v['addtime']);
            $evaluation[$k]['score'] = ceil($score);
            $evaluation[$k]['endscore'] = 5-ceil($score);
            if(!empty($v['image'])){
                $evaluation[$k]['image'] = explode(',',$v['image']);
                //图片路径处理
                foreach ($evaluation[$k]['image'] as $kk => &$vv) {
                    $vv = 'http://'.$_SERVER['SERVER_NAME'].$vv;
                }
                //图片路径处理
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
        // $this->make_json_result(1,$data);

        if($data){
            $datas['code'] = 200;
            $datas['msg'] = '获取成功';
            $datas['data'] = $data;
            $this->ajaxReturn($datas);
        }else{
            $datas['code'] = 100;
            $datas['msg'] = '获取失败，暂无数据';
            $this->ajaxReturn($datas);
        }
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

    //确认订单
    public function payorder_info(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $arr = json_decode(file_get_contents("php://input") , true);

        // print_r($arr);
        // exit;
        $stall_id = $arr['stall_id'];
        // 提交生成订单
        if(empty($stall_id)){
            $data['code'] = 100;
            $data['msg'] =  '请选择菜品';
            $this->ajaxReturn($data);
            die;
        }
        $cart_db = M('cart');
        $dishes_db = M('dishes');
        $order_goods_db = M('order_goods');
        // 检查库存
        $list = $cart_db->where(array('stall_id'=>$stall_id,'member_list_id'=>$member_list_id))->select();
        if(empty($list)){
            $data['code'] = 100;
            $data['msg'] =  '请重新添加菜品';
            $this->ajaxReturn($data);
            die;
        }
        foreach ($list as $k => $v){
            $kucun = $dishes_db->where(array('dishes_id'=>$v['dishes_id']))->find();
            if($v['number'] > $kucun['num']){
                $data['code'] = 100;
                $data['msg'] =  $kucun['dishes_name'].'库存不足！';
                $this->ajaxReturn($data);
                die;
            }
        }
        // 下单
        $data['order_no'] = create_pay_no();
        $data['member_list_id'] = $member_list_id;
        $data['ma_id'] = $ma_id;
        $data['stall_id'] = $stall_id;
        $deliver_type = $arr['deliver_type'];
        $data['deliver_type'] = $deliver_type;

        //判断配送方式是否存在这个方式
        $status = M('merchant')->where(array('ma_id'=>$ma_id))->getField('status');

        $status_arr = explode(',', $status);
        if(in_array( $deliver_type , $status_arr) == false){
            $data['code'] = 100;
            $data['msg'] = '配送方式已改变，请刷新页面';
            $this->ajaxReturn($data);
        }
        if($deliver_type == 1){
            $express_money = $arr['express_money'];
            $username = $arr['username'];
            $phone = $arr['phone'];
            $address = $arr['address'];
            $address_id = $arr['address_id'];

            $data['express_money'] = $express_money;
            $data['username'] = $username;
            $data['phone'] = $phone;
            $data['address'] = $address;
            $ress = M('user_address_list')->where(array('address_id'=>$address_id))->find();
            $data['longitude'] = $ress['longitude'];
            $data['latitude'] = $ress['latitude'];

        }else{
            $stall_address = $arr['stall_address'];
            $stall_tel = $arr['stall_tel'];

            $data['stall_address'] = $stall_address;
            $data['stall_tel'] = $stall_tel;
            $data['telphone'] = M('member_list')->where(array('member_list_id'=>$member_list_id))->getField('telphone');
        }
        $total_money = $arr['total_money'];

        $data['total_money'] = $total_money;
        $express_time = $arr['express_time'];
        if(empty($express_time)){
            $express_time = time()+900;
        }else{
            $str = date("Y-m-d ").$express_time;
            $express_time = strtotime($str);
        }
        $data['express_time'] = $express_time;

        $discount = $arr['discount'];
        $real_money = $arr['real_money'];
        $integral = $arr['integral'];
        $order_note = $arr['order_note'];

        $data['discount'] = $discount;
        $data['real_money'] = $real_money;
        $data['integral'] = $integral;
        $data['order_note'] = $order_note;
        $data['create_time'] = time();
        $order_id = M('orders')->add($data);
        // file_put_contents('999.log', var_export($order_id,TEUE).'|'.  PHP_EOL,FILE_APPEND);
        if(!empty($order_id)){
            //$meal_code = rand(1000,9999);
            $meal_code = $this->getMakeCode($ma_id);
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

                //判断档口是否为自动接单状态 
                // $automatic = M('stall')->where(array('stall_id'=>$stall_id))->getField("automatic");
                // if($automatic == 1){
                //     $dataG['state'] = 0;
                // }else{
                //     $dataG['state'] = 1;
                // }
                //判断档口是否为自动接单状态 

                $order_goods_db->add($dataG);
            }
            // 清空购物车
            $cart_db->where(array('stall_id'=>$stall_id,'member_list_id'=>$member_list_id))->delete();
            $data['code'] = 200;
            $data['msg'] = '下单成功！';
            $data['order_id'] = $order_id;
        }else{
            $data['code'] = 100;
            $data['msg'] = '下单失败！';
            $data['order_id'] = $order_id;
        }
        $this->ajaxReturn($data);
    }

    //结算
    public function settlement(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $stall_id = I('stall_id');
        //处理数据
        $merchant = M('merchant')->where(array('ma_id'=>$ma_id))->find();
        $stallInfo = M('stall as a')->join("LEFT JOIN sm_merchant as b on a.ma_id = b.ma_id")->where(array('stall_id'=>$stall_id))->find();
        $list = M('cart as a')
            ->join("LEFT JOIN sm_dishes as b on a.dishes_id = b.dishes_id")
            ->where(array('member_list_id'=>$member_list_id,'stall_id'=>$stall_id))
            ->order('a.addtime DESC')
            ->select();
        $discount_db = M('discount');
        $count = $discount_db->where(array('ma_id'=>$ma_id,'start_time'=>array('elt',date("Hi")),'end_time'=>array('egt',date("Hi"))))->getField('discount');
        if (empty($count)) {
            $count = 0;
        }
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
            $stallInfo['price'] += $v['price'];
        }

        // echo "<pre>";
        // print_r($list);
        // exit;
        $value = M('sysconfig')->where(array('varname'=>'cfg_integral'))->getField('value');
        $stallInfo['integral'] = floor($stallInfo['allmoneys']*$value);
        $stallInfo['allmoney'] = sprintf("%.2f",$stallInfo['allmoneys'],2);
        $stallInfo['allmoneys'] = sprintf("%.2f",$stallInfo['allmoneys'],2);
        $stallInfo['image'] = "http://mccygood.com".$stallInfo['image'];
        
        $data['code'] = 200;
        $data['msg'] = '获取成功';
        $data['merchant'] = $merchant;
        $data['list'] = $list;
        $data['count'] = $count;
        
        $res = $this->getHot();
        $data['res'] = $res;
        $data['all'] = I('all');

        //判断字符串是否有配送 1配送 2 自提 1,2   1  2
        //
        $shopInfo = M('merchant')->where(array('ma_id'=>$ma_id))->field('status')->find();

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

        $data['stallInfo'] = $stallInfo;
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
                $data['address_id'] = $address_id;
                $address= M('user_address_list')->where(array('address_id'=>$address_id))->find();
            }
        }

        $memInfo = M('member_list')->where(array('member_list_id'=>session('member_list_id'),'is_del'=>2))->field('stature,weight')->find();
        $data['memInfo'] = $memInfo;
        $data['shopInfo'] = $shopInfo;
        $data['address'] = $address;
        $data['a'] = $a;
        $data['b'] = $b;
        $this->ajaxReturn($data);
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

    //调取统一下单接口---小程序支付
    public function unpay(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];

        $arr_in = json_decode(file_get_contents("php://input") , true);
        $order = $arr_in['order_no'];
        $money = $arr_in['money'];
        $mch_id = 1523992141;
        $appid = 'wx6f20b935cb808182';
        $appkey = 'fe29cc0f340f8da7fe1d293e37eaec6e';

        $openid = M('member_list')->where(array('member_list_id'=>$member_list_id))->getField('openid');

        $unurl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $params = [
            'appid'             => $appid,
            'mch_id'            => $mch_id,
            'nonce_str'         => uniqid(),
            'body'              => '梦诚餐饮',
            'out_trade_no'      => $order,
            'total_fee'         => $money*100,
            'spbill_create_ip'  => $_SERVER['REMOTE_ADDR'],
            // 'notify_url'        => 'http://mccygood.com/index.php?m=Small&c=Stall&a=notify_url',//此处写你的回调地址
            'notify_url'        => 'http://'.$_SERVER['HTTP_HOST'].'/orderb.php',
            'trade_type'        => 'JSAPI',
            'openid'            => $openid,
        ];
        $params['sign'] = $this -> getSign($params , $appkey);

        $xml = $this -> ArrToXml($params);
        $res = $this -> curlRequest($unurl,$xml);
        $arr = $this -> XmlToArr($res);

        $params2 = [
            'appId'     => $appid,
            'timeStamp'     => strval(time()),   //建议把时间戳转变成字符串
            'nonceStr'     => uniqid(),
            'package'     => 'prepay_id='.$arr['prepay_id'],
            'signType'     => 'MD5'
        ];
        $params2['paySign'] = $this -> getSign($params2 , $appkey);
        $params2['prepay_id'] = $arr['prepay_id'];

        $data['code'] = 200;
        $data['msg'] = '调取统一下单接口成功';
        $data['data'] = $params2;

        $this->ajaxReturn($data);
    }

    //小程序支付回调地址
    public function notify_url(){
        $xmls = file_get_contents("php://input");

        $xmlstring = simplexml_load_string($xmls, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);

        $out_trade_no = $val["out_trade_no"];//订单号
        $total_fee = $val["total_fee"];
        //支付流水号
        $trade_no = $val['transaction_id'];
        $pay_no = $out_trade_no;
        $money = $total_fee / 100;
        if ($val["return_code"] == "SUCCESS")
        {
            //支付状态
            $infos = M('orders')->where(array('order_no'=>$out_trade_no))->find();
            //支付状态0未支付1已支付
            if ( $infos['order_status'] == 1)
            {
                //修改订单状态
                $order_data['trade_no'] = $trade_no;
                $order_data['payment_time'] = time();

                //判断是否为自动接单状态
                $automatic = M('stall')->where(array('stall_id'=>$infos['stall_id']))->getField("automatic");
                if($automatic == 2){
                    $order_data['order_status'] = 3;
                }else{
                    $order_data['order_status'] = 2;
                }
                //判断是否为自动接单状态

                M('orders')->where(array('order_no'=>$out_trade_no))->save($order_data);
                //增加积分记录
                /*M('member_list')->where(array('member_list_id'=>$infos['member_list_id']))->setInc('integral',$infos['integral']);
                $res['order_no'] = $infos['order_no'];
                $res['ma_id'] = $infos['ma_id'];
                $res['member_list_id'] = $infos['member_list_id'];
                $res['state'] = 1;
                $res['type'] = 1;
                $res['integral'] = $infos['integral'];
                $res['creattime'] = time();
                M('integral_statistics')->add($res);*/
                 //查找默认的铃声
                $tone_music = M('stall_music')->where(array('stall_id'=>$infos['stall_id'],'music_type'=>1))->getField('music');
                $arr = array('type'=>"http://".$_SERVER['SERVER_NAME'].$tone_music);
                $n_content = '档口有新订单';
                $this->jpush($infos['stall_id'],$n_content,$arr);
            }
            echo "SUCCESS";
        }
    }

    //获取全局token
    public function get_token($appid , $secret){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;

        //取缓存数据
        $data = S('name');
        $arr = json_decode($data , true);
        if (empty($arr)) {
            //缓存数据为空的情况下请求微信接口调取全局token
            $data = $this->curlRequest($url);
            //存入缓存时间为3600秒
            S('name',$data,3600);
            $arr = json_decode($data , true);
        }

        return $arr;
    }

    //发送模板消息
    public function send_tmp(){
        $sessions = $this->Sessions();
        $ma_id = $sessions['ma_id'];
        $member_list_id = $sessions['member_list_id'];
        
        $appid = "wx6f20b935cb808182";
        $secret = "8dc48e526ece28eb48db4aea5ecf2947";

        $prepay_id = I('prepay_id');
        $order_no = I('order_no');

        //订单信息
        $order_info = M('orders')->field('deliver_type , address , real_money , integral , order_id , stall_id , payment_time')->where(array('order_no'=>$order_no))->find();
        //配送地址
        $address_info = M('user_address_list')->where(array('address'=>$order_info['address']))->find();
        $proviceid = M('user_address')->where(array('id'=>$address_info['proviceid']))->getField('title');
        $cityid = M('user_address')->where(array('id'=>$address_info['cityid']))->getField('title');
        $countyid = M('user_address')->where(array('id'=>$address_info['countyid']))->getField('title');

        $member_address = $proviceid.$cityid.$countyid.$order_info['address'];
        //档口信息
        $stall_name = M('stall')->where(array('stall_id'=>$order_info['stall_id']))->getField('stall_name');
        $ma_info = M('merchant')->where(array('ma_id'=>$ma_id))->getField('address');
        //获取取餐码和餐品名称
        $meal_code = M('order_goods')->field('dishes_name , meal_code')->where(array('order_id'=>$order_info['order_id']))->limit(1)->find();

        $arr = $this->get_token($appid , $secret);
        $access_token = $arr['access_token'];
        $send_url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token;
        $openid = M('member_list')->where(array('member_list_id'=>$member_list_id))->getField('openid');
        // $openid = "o0WsF5uH4LoKpsjOC1wK4c5972Gk";
        $qu_temp_id = "YhdpHpd5iHyuoqndqTxvnW4ZhA1MHcq78q3Bze0ZsJg";
        $shou_temp_id = "YhdpHpd5iHyuoqndqTxvnUsYcXfNaGJkoxI4owl1dfM";

        $arr_data = [
            "touser"    =>  $openid,
            "template_id"   =>  $shou_temp_id,
            "form_id"   =>  $prepay_id,
            "data"  =>  [
                "keyword1"  =>  [
                    "value" =>  $meal_code['meal_code'],
                ],
                "keyword2"  =>  [
                    "value" =>  $stall_name,
                ],
                "keyword3"  =>  [
                    "value" =>  $meal_code['dishes_name'],
                ],
                "keyword4"  =>  [
                    "value" =>  "待配送",
                ],
                "keyword5"  =>  [
                    "value" =>  date("Y-m-d H:i:s",$order_info['payment_time']),
                ],
                "keyword6"  =>  [
                    "value" =>  $order_info['real_money'],
                ],
                "keyword7"  =>  [
                    "value" =>  $member_address,
                ],
            ],
        ];

        if ($order_info['deliver_type'] == 2) {
            $arr_data['template_id'] = $qu_temp_id;
            $arr_data['data']['keyword4']['value'] = "待取餐";
            $arr_data['data']['keyword7']['value'] = $ma_info.$stall_name;
        }

        $json_data = json_encode($arr_data);
        $send_data = $this->curlRequest($send_url , $json_data);
        echo $send_data;
    }
}
