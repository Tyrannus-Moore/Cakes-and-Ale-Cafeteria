<?php
namespace Api\Model;
use Think\Model;
class UploadModel {
    /**
     * 单图片上传
     * 调用方式
     * 二进制流的方式
     * $UploadModel = new UploadModel();
     * $account_list = $UploadModel->Dan();
     * @return string
     */
    function dan(){
        //上传图片
        //参数初始化
        date_default_timezone_set('prc');
        $date = date("Y-m-d",time());
        $url=str_replace('\\', '/', ROOT_PATH);
        //生成日期目录
        $addr =  "./data/upload/".$date."/";
        //如果目录不存在则创建
        !is_dir($url .$addr) && mkdir($url."/".$addr,0777);
        $filelist=array();

        if($_FILES["icon"]){
            if(($_FILES["icon"]['size'] / 1024) > 4*1024){
                $filelist[1]['error'] = 1;
                $filelist[1]['address'] = "";
                $filelist[1]['name'] = "";
                $this->make_json_error("上传失败",100);
            }
            //生成图片名称
            $picName = time().rand(100, 999).strrchr($_FILES["icon"]['name'],'.');

            //生成图片绝对路径
            $images=$addr.$picName;
            if (move_uploaded_file($_FILES["icon"]['tmp_name'], $images)) {
                $filelist[1]['error'] = 0;
                $filelist[1]['address'] = $images;
                $filelist[1]['name'] = $_FILES["icon"]['name'];
                // $img = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $images;
                // $images = "/data/upload/".$date."/".$picName;
                $images = substr($images,1);
                $img = $images;
            }
        }
        return $img;
    }
    /**
        *多图片上传
        *$UploadModel = new UploadModel();
        *$account_list = $UploadModel->duopupian();
    */
    function duopupian(){
        //上传图片
        //参数初始化
        date_default_timezone_set('prc');
        $date = date("Y-m-d",time());
        $url=str_replace('\\', '/', ROOT_PATH);
        //生成日期目录
        $addr =  "./data/upload/".$date."/";
        //如果目录不存在则创建
        !is_dir($url .$addr) && mkdir($url."/".$addr,0777);
        $filelist=array();
        $img = array();
        foreach($_FILES as $k=>$v){
            if($_FILES["$k"]){
                if(($_FILES["$k"]['size'] / 1024) > 4*1024){
                    $filelist[1]['error'] = 1;
                    $filelist[1]['address'] = "";
                    $filelist[1]['name'] = "";
                    $this->make_json_error("上传图片失败",1);
                }
                //生成图片名称
                $picName = time().rand(100, 999).strrchr($_FILES["$k"]['name'],'.');

                //生成图片绝对路径
                $images=$addr.$picName;
                if (move_uploaded_file($_FILES["$k"]['tmp_name'], $images)) {
                    $filelist[1]['error'] = 0;
                    $filelist[1]['address'] = $images;
                    $filelist[1]['name'] = $_FILES["$k"]['name'];
                    //$img[] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $images;
                    $images = substr($images,1);
                    // $images = "/data/upload/".$date."/".$picName;
                    if($k == 'car_vehicle_license'){
                        $img[] = $images;
                    }
                    elseif($k == 'car_driving'){
                        $img[] = $images;
                    }
                    elseif($k == 'car_proxy'){
                         $img[] = $images;
                    }
                    elseif($k == 'car_insurance'){
                        $img[] = $images;
                    }
                    elseif($k == 'car_annual_inspection'){
                        $img[] = $images;
                    }
                    else{
                        $img[] = $images;                        
                    }
                }
            }
        }
        return $img;
    }
    
    /**
        *  @desc 根据两点间的经纬度计算距离
        *  @param float $lat 纬度值
        *  @param float $lng 经度值
        *   $this->getdistance($location[1],$location[0],$locat[1],$local[0]);
    */
    function getdistance($lng1,$lat1,$lng2,$lat2){
        //将角度转为狐度
        $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=(2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2))))*6378.137;
        // $num = strpos($s,'.')+2;
        // $s = floatval(substr($s,$num));
        // return $s;
        return round($s,2);
    }
    //生成订单号
    function getorderSn(){
        $orderSn = date("Ymd").rand(1000,9999);
        $res = M("account_log")->where("orderSn = '".$orderSn."'")->find();
        if($res){
            $this->getorderSn();
        }
        else{
            return $orderSn;
        }
    }
    //计算某年某月有多少天
    function days_in_month($month, $year) {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }
    //根据生日获取星座   $birthday 时间戳类型
    public function getconstellation($birthday){
        $birthday = strtotime($birthday);
        $month = date("m",$birthday);
        $day = date("d",$birthday);
        $day   = intval($day);
        $month = intval($month);
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) return false;
        $signs = array(
                array('20'=>'水瓶座'),
                array('19'=>'双鱼座'),
                array('21'=>'白羊座'),
                array('20'=>'金牛座'),
                array('21'=>'双子座'),
                array('22'=>'巨蟹座'),
                array('23'=>'狮子座'),
                array('23'=>'处女座'),
                array('23'=>'天秤座'),
                array('24'=>'天蝎座'),
                array('22'=>'射手座'),
                array('22'=>'摩羯座')
        );
        list($start, $name) = each($signs[$month-1]);
        if ($day < $start)
            list($start, $name) = each($signs[($month-2 < 0) ? 11 : $month-2]);
        return $name;
    }
    /*
        *@desc 根据出生日期计算年龄
        *@param float $birthday 出生日期  年-月-日
    */
    public function age($birthday){
        $age = strtotime($birthday);
        if(empty($birthday)){
            $age = 0;
        }
        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
        $now = strtotime("now");
        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
        $age  = $y2 - $y1;
        if((int)($m2.$d2) < (int)($m1.$d1))
        $age  -= 1;
        if($age <= 0){
            $age = 0;
        }
        return $age;
    }
    //根据积分计算用户等级
    public function rand($integtal){
        $member_lvl_db = M("member_lvl");
        $integtal = empty($integtal) ? 0 : $integtal;
        $info = $member_lvl_db->where("member_lvl_num > ".$integtal)->order("member_lvl_num asc")->find();

        if(!empty($info)){
            $lvl['level'] = $info['member_lvl_level'] - 1;
            $inte = $info['member_lvl_num'] - $integtal;
            $lvl['lvl_num'] = "距离升级还需".$inte."积分";
            return $lvl;
        }
        else{
            $info = $member_lvl_db->order("member_lvl_num desc")->find();
            $lvl['level'] = $info['member_lvl_level'];
            $lvl['lvl_num'] = "您的等级已达到最高";
            return $lvl;
        }
    }
    /**
     * 取得给定日期所在周的开始日期和结束日期
     * @param string $gdate 日期，默认为当天，格式：YYYY-MM-DD
     * @param int $weekStart 一周以星期一还是星期天开始，0为星期天，1为星期一
     * @return array 数组array( "开始日期 ",  "结束日期");
     */
    function getAWeekTimeSlot($gdate = '', $weekStart = 0) {
     if (! $gdate){
     $gdate = date ( "Y-m-d" );
     }
     $w = date ( "w", strtotime ( $gdate ) ); //取得一周的第几天,星期天开始0-6
     $dn = $w ? $w - $weekStart : 6; //要减去的天数
     $st = date ( "Y-m-d", strtotime ( "$gdate  - " . $dn . "  days " ) );
     $en = date ( "Y-m-d", strtotime ( "$st  +6  days " ) );
     return array ($st, $en ); //返回开始和结束日期
    }
}
?>