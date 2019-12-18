<?php
namespace Home\Model;
class UploadModel {

    public function creat_icon($url)
    {
        //替换https
        $url = str_replace("https://","http://",$url);
        $header = array("Connection: Keep-Alive", "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8", "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3", "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:29.0) Gecko/20100101 Firefox/29.0");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        $content = curl_exec($ch);
        $curlinfo = curl_getinfo($ch);
        //关闭连接
        curl_close($ch);
        if ($curlinfo['http_code'] == 200) {
            if ($curlinfo['content_type'] == 'image/jpeg') {
                $exf = '.jpg';
            } else if ($curlinfo['content_type'] == 'image/png') {
                $exf = '.png';
            } else if ($curlinfo['content_type'] == 'image/gif') {
                $exf = '.gif';
            }
            //存放图片的路径及图片名称  *****这里注意 你的文件夹是否有创建文件的权限 chomd -R 777 mywenjian
            mkdir('./data/upload/WxImage/'.date("Y-m-d"), 0777, true);
            $filename = './data/upload/WxImage/'.date("Y-m-d").'/'.uniqid() . $exf;
            file_put_contents($filename, $content);
        }
        $filename = substr($filename,1);
        return $filename;
    }
}
?>