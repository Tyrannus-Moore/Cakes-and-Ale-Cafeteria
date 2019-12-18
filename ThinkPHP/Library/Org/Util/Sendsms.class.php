<?php
namespace Org\Util;
class Sendsms
{


//	public function __construct()
//	{
//		$this->userid = '';
//		$this->account = ' AB00203';
//		$this->password = 'AB0020345';
//		$this->url = 'http://dx.ipyy.net/sms.aspx';
//	
//	}
	public function sendsms($mobile,$content)
	{
		//企业ID $userid
		$userid = '1111';
		//用户账号 $account
		$account = 'AG00058';
		//用户密码 $password
		$password = 'AG0005889';
		//发送到的目标手机号码 $mobile
//		$mobile = '15201131591';
		//短信内容 $content
//		$content = "PHP完整测试信息";
		
		
		
		
		//发送短信（其他方法相同）
		$gateway = "https://dx.ipyy.net/sms.aspx?action=send&userid={$userid}&account={$account}&password={$password}&mobile={$mobile}&content={$content}&sendTime=";
		$result = file_get_contents($gateway);
		$xml = simplexml_load_string($result);
		// echo "返回状态为：".$xml->returnstatus."<br>";
		// echo "返回信息：".$xml->message."<br>";
		// echo "返回余额：".$xml->remainpoint."<br>";
		// echo "返回本次任务ID：".$xml->taskID."<br>";
		// echo "返回成功短信数：".$xml->successCounts."<br>";
		// echo "<br>";
		// echo "<br>";die;	
		return json_encode($xml);
//		$data = array
//		(
//				'action'=>'send',
//				'userid'=>'',
//				'account'=>$this->account,					//用户账号
//				'password'=>strtoupper(md5($this->password)),				//密码
//				'mobile'=>$mobile,					//号码
//				'content'=>'【餐饮会员】'.$content,	//内容
//				//'sendtime' =>$config['sendTime']	//定时发送时间
//		);
//      
//		$result= $this->curl_request($this->url,$data);	
//		return $result;
	}
	
	public function curl_request($url,$param='',$httpMethod='GET'){
		$ch=curl_init();
		if(stripos($url, "https://") !== FALSE){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		if($httpMethod=='GET'){
			if(empty($param)){
				curl_setopt($ch, CURLOPT_URL, $url );
			}else{
				if(stripos($url, "?") !== FALSE){
					curl_setopt($ch, CURLOPT_URL, $url . "&" . http_build_query($param));
				}else{
					curl_setopt($ch, CURLOPT_URL, $url . "?" . http_build_query($param));
				}
			}
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		}else {
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        }		
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
		$sContent = curl_exec($ch);
        $aStatus = curl_getinfo($ch);
        curl_close($ch);
        if (intval($aStatus["http_code"]) == 200) {
                return json_decode($sContent,true);
        } else {
                return FALSE;
        }
	}	
	
	public function getsms($url,$data)
	{
		
	}
}
?>