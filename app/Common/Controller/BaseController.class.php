<?php
// +----------------------------------------------------------------------
// | 功能:基础后台
// +----------------------------------------------------------------------
// | 作者:杨少雄
// +----------------------------------------------------------------------
// | 时间:2017.7.19
// +----------------------------------------------------------------------

namespace Common\Controller;
use Common\Controller\CommonController;
class BaseController extends CommonController
{
    public $member_list_id;
    public $member_list_groupid;
	//初始化
	public function _initialize(){
        parent::_initialize();
		$memberInfo = session('memberInfo');
//		dump($memberInfo);
		if($memberInfo){
			$member_list_id = $memberInfo['member_list_id'];
		}else{
			$member_list_id = I("member_list_id");
	 		if(!$member_list_id)
	 		{
	 			$this->make_json_error('请登录',999);
	 		}
		}
		$member_info = M('member_list')->where(array('member_list_id'=>$member_list_id,'is_delete'=>1))->find();
		if(!$member_info){
			$this->make_json_error('账户不存在',998);
		}elseif($member_info['member_list_open'] == 2){
			$this->make_json_error('账号停用状态',997);
		}else{
			$this->member_list_id = $member_info['member_list_id'];
			$this->member_list_groupid = $member_info['member_list_groupid'];
//			$this->make_json_result('自动登录',100);
		}		 		
	}
}