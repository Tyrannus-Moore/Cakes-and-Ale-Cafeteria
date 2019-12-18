<?php
// +----------------------------------------------------------------------
// |  积分管理
// +----------------------------------------------------------------------
namespace Integral\Controller;
use Common\Controller\AuthController;
use Org\Util\Stringnew;
class IntegralController extends AuthController {
	/*
     * 轮播图
     */
	public function banner(){
		$banner_db = M('banner');
		$count= $banner_db->count();// 查询满足要求的总记录数
		$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show= $Page->show();
		$list = $banner_db->limit($Page->firstRow.','.$Page->listRows)->order('edittime desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
	//修改轮播图
	public function bannerEdit(){
		if(IS_AJAX){
			$banner_id = I('banner_id');
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
                    foreach($info as $file){
                        if ($file['key']=='img'){//单图路径数组
                            $data["image"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
                        }
                    }
                }
            }
			$data['name'] = I('name');
			$data['url'] = I('url');
			$data['edittime'] = time();
			//添加学校信息
	        $result = M('banner')->where(array('banner_id'=>$banner_id))->save($data);
			if($result!==false){
				$this->success('修改成功',U('banner'),1);
			}else{
				$this->error('修改失败',U('banner'),0);
			}
		}else{
			$infos = M('banner')->where(array('banner_id'=>I('banner_id')))->find();
			$this->assign('infos',$infos);
			$this->display();
		}
	}
	//轮播图状态操作
    public function bannerState(){
        $id=I('x');
        if (empty($id)){
            $this->error('ID不存在',U('banner'),0);
        }
        $status=M('banner')->where(array('banner_id'=>$id))->getField('is_show');//判断当前状态情况
        if($status==1){
            $statedata = array('is_show'=>2);
            M('banner')->where(array('banner_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('banner',array('p'=>I('p'))),0);
        }else{
            $statedata = array('is_show'=>1);
            M('banner')->where(array('banner_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('banner',array('p'=>I('p'))),0);
        }
    }
    //积分商品分类列表
    public function category(){
    	//搜索
		$search = I('search');
		if($search['cat_name']){
			$where['cat_name'] = array('like',"%".trim($search['cat_name'])."%");
		}
		$where['is_del'] = 1;
		
		$goods_category_db = M('goods_category');
		$count= $goods_category_db->where($where)->count();// 查询满足要求的总记录数
		$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show= $Page->show();
		$list = $goods_category_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('addtime desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('search',$search);
    	$this->display();
    }
    //积分商品分类添加
    public function categoryAdd(){

    	if(IS_AJAX){
    		$data['cat_name'] = I('cat_name');
    		$data['addtime'] = time();
			//添加分类信息
	        $result = M('goods_category')->add($data);
			if($result!==false){
				$this->success('添加成功',U('category'),1);
			}else{
				$this->error('添加失败',U('category'),0);
			}
    	}
    	else
    	{
	    	$this->display();
    	}
    }
    // 修改显示页面
    public function categoryEdit(){
        $cat_id = I('cat_id');
		$goods_category_db = M('goods_category');
		$infos = $goods_category_db->where(array('cat_id'=>$cat_id))->find();
        $infos['status'] = 1;

        $this->ajaxReturn($infos,'json');
    }
    // 执行修改操作
    public function categoryRunEdit(){
        $cat_id = I('cat_id');
		$goods_category_db = M('goods_category');
		$data['cat_name'] = I('cat_name');
		$data['addtime'] = time();
		//添加学校信息
        $result = $goods_category_db->where(array('cat_id'=>$cat_id))->save($data);
		if($result!==false){
			$this->success('修改成功',U('category'),1);
		}else{
			$this->error('修改失败',U('category'),0);
		}
    	
    }
    /*
     * 删除
     */
	public function categoryDel(){
		$p = I('p');
		$cat_id = I('cat_id');
		//判断是否存在商家或者用户
		$is_good = M('goods')->where(array('cat_id'=>$cat_id,'is_del'=>1))->find();
		if($is_good){
			$this->error('该分类下存在商品，请修改商品的分类或删除该分类下的商品！',U('category', array('p' => $p)),0);
		}
		$rst = M('goods_category')->where(array('cat_id'=>$cat_id))->setField('is_del',2);
		if($rst!==false){
            $this->success('删除成功',U('category', array('p' => $p)),1);
        }else{
            $this->error('删除失败',U('category', array('p' => $p)),0);
        }
	}
	//商品列表
	public function goods(){
		$where = array();
        //搜索
        $search = I('search');
        if($search){
            if($search['goods_name']){
                $where['a.goods_name'] =  array('like','%'.trim($search['goods_name']).'%');
            }
            if($search['cat_id']){
                $where['a.cat_id'] =  $search['cat_id'];
            }
            if($search['addtime']){
                $startTime = substr($search['addtime'],0,10);
                $endTime = substr($search['addtime'],13);
                if($startTime && $endTime){
                    $where['a.addtime'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
        }
        $where['a.is_del'] = 1;
        $count = M('goods')->where($where)->join("AS a LEFT JOIN __GOODS_CATEGORY__ AS b ON a.cat_id = b.cat_id")->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        $list = M('goods')->where($where)->join("AS a LEFT JOIN __GOODS_CATEGORY__ AS b ON a.cat_id = b.cat_id")
            ->limit($Page->firstRow.','.$Page->listRows)->order('a.sort ASC,a.addtime DESC')->select();
        $categorys = M('goods_category')->where("is_del=1")->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('search',$search);
        $this->assign('categorys',$categorys);
		$this->display();
	}
	//添加
	public function goodsAdd(){
		if(IS_AJAX){
			//图片
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728000000 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
            $upload->savePath  =     'Banner/'; // 设置附件上传（子）目录
            $upload->saveRule  =     'time';
            //$upload->autoSub = false;
            $info   =   $upload->upload();
            if($info) {
                foreach($info as $file){
                    if ($file['key']=='img'){//单图路径数组
                        $data["pic_url"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
                    }
                }
            }
			$data['cat_id'] = I('cat_id');
			$data['goods_name'] = I('goods_name');
			$data['num'] = I('num');
			$data['content'] = I('content');
            $data['intergral'] = I('intergral');
			$data['sort'] = I('sort');
			$data['addtime'] = time();
			//添加商品信息
	        $result = M('goods')->add($data);
			if($result!==false){
				$this->success('添加成功',U('goods'),1);
			}else{
				$this->error('添加失败',U('goods'),0);
			}
		}else{
			//查找分类名称
			$categorys = M('goods_category')->where(array('is_del'=>1))->order('addtime desc')->select();
			$this->assign('categorys',$categorys);
			$this->display();
		}
	}
	//修改
	public function goodsEdit(){
		if(IS_AJAX){
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
	                foreach($info as $file){
	                    if ($file['key']=='img'){//单图路径数组
	                        $data["pic_url"] = substr(C('UPLOAD_DIR'),1).$file[savepath].$file[savename];
	                    }
	                }
	            }
	        }
			$data['cat_id'] = I('cat_id');
			$data['goods_name'] = I('goods_name');
			$data['num'] = I('num');
			$data['content'] = I('content');
			$data['intergral'] = I('intergral');
            $data['sort'] = I('sort');
			$data['addtime'] = time();
			//添加商品信息
	        $result = M('goods')->where(array('goods_id'=>I('goods_id')))->save($data);
			if($result!==false){
				$this->success('修改成功',U('goods'),1);
			}else{
				$this->error('修改失败',U('goods'),0);
			}
		}else{
			//查找分类名称
			$categorys = M('goods_category')->where(array('is_del'=>1))->order('addtime desc')->select();
			$this->assign('categorys',$categorys);
			//查找商品信息
			$infos = M('goods')->where(array('goods_id'=>I('goods_id')))->find();
			$this->assign('infos',$infos);
			$this->display();
		}
	}
	//上下架
	public function goodsState(){
		$id=I('x');
        if (empty($id)){
            $this->error('ID不存在',U('goods'),0);
        }
        $status=M('goods')->where(array('goods_id'=>$id))->getField('state');//判断当前状态情况
        if($status==1){
            $statedata = array('state'=>2);
            M('goods')->where(array('goods_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('goods',array('p'=>I('p'))),0);
        }else{
            $statedata = array('state'=>1);
            M('goods')->where(array('goods_id'=>$id))->setField($statedata);
            $this->success('操作成功',U('goods',array('p'=>I('p'))),0);
        }
	}
	 /*
     * 删除
     */
	public function goodsDel(){
		$p = I('p');
		$goods_id = I('goods_id');
		//判断是否存在商家或者用户
		$rst = M('goods')->where(array('goods_id'=>$goods_id))->setField('is_del',2);
		if($rst!==false){
            $this->success('删除成功',U('goods', array('p' => $p)),1);
        }else{
            $this->error('删除失败',U('goods', array('p' => $p)),0);
        }
	}
	//商品订单
	public function orders(){
		$where = array();
        $integral_order_db = M('integral_order');
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
            if($search['ordersn']){
                $where['a.ordersn'] = array('like','%'.trim($search['ordersn']).'%');
            }
            if($search['telphone']){
                $where['a.telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['username']){
                $where['c.member_list_nickname'] = array('like','%'.trim($search['username']).'%');
            }
            if($search['status']){
                $where['a.status'] = $search['status'];
            }else{
                $where['a.status'] = array('neq',0);
            }
            if($search['state']){
                $where['a.state'] = $search['state'];
            }
        }else{
            $where['a.status'] = array('neq',0);
        }
        //分页
        $count= $integral_order_db->alias('a')
            ->join('LEFT JOIN __GOODS__ AS b ON a.goods_id = b.goods_id')
            ->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.member_list_id = c.member_list_id')
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $integral_order_db->alias('a')
            ->field('a.*,b.goods_name,c.member_list_nickname')
            ->join('LEFT JOIN __GOODS__ AS b ON a.goods_id = b.goods_id')
            ->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.member_list_id = c.member_list_id')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order("a.addtime DESC")
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
		$this->display();
	}
	//发货
	public function deliver(){
		if(IS_AJAX){
            $type = I('type');
            if($type == 1){
                $data['log_company'] = I('log_company');
                $data['log_order'] = I('log_order');
            }else{
                $data['log_company'] = I('username');
                $data['log_order'] = I('telphone');
            }
			
            $data['type'] = $type;
			$data['status'] = 2;
			$data['sendtime'] = time();
			$result = M('integral_order')->where(array('order_id'=>I('order_id')))->save($data);
			if($result){
				$this->success('发货成功',U('orders'),1);
			}else{
				$this->error('发货失败',U('orders'),1);
			}
		}else{
			$order_id = I('order_id');
			$this->assign('order_id',$order_id);
			$this->display();
		}
	}
	//订单详情
	public function orderDetail(){
		$infos = M('integral_order as a')->field('a.*,c.member_list_nickname,c.telphone as member_tel,d.address as saddress,d.school_id,d.mention,d.ma_tel,b.goods_name,b.pic_url')->where(array('a.order_id'=>I('order_id')))->join('LEFT JOIN __GOODS__ AS b ON a.goods_id = b.goods_id')->join('LEFT JOIN __MEMBER_LIST__ AS c ON a.member_list_id = c.member_list_id')->join('LEFT JOIN __MERCHANT__ AS d ON a.ma_id = d.ma_id')->find();
		if($infos['state'] == 1){
			$infos['ma_address'] = $infos['address'];
			$infos['ma_tel'] = $infos['ma_tel'];
		}else{
			$res = M('merchant')->where(array('school_id'=>$infos['school_id'],'mention'=>1))->limit(1)->find();
			$infos['ma_address'] = $res['saddress'];
			$infos['ma_tel'] = $res['ma_tel'];
		}
		$this->assign('infos',$infos);
		$this->display();
	}
	//积分统计
	public function statistics(){
		$where = array();
        $integral_statistics_db = M('integral_statistics');
        $search = I('search');
        if($search){
            if($search['start_time']){
                $where['a.creattime'] = array('egt',strtotime($search['start_time']));
            }
            if($search['end_time']){
                $where['a.creattime'] = array('elt',strtotime($search['end_time']." 23:59:59"));
            }
            if($search['start_time'] && $search['end_time']){
                $where['a.creattime'] = array(array('egt',strtotime($search['start_time'])),array('elt',strtotime($search['end_time']." 23:59:59")));
            }
            if($search['ordersn']){
                $where['a.order_no'] = array('like','%'.trim($search['ordersn']).'%');
            }
            if($search['telphone']){
                $where['b.telphone'] = array('like','%'.trim($search['telphone']).'%');
            }
            if($search['username']){
                $where['b.member_list_nickname'] = array('like','%'.trim($search['username']).'%');
            }
            if($search['type']){
                $where['a.type'] = $search['type'];
            }
        }
        //分页
        $count= $integral_statistics_db->alias('a')
            ->join('LEFT JOIN __MEMBER_LIST__ AS b ON a.member_list_id = b.member_list_id')
            ->where($where)
            ->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = $integral_statistics_db->alias('a')
            ->join('LEFT JOIN __MEMBER_LIST__ AS b ON a.member_list_id = b.member_list_id')
            ->field('a.*,b.member_list_nickname,b.telphone')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order("a.creattime DESC")
            ->select();

        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
		$this->display();
	}
    //维护信息
    public function maintain(){
        if(IS_AJAX){
            $res = M('sysconfig')->where(array('varname'=>'cfg_integral'))->setField('value',I('cfg_integral'));
            $res = M('sysconfig')->where(array('varname'=>'cfg_freight'))->setField('value',I('cfg_freight'));
            if($res!==false){
                $this->success('修改成功',U('maintain'),1);
            }else{
                $this->error('修改失败',U('maintain'),1);
            }
        }else{
            $integral = M('sysconfig')->where(array('varname'=>'cfg_integral'))->getField('value');
            $freight = M('sysconfig')->where(array('varname'=>'cfg_freight'))->getField('value');
            $this->assign('integral',$integral);
            $this->assign('freight',$freight);
            $this->display();
        }
        
    }
}