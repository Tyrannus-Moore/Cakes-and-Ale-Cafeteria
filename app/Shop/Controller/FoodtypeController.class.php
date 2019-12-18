<?php
namespace Shop\Controller;
use Common\Controller\ShopAuthController;
use Think\Verify;
use Org\Util\Stringnew;
class FoodtypeController extends ShopAuthController
{
    // 分类列表
    public function typeList()
    {
        $where = array();
        $dishes_category_db = M('dishes_category');
        $ma_id = session('ma_id');
        $where['ma_id'] = $ma_id;
        $where['is_del'] = 2;
        $search = I('search');
        if($search){
            if($search['name']){
                $where['cat_name'] = array('like','%'.trim($search['name']).'%');
            }
        }
        //分页
        $count= $dishes_category_db->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $dishes_category_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order("sort ASC")->select();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    // 新增分类
    public function typeAdd(){
        $ma_id = session('ma_id');
        $dishes_category_db = M('dishes_category');
        if (IS_AJAX){
            $name = I('name');
            $sort = I('sort');
            $isset = $dishes_category_db->where(array('ma_id'=>$ma_id,'sort'=>$sort))->select();
            if(!empty($isset)){
                $this->error('该序号已存在！');
            }
            $data['ma_id'] = $ma_id;
            $data['cat_name'] = $name;
            $data['sort'] = $sort;
            $data['addtime'] = time();
            $res = $dishes_category_db->add($data);
            if($res){
                $this->success('新增成功',U('typeList'),1);
            }else{
                $this->error('新增失败',U('typeList'),0);
            }
        }else{
            $this->display();
        }
    }

    // 新增分类
    public function typeEdit(){
        $dishes_cate_id = I('dishes_cate_id');
        $ma_id = session('ma_id');
        $dishes_category_db = M('dishes_category');
        if (IS_AJAX){
            $name = I('name');
            $sort = I('sort');
            $isset = $dishes_category_db->where(array('ma_id'=>$ma_id,'sort'=>$sort,'dishes_cate_id'=>array('neq',$dishes_cate_id)))->select();
            if(!empty($isset)){
                $this->error('该序号已存在！');
            }
            $data['cat_name'] = $name;
            $data['sort'] = $sort;
            $res = $dishes_category_db->where(array('dishes_cate_id'=>$dishes_cate_id))->save($data);
            if($res !== false){
                $this->success('修改成功',U('typeList'),1);
            }else{
                $this->error('修改失败',U('typeList'),0);
            }
        }else{
            $infos = $dishes_category_db->where(array('dishes_cate_id'=>$dishes_cate_id))->find();
            $this->assign('infos',$infos);
            $this->display();
        }
    }

    // 删除分类
    public function typeDel()
    {
        $dishes_category_db = M('dishes_category');
        $dishes_cate_id = I('dishes_cate_id');
        $list = M('dishes')->where(array('dishes_cate_id'=>$dishes_cate_id,'is_del'=>2))->select();
        if(!empty($list)){
            $this->success('该分类下存在菜品，请修改菜品的分类或删除该分类下的菜品！',U("typeList"),0);
        }
        $res = $dishes_category_db->where(array('dishes_cate_id'=>$dishes_cate_id))->setField('is_del',1);
        if($res !== false){
            $this->success('删除成功！',U("typeList"),1);
        }else{
            $this->success('删除失败！',U("typeList"),0);
        }
    }

    // 菜品列表
    public function foodList()
    {
        $where = array();
        $dishes_category_db = M('dishes_category');
        $stall_db = M('stall');
        $ma_id = session('ma_id');
        $where['a.ma_id'] = $ma_id;
        $where['a.is_del'] = 2;
        $search = I('search');
        if($search){
            if($search['dishes_id']){
                $where['a.dishes_no'] = array('like','%'.trim($search['dishes_id']).'%');
            }
            if($search['dishes_name']){
                $where['a.dishes_name'] = array('like','%'.trim($search['dishes_name']).'%');
            }
            if($search['dishes_cate_id']){
                $where['b.dishes_cate_id'] = $search['dishes_cate_id'];
            }
            if($search['stall_id']){
                $where['c.stall_id'] = $search['stall_id'];
            }
            if($search['start_time']){
                $where['a.addtime'] = array('egt',strtotime($search['start_time']));
            }
            if($search['end_time']){
                $where['a.addtime'] = array('elt',strtotime($search['end_time']." 23:59:59"));
            }
            if($search['start_time'] && $search['end_time']){
                $where['a.addtime'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time']." 23:59:59")));
            }
        }
        //分页
        $count= M('dishes as a')
            ->join('LEFT JOIN sm_dishes_category as b on a.dishes_cate_id = b.dishes_cate_id')
            ->join('LEFT JOIN sm_stall as c on a.stall = c.stall_id')
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('dishes as a')
            ->join('LEFT JOIN sm_dishes_category as b on a.dishes_cate_id = b.dishes_cate_id')
            ->join('LEFT JOIN sm_stall as c on a.stall = c.stall_id')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order("a.sort ASC,a.addtime DESC")
            ->field('a.dishes_id,a.dishes_no,a.dishes_name,c.stall_name,b.cat_name,a.num,a.price,a.score,a.state,a.addtime,a.on_the_pin,a.sort')
            ->select();
        $order_goods_db = M('order_goods');
//        foreach ($list as $k => $v){
//            $num = $order_goods_db->where(array('dishes_id'=>$v['dishes_id'],"FROM_UNIXTIME(addtime,'%Y-%m')"=>date('Y-m',time())))->sum('dishes_nums');
//            if(empty($num)){
//                $num = 0;
//            }
//            $list[$k]['number'] = $num;
//        }
        $dishes_cate_list = $dishes_category_db->where(array('is_del'=>2,'ma_id'=>$ma_id))->select();
        $stall_list = $stall_db->where(array('delete'=>2,'is_freeze'=>2,'ma_id'=>$ma_id))->select();
        $this->assign('list',$list);
        $this->assign('dishes_cate_list',$dishes_cate_list);
        $this->assign('stall_list',$stall_list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    // 菜品新增
    public function foodAdd(){
        $ma_id = session('ma_id');
        if(IS_AJAX){
            $type = I('type');
            $dishes_no = M('dishes')->where(array('is_del'=>2))->order('dishes_no DESC')->find();
            $str = $dishes_no['dishes_no']+1;
            $newStr= sprintf('%06s', $str);
            $dataD['dishes_no'] = $newStr;
            $dataD['ma_id'] = $ma_id;
            $dataD['type'] = $type;
            $dataD['stall'] = I('stall');
            $dataD['dishes_cate_id'] = I('dishes_cate_id');
            $dataD['dishes_name'] = I('dishes_name');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            $upload->saveRule  =     'time';
            //$upload->autoSub = false;
            $info   =   $upload->upload();
            if($info) {
                $dataD['pic_url'] = substr(C('UPLOAD_DIR'),1).$info['img']['savepath'].$info['img']['savename'];//如果上传成功则完成路径拼接
            }
            $dataD['num'] = I('num');
            $dataD['price'] = I('price');
            $dataD['hot'] = I('hot');
            $dataD['statue'] = I('statue');
            $dataD['state'] = I('state');
            $dataD['content'] = I('content');
            $dataD['discount'] = I('discount');
            $dataD['sort'] = I('sort');
            // $dataD['status'] = implode(',', I('status'));
            $dataD['addtime'] = time();
            $dishes_id = M('dishes')->add($dataD);
            if($dishes_id){
                $dishes_meal_db = M('dishes_meal');
                if($type == 2){
                    foreach (I('ids') as $k => $v){
                        $dataM['ma_id'] = $ma_id;
                        $dataM['dishes_id'] = $dishes_id;
                        $dataM['dishes_ids'] = $v;
                        $val = $k + 1;
                        if($val%3 == 0){
                            $dataM['type'] = 3;
                        }else if($val%3 == 2){
                            $dataM['type'] = 2;
                        }else{
                            $dataM['type'] = 1;
                        }
                        $dataM['day'] = ceil($val/3);
                        $dataM['addtime'] = time();
                        $dishes_meal_db->add($dataM);
                    }
                }
                $this->success('添加成功！',U('foodList'),1);
            }else{
                $this->error('添加失败！',U('foodList'),0);
            }

        }
        $dishes_category_db = M('dishes_category');
        $dishes_cate_list = $dishes_category_db->where(array('is_del'=>2,'ma_id'=>$ma_id))->select();
        $this->assign('dishes_cate_list',$dishes_cate_list);
        $this->display();
    }

    // 菜品编辑
    public function foodEdit(){
        $ma_id = session('ma_id');
        $dishes_id = I('dishes_id');
        if(IS_AJAX){
            // 档口变化清除购物车
            $stall = M('dishes')->where(array('dishes_id'=>$dishes_id))->getField('stall');
            if($stall !== I('stall')){
                M('cart')->where(array('dishes_id'=>$dishes_id))->delete();
            }
            $type = I('type');
            $dataD['type'] = $type;
            $dataD['stall'] = I('stall');
            $dataD['dishes_cate_id'] = I('dishes_cate_id');
            $dataD['dishes_name'] = I('dishes_name');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            $upload->saveRule  =     'time';
            //$upload->autoSub = false;
            $info   =   $upload->upload();
            if($info) {
                $dataD['pic_url'] = substr(C('UPLOAD_DIR'),1).$info['img']['savepath'].$info['img']['savename'];//如果上传成功则完成路径拼接
            }
            $dataD['num'] = I('num');
            $dataD['price'] = I('price');
            $dataD['hot'] = I('hot');
            $dataD['statue'] = I('statue');
            $dataD['state'] = I('state');
            $dataD['content'] = I('content');
            $dataD['discount'] = I('discount');
            $dataD['sort'] = I('sort');
            // $dataD['status'] = implode(',', I('status'));
            // dump($dataD);die;
            $res = M('dishes')->where(array('dishes_id'=>$dishes_id))->save($dataD);
            if($res !== false){
                $dishes_meal_db = M('dishes_meal');
                $dishes_meal_db->where(array('dishes_id'=>$dishes_id))->delete();
                if($type == 2){
                    foreach (I('ids') as $k => $v){
                        $dataM['ma_id'] = $ma_id;
                        $dataM['dishes_id'] = $dishes_id;
                        $dataM['dishes_ids'] = $v;
                        $val = $k + 1;
                        if($val%3 == 0){
                            $dataM['type'] = 3;
                        }else if($val%3 == 2){
                            $dataM['type'] = 2;
                        }else{
                            $dataM['type'] = 1;
                        }
                        $dataM['day'] = ceil($val/3);
                        $dataM['addtime'] = time();
                        $dishes_meal_db->add($dataM);
                    }
                }
                $this->success('编辑成功！',U('foodList'),1);
            }else{
                $this->error('编辑失败！',U('foodList'),0);
            }

        }
        $infos = M('dishes')->where(array('dishes_id'=>$dishes_id))->find();
        $dishes_category_db = M('dishes_category');
        $dishes_db = M('dishes');
        $stall_db = M('stall');
        $dishes_cate_list = $dishes_category_db->where(array('is_del'=>2,'ma_id'=>$ma_id))->select();
        $where['delete'] = 2;
        $where['is_freeze'] = 2;
        $where['ma_id'] = $ma_id;
        if($infos['type'] == 1){
            $where['stall_type'] = 1;
        }else{
            $where['stall_type'] = 2;
        }
        $stall_list = $stall_db->where($where)->select();
        $dishes_meal = M('dishes_meal')->where(array('dishes_id'=>$dishes_id))->order('meal_id ASC')->select();
        foreach ($dishes_meal as $k => $v){
            $names = '';
            $ids = explode(',',$v['dishes_ids']);
            $list = $dishes_db->where(array('dishes_id'=>array('in',$ids)))->select();
            foreach ($list as $key => $val){
                $names .= $val['dishes_name'].',';
            }
            $names = trim($names,',');
            $dishes_meal[$k]['dishes_name'] = $names;
        }
        $this->assign('dishes_cate_list',$dishes_cate_list);
        $this->assign('stall_list',$stall_list);
        $this->assign('infos',$infos);
        $this->assign('dishes_meal',$dishes_meal);
        $this->assign('a',1);
        $this->assign('b',2);
        $this->display();
    }

    // 上下架
    public function isSale()
    {
        $id=I('x');
        $status = M('dishes')->where(array('dishes_id'=>$id))->getField('state');//判断当上下架状态
        if($status==2){
            //判断当前状态，能否上架
            M('dishes')->where(array('dishes_id'=>$id))->setField('state',1);
            $this->success('上架',1,1);
            // }
        }else{
            M('dishes')->where(array('dishes_id'=>$id))->setField('state',2);
            // 删除购物车
            M('cart')->where(array('dishes_id'=>$id))->delete();
            $this->success('下架',1,1);
        }
    }

    // 选择菜品
    public function choose(){
        $where = array();
        $dishes_category_db = M('dishes_category');
        $stall_db = M('stall');
        $ma_id = session('ma_id');
        $where['a.ma_id'] = $ma_id;
        $where['a.state'] = 1;
        $where['a.is_del'] = 2;
        $where['a.type'] = 1;
        $search = I('search');
        $nid = I('nid');
        if($search){
            if($search['dishes_name']){
                $where['a.dishes_name'] = array('like','%'.trim($search['dishes_name']).'%');
            }
            if($search['dishes_cate_id']){
                $where['b.dishes_cate_id'] = $search['dishes_cate_id'];
            }
        }
        //分页
        $count= M('dishes as a')
            ->join('LEFT JOIN sm_dishes_category as b on a.dishes_cate_id = b.dishes_cate_id')
            ->join('LEFT JOIN sm_stall as c on a.stall = c.stall_id')
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('dishes as a')
            ->join('LEFT JOIN sm_dishes_category as b on a.dishes_cate_id = b.dishes_cate_id')
            ->join('LEFT JOIN sm_stall as c on a.stall = c.stall_id')
            ->where($where)
            ->order("a.addtime DESC")
            ->field('a.dishes_id,a.dishes_name,c.stall_name,b.cat_name,a.num,a.price')
            ->select();
        $dishes_cate_list = $dishes_category_db->where(array('is_del'=>2,'ma_id'=>$ma_id))->select();
        $stall_list = $stall_db->where(array('delete'=>2,'is_freeze'=>2,'ma_id'=>$ma_id))->select();
        $this->assign('list',$list);
        $this->assign('dishes_cate_list',$dishes_cate_list);
        $this->assign('stall_list',$stall_list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->assign('nid',$nid);
        $this->display();
    }

    // 删除菜品
    public function foodDel()
    {
        $dishes_db = M('dishes');
        $dishes_id = I('dishes_id');
        $res = $dishes_db->where(array('dishes_id'=>$dishes_id))->setField('is_del',1);
        if($res !== false){
//            M('dishes_meal')->where(array('dishes_id'=>$dishes_id))->delete();
            $this->success('删除成功！',U("foodList"),1);
        }else{
            $this->success('删除失败！',U("foodList"),0);
        }
    }

    public function type(){
        $type = I('type');
        $stall_db = M('stall');
        $ma_id = session('ma_id');
        if($type == 1){
            $stall_list = $stall_db->where(array('delete'=>2,'is_freeze'=>2,'ma_id'=>$ma_id,'stall_type'=>1))->select();
        }else{
            $stall_list = $stall_db->where(array('delete'=>2,'is_freeze'=>2,'ma_id'=>$ma_id,'stall_type'=>2))->select();
        }
        $this->ajaxReturn($stall_list);
    }
}