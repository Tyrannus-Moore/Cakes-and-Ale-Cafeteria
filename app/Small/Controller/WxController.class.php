<?php 
namespace Small\Controller;
use Small\Model\UploadModel;
use Think\Controller;
class WxController extends Controller
{

  //单独获取tonk
    public function logincode(){
        $data = json_decode(file_get_contents('php://input'),true);

        $params = [
            'appid'=>'wx6f20b935cb808182',
            'secret'=>'8dc48e526ece28eb48db4aea5ecf2947',
            'js_code'=>$data['code'],
            'grant_type'=>'authorization_code'
        ];

        $result = $this->http("https://api.weixin.qq.com/sns/jscode2session",$params);
        $res = json_decode($result,true);

        if($res){
            $data['code']  = 200;
            $data['msg'] = '加载成功！';
            $data['data'] = $res;
            $this->ajaxReturn($data);
        }else{
            $data['code']  = 100;
            $data['msg'] = '内部错误！';
            $this->ajaxReturn($data);
        }
    }

    public function getunionID(){
//        $token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxc0b30000d65d2286&secret=d6456ef51bdda32fddb30af5920d8638";
////
//        $res = $this->curlRequest($token_url);
////
//        echo $res;die;
        $access_token = "24__bNQf9W5SysYSIkX8pq0YoI-a7OIBDId8rgxx-FPCBjqKhYhSjsrLKEmjRiA61kM61_nWPLQDXCBvf5NDPeNBLCOWRNt4OI8Np2tBYL0THvtbnjyaGqXwvv3hwiwMDYa7eUPAcZGdHc_HSehJTJjAJACMC";
//        $openid = "o0WsF5gs_fYWxvMm_lDGjYDksNEw";

        $url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=$access_token";
        $data = [
            "user_list" =>  [
                [
                    "openid"   =>  "o0WsF5gs_fYWxvMm_lDGjYDksNEw",
                    "lang"     =>   "zh-CN",
                ],
            ],
        ];
        $json = json_encode($data);
//        echo $json;die;
        $result = $this->curlRequest($url , $json);
        $res = json_decode($result,true);

        print_r($res);die;
    }


  //授权信息
  public function userdata(){
      session_start();
      $data = json_decode(file_get_contents('php://input'),true);


      $datas = $data['userInfo'];

      // echo header("Content-type:text/html;charset=utf-8");
      // echo '<pre>';
      // print_r($datas);
      // var_dump(session_start());
      // unset($_SESSION['ma_id']);
      // $_SESSION['ma_id'] = $datas['ma_id'];
      // print_r($_SESSION);
      // exit;

//      $memberData = M('member_list')->where(array("openid"=>$datas['openid'],'ma_id'=>$datas['ma_id'],'is_del'=>2))->find();
      $memberData = M('member_list')->where(array("unionid"=>$datas['unionid'],'ma_id'=>$datas['ma_id'],'is_del'=>2))->find();
      // session("ma_id",$datas['ma_id']);

      // echo '<pre>';
      // $_SESSION['member_list_id'] = $memberData['member_list_id'];
      // print_r($_SESSION);
      // exit;

      if($memberData){
        $data['code']  = 100;
        $data['msg'] = '该用户已存在！';
        $data['ma_id'] = $datas['ma_id'];
        $data['member_list_id'] = $memberData['member_list_id'];
        $this->ajaxReturn($data);
      }else{
        //该用户以前未保存微信信息
          $arr['ma_id']         = $datas['ma_id'];
          $arr["openid"]        = $datas["openid"];
//          $arr["unionid"]        = $datas["unionid"];
          $arr['member_list_headpic'] = $datas['avatarUrl'];
          $arr["member_list_nickname"] = $datas['nickName'];
          if($gen['gender'] == 0){$datas['gender'] = 3;}
          $arr["member_list_sex"]  = $datas['gender'];
          $arr["addtime"]        = time();
          $arr["school_id"]  = 2;

          // print_r($arr);die;
          $res = M('member_list')->add($arr);
          $data['ma_id'] = $datas['ma_id'];
          $data['member_list_id'] = $res;
          $data['code']  = 200;
          $data['msg'] = '授权成功';
          $this->ajaxReturn($data);
      }
  }

  // public function SetuserAction(){
  //   $openid = SGet('openid');
  //   $row = DB::getone('select * from @pf_user_all where openid=?',[$openid]);
  //     if($row){
  //       $this->AjaxReturn(100,'该用户已存在!',$row);
  //     }else{
  //       $this->AjaxReturn(200,'该用不存在！');
  //     }
  // }

  //图片路径
    public function thumbUrl($arr = []){
        foreach ($arr as $k => &$v) {
            $v['image'] = 'https://'.$_SERVER['SERVER_NAME'].$v['image'];
        }
        return $arr;
    }


    //内容图片路径
    public function conthumburl($row){
        return htmlspecialchars_decode(str_replace('/upfiles','https://'.$_SERVER['SERVER_NAME'].'/upfiles',htmlspecialchars($row)));
    }


    //获取自建session中的值
    public function Sessions(){
        if ($_COOKIE['sessions']) {
            session_id($_COOKIE['sessions']);
        }

        session_start();
        $arr = session_id();
        $sessions = json_decode($arr,true);
        return $sessions;
    }

    /**
     * 网络请求方法
     * @param  string $url    请求url
     * @param  string $param  GET请求参数
     * @param  string $data   POST请求参数
     * @param  string $method 请求方式
     * @return miexid 网络请求返回的数据
     */
    public function http($url, $param = '', $data = '', $method = 'GET') {

        $opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        );
        // 根据get请求参数组织新的url地址
        $opts[CURLOPT_URL] = $url . '?' . http_build_query($param);

        // 进行post请求
        if ($method == 'POST') {
            $opts[CURLOPT_POST] = true;
            $opts[CURLOPT_POSTFIELDS] = $data;

            if (is_string($data)) {
                $opts[CURLOPT_HTTPHEADER] = array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data));
            }
        }

        // 执行curl请求
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /**
    使用curl方式实现get或post请求
    @param $url 请求的url地址
    @param $data 发送的post数据 如果为空则为get方式请求
    return 请求后获取到的数据
     */
    public function curlRequest($url,$data = ''){
        $ch = curl_init();
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_TIMEOUT] = 30; //超时时间
        if(!empty($data)){
            $params[CURLOPT_POST] = true;
            $params[CURLOPT_POSTFIELDS] = $data;
        }
        $params[CURLOPT_SSL_VERIFYPEER] = false;//请求https时设置,还有其他解决方案
        $params[CURLOPT_SSL_VERIFYHOST] = false;//请求https时,其他方案查看其他博文
        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行
        curl_close($ch); //关闭连接
        return $content;
    }

}