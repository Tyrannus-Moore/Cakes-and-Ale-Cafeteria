<?php
//ajax控制器
namespace Ajax\Controller;
use Common\Controller\CommonController;
class AjaxController extends CommonController{
	/*
     * 返回行政区域json字符串
     */
	public function getRegion(){
		$Region=M("region");
		$map['pid']=I('pid');
		$map['type']=I('type');
		$list=$Region->where($map)->select();
		echo json_encode($list);
	}
	/*
	 * 返回产品分类名json数组
	 */
	public function getCats(){
		$Region=M("goods_category");
		$map['parint_id']=I('pid');
		$list=$Region->where($map)->order('listorder ASC')->select();
		echo json_encode($list);
	}
	/*
	 * 返回产品规格价格及库存
	 */
	public function getGoodsAttr(){
		$goodid = I('goodid');
		$map_attr['goods_id'] = $goodid;
		$map_attr['type'] = 1;
		$map_attr['goods_price']  =array('gt',0);
		$goods_info_price = M('goods_attr')->field('goods_price,goods_stock')->where($map_attr)->order('id ASC')->select();
		echo json_encode($goods_info_price);
	}
	//Ajax获取地区
	public function getCity()
	{
		$pid = I('pid');
		$citylist = M('region')->where("pid = $pid")->select();
		$str = '';
		foreach ($citylist as $v) {
			$str .= "<option value='".$v['cityid']."'>".$v['name']."</option>";
		}
		$this->ajaxReturn($str);
	}

    //获取班级二级分类名称
    public function faculty()
    {
        $faculty_id = I('id');
        $infos = M('faculty')->where(array('faculty_id'=>$faculty_id))->find();
        $str = '';
        for ($i=1; $i<=$infos['num']; $i++) {
            $str .= "<option value='".$i."班'>".$i."班</option>";
        }
        $this->ajaxReturn($str);
    }

	//验证会员手机号
	public function isTel(){
		$where['member_list_username'] =I('tel');
		if(I('id')){
			$where['member_list_id'] = array('neq',I('id'));
		}
		$result = M('member_list')->where($where)->find();
		if($result){
			echo 1;
		}else{
			echo 2;
		}
	}
	//验证会员邮箱
	public function isEmail(){
		$where['member_list_email'] =I('email');
		if(I('id')){
			$where['member_list_id'] = array('neq',I('id'));
		}
		$result = M('member_list')->where($where)->find();
		if($result){
			echo 1;
		}else{
			echo 2;
		}
	}
	//验证管理员被占用
	public function isAdmin(){
		$where['admin_username'] =I('username');
		if(I('id')){
			$where['admin_id'] = array('neq',I('id'));
		}
		$result = M('admin')->where($where)->find();
		if($result){
			echo 1;
		}else{
			echo 2;
		}
	}
	//验证楼盘名是否存在
	public function isBuilTitle(){
		$where['buil_title'] = I('buil_title');
		$where['is_delete'] = 0;
		$result = M('buildings')->where($where)->find();
		if($result){
			echo 1;
		}else{
			echo 2;
		}
	}
	/*
	 * 地图点击获取经纬度
	 */
	public function marker(){
		$lnglat = I('lnglat');
		if($lnglat){
			$map = explode(',',$lnglat);
			$this->assign('lng',$map[0]);
			$this->assign('lat',$map[1]);
		}else{
			$this->assign('lng','116.404');
			$this->assign('lat','39.915');
		}
		$this->display();
	}
	/*
	 * 地图点击获取经纬度
	 */
	public function marker1(){
		$lnglat = I('lnglat');
		if($lnglat){
			$map = explode(',',$lnglat);
			$this->assign('lng',$map[0]);
			$this->assign('lat',$map[1]);
		}else{
			$this->assign('lng','116.404');
			$this->assign('lat','39.915');
		}
		$this->display();
	}

    /*
     * 地图点击获取经纬度
     */
    public function marker3(){
        $lnglat = I('lnglat');
        if($lnglat){
            $map = explode(',',$lnglat);
            $this->assign('lng',$map[0]);
            $this->assign('lat',$map[1]);
        }else{
            $this->assign('lng','116.404');
            $this->assign('lat','39.915');
        }
        $this->display();
    }
	/*
	 *查询小区
	 */
	public function plot(){
		$plot_name = I('name');
		if($plot_name !=''){
			$plot['plot_name'] = array('like',"%".$plot_name."%");
			$plot['is_delete'] = 1;
			$result = M('plot')->where($plot)->field('plot_name,plot_id')->select();
			$str = '';
			foreach ($result as $v) {
				$str .= "<li class='li2' data=".$v['plot_id'].">".$v['plot_name']."</li>";
			}
			$this->ajaxReturn($str);
		}	
	}
	/*
	 *去除沙盘中的楼栋
	 */
	public function delSpld(){
		$where['hnid'] = I('hdid');
		$data['x'] = '0px';
		$data['y'] = '0px';
		$data['is_show'] = 0;
		$result = M('housenum')->where($where)->save($data);
		if($result){
			echo 1;//成功
		}else{
			echo 2;//失败
		}
	}
	/*
	 *新楼盘刷新
	 */
	public function refreshLou(){
		$p =I('p',1);
		$where['buildings_id'] = I('buildings_id');
		$data['refresh_time']=time();//刷新时间
 
		$info = M('buildings')->where($where)->save($data);
		if($info)
		{
			$this->success('刷新成功',U('Houses/Index/index',array('p'=>$p)),1);
		}else{
			$this->error('刷新失败',U('Houses/Index/index',array('p'=>$p)),0);
		}
 
	}
}