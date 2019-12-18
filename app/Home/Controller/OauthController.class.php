<?php
namespace Home\Controller;
use Home\Model\UploadModel;
use Think\Controller;
class OauthController extends Controller
{
	public function login()
	{

        $ma_id = I('ma_id'); //商家id

        ob_clean();
        $shopData = M('merchant')->where("ma_id='$ma_id'")->field("appid,appsecret")->find();

        if(empty($ma_id)){
            $this->error('商家ID不能为空!');
        }
        //微信授权登录
		if (!isset($_GET['code'])){	

			//获取当前url
			$redirect_uri = get_current_url();

			$scope = 'snsapi_userinfo';//微信固定参数，弹出授权页面
			$oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $shopData['appid'] . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . $scope . '&state=wx_oauth#wechat_redirect';

			header('Location:' . $oauth_url);
			exit;
		}
		if(isset($_GET['code']) && isset($_GET['state']) && isset($_GET['state']) == 'wx_oauth'){
			$result = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $shopData['appid'] . '&secret=' . $shopData['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code');
			$access_token = json_decode($result,true);
			if(empty($access_token['openid'])){
				$this->error('用户信息获取失败!');
			}
			// 获取微信用户基本信息  拉取用户信息
			$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token['access_token']."&openid=".$access_token['openid']."&lang=zh_CN";
			$rt = file_get_contents($url);
			$userinfo = json_decode($rt ,true);
			if(empty($userinfo)){
				$this->error('获取用户详细信息失败');
			}
			$openid = $userinfo['openid'];

			//授权登录
			if($openid){
				//记录微信个人信息
				$memberData = M('member_list')->where(array("openid"=>$openid,'ma_id'=>$ma_id,'is_del'=>2))->find();
				session("ma_id",$ma_id);
				if($memberData){
				    if ($memberData['unionid'] == ""){
				        $data['unionid'] = $userinfo['unionid'];
                        M('member_list')->where(array('openid'=>$openid))->save($data);
                    }
					$_SESSION['member_list_id'] = $memberData['member_list_id'];
					$this->redirect('Home/Drink/index');
				}else{
					//该用户以前未保存微信信息
                    $data['ma_id']         = $ma_id;
					$data["openid"]	       = $userinfo["openid"];
                    $images = new UploadModel();
                    $data['member_list_headpic'] = $images->creat_icon($userinfo['headimgurl']);
                    $data["member_list_nickname"]= $userinfo["nickname"];
                    if($userinfo['sex'] == 0){$userinfo['sex'] = 3;}
                    $data["member_list_sex"] 	= $userinfo['sex'];
					$data["addtime"] 		    = time();
					$data['unionid'] = $userinfo['unionid'];
					$res = M('member_list')->add($data);
					$_SESSION['member_list_id'] = $res;
					$this->redirect('Home/Drink/index');
				} 					
			}else{
				$this->error('授权失败');
			}
		}
	}

	//测试方法
    public function index()
    {
        $this->display();
    }
}