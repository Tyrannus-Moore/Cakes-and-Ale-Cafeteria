<?php
/**
*调取
*
*$orderBody = "test商品";
*$tade_no = "abc_" . time();
*$total_fee = 1;
*$WxPayHelper = new WxqiyePayHelper();
*$response = $WxPayHelper->getPrePayOrder($amout,$check_name,$desc,$openid,$partner_trade_no,$re_user_name,$spbill_create_ip);
*
*/
namespace Small\Model;
use Think\Model;
class qiyeModel{
  /*
   * 配置参数
  */
	/*var $config = array(
        'mch_appid' => "wx1738a49e49ccf770",    //公众账号appid corpid
        'mch_id' => "1387984802"  //商户号
    ); */
	var $config = array(
        'mch_appid' => "wxed2e1ebe19b2c854",    //公众账号appid corpid
        'mch_id' => "1514006241",   //商户号
        'api_key' => "heoxilrub0ion54ldtnhhlej1vjvzs5l",  // 支付key
        'cert_pem'=>'\app\Account\Model\cert\apiclient_cert.pem',   // 支付证书
        'key_pem'=> '\app\Account\Model\cert\apiclient_key.pem'     // 支付密钥证书
    );
    // $mch_appid=$appid;//公众账号appid
    // $mchid='10000005';//商户号
    // $nonce_str='qyzf'.rand(100000, 999999);//随机数
    // $partner_trade_no='xx'.time().rand(10000, 99999);//商户订单号
    // $openid=$openids;//用户唯一标识,上一步授权中获取
    // $check_name='NO_CHECK';//校验用户姓名选项，NO_CHECK：不校验真实姓名， FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账），OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
    // $re_user_name='测试';//用户姓名
    // $amount=100;//企业金额，这里是以分为单位（必须大于100分）
    // $desc='测试数据呀！！！';//描述
    // $spbill_create_ip='192.168.0.1';//请求ip


public function getPrePayOrder($amount,$check_name,$desc,$openid,$partner_trade_no){
        $nonce_str = $this->getRandChar(32);
        //$dataArr=array();
        $dataArr['amount']=$amount;
        $dataArr['check_name']=$check_name;
        $dataArr['desc']=$desc;
        $dataArr['mch_appid']=$this->config['mch_appid'];
        $dataArr['mchid']=$this->config['mch_id'];
        $dataArr['nonce_str']=$nonce_str;//随机数;
        $dataArr['openid']=$openid;
        $dataArr['partner_trade_no']=$partner_trade_no;
        //$dataArr['re_user_name']=$re_user_name;
        $dataArr['spbill_create_ip']=$this->get_client_ip();
        //生成签名
        $s=$this->getSign($dataArr);//getSign($dataArr);见结尾
    		$dataArr["sign"] = $s;
            //echo "-----<br/>签名：".$sign."<br/>*****";//die;
    		$data = $this->arrayToXml($dataArr);
        //4、发出企业付款请求
		    $data1 = $this->enterprise('https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',$data,$second=60);

//    		dump(getcwd().$this->config['key_pem']);
    		//将微信返回的结果xml转成数组
        return $this->xmlstr_to_array($data1);
}


        //更具返回值，做具体处理。

//------------------------getSign()方法如下----------------------

  /**
   * 查询订单信息
   * @param $out_trade_no   订单号
   * @return array
   */
  public function orderQuery($out_trade_no){
    $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo";

    $data['appid'] = $this->config["mch_appid"];
    $data['mch_id'] = $this->config['mch_id'];
    $data['nonce_str'] = $this->getRandChar(32);
    $data['out_trade_no'] = $out_trade_no;

    //获取签名数据
    $s = $this->getSign($data, false);
    $data["sign"] = $s;
    $xml = $this->arrayToXml($data);
    //$response = $this->postXmlCurl($xml, $url);
	$response = $this->enterprise($url,$xml,$second=60);

    /* if( !$response ){
      return false;
    }
    $result = $this->arrayToXml( $response );
    if( !empty($result['result_code']) && !empty($result['err_code']) ){
       $result['err_msg'] = $this->error_code( $result['err_code'] );
    } */
	return $this->xmlstr_to_array($response);
    //return $result;
  }

    /**
     * 作用：生成签名
     */
    function getSign($Obj)
    {
          //var_dump($Obj);//die;
          foreach ($Obj as $k => $v)
          {
            $Parameters[$k] = $v;
          }
          //签名步骤一：按字典序排序参数
          ksort($Parameters);
          $String = $this->formatBizQueryParaMap($Parameters, false);//方法如下
          //echo '【string1】'.$String.'</br>';
          //签名步骤二：在string后加入KEY
          // $String = $String."&key=3F08D09FF8FA6C53A634F1E6B214C316";
		      $String = $String."&key=".$this->config['api_key'];

          //echo "【string2】".$String."</br>";
          //签名步骤三：MD5加密
          $String = md5($String);
          //echo "【string3】 ".$String."</br>";
          //签名步骤四：所有字符转为大写
          $result_ = strtoupper($String);
          //echo "【result】 ".$result_."</br>";
          return $result_;
    }


    /**
     * 作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        //var_dump($paraMap);//die;
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
        if($urlencode)
        {
        $v = urlencode($v);
        }
        //$buff .= strtolower($k) . "=" . $v . "&";
        $buff .= $k . "=" . $v . "&";
        }
        if (strlen($buff) > 0)
        {
        $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        //var_dump($reqPar);//die;
        return $reqPar;
    }
	/*
        获取当前服务器的IP
    */
    function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
        $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
        $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
        $cip = getenv("HTTP_CLIENT_IP");
        } else {
        $cip = "unknown";
        }
        return $cip;
    }

	function enterprise($url, $vars, $second=30,$aHeader=array())
{
	$ch = curl_init();
	//超时时间
	curl_setopt($ch,CURLOPT_TIMEOUT,$second);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	//这里设置代理，如果有的话
	//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
	//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

	//以下两种方式需选择一种

	//第一种方法，cert 与 key 分别属于两个.pem文件
	//默认格式为PEM，可以注释
	curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
	curl_setopt($ch,CURLOPT_SSLCERT,getcwd().$this->config['cert_pem']);

	//默认格式为PEM，可以注释
	curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
	curl_setopt($ch,CURLOPT_SSLKEY,getcwd().$this->config['key_pem']);

	if( count($aHeader) >= 1 ){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
	}

	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
	$data = curl_exec($ch);
	//var_dump($data);
	if($data){
		curl_close($ch);
		return $data;
	}
	else {
		$error = curl_errno($ch);
		echo "call faild, errorCode:$error\n";
		curl_close($ch);
		return false;
	}

}
//获取指定长度的随机字符串
    function getRandChar($length){
       $str = null;
       $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
       $max = strlen($strPol)-1;

       for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
       }
       return $str;
    }
	//数组转xml
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
             if (is_numeric($val))
             {
                $xml.="<".$key.">".$val."</".$key.">";

             }
             else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }
	/**
    xml转成数组
    */
    function xmlstr_to_array($xmlstr) {
      $doc = new \DOMDocument();
      $doc->loadXML($xmlstr);
      return $this->domnode_to_array($doc->documentElement);
    }
	function domnode_to_array($node) {
      $output = array();
      switch ($node->nodeType) {
       case XML_CDATA_SECTION_NODE:
       case XML_TEXT_NODE:
        $output = trim($node->textContent);
       break;
       case XML_ELEMENT_NODE:
        for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
         $child = $node->childNodes->item($i);
         $v = $this->domnode_to_array($child);
         if(isset($child->tagName)) {
           $t = $child->tagName;
           if(!isset($output[$t])) {
            $output[$t] = array();
           }
           $output[$t][] = $v;
         }
         elseif($v) {
          $output = (string) $v;
         }
        }
        if(is_array($output)) {
         if($node->attributes->length) {
          $a = array();
          foreach($node->attributes as $attrName => $attrNode) {
           $a[$attrName] = (string) $attrNode->value;
          }
          $output['@attributes'] = $a;
         }
         foreach ($output as $t => $v) {
          if(is_array($v) && count($v)==1 && $t!='@attributes') {
           $output[$t] = $v[0];
          }
         }
        }
       break;
      }
      return $output;
    }
}
?>