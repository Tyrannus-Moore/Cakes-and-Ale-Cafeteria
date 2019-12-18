<?php
/* *
 * 配置文件
 * 版本：1.0
 * 日期：2016-06-06
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */
return array(  
    'alipay_config'=>array 
    (
    
        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
        //合作身份者ID，签约账号，以2088开头由16位纯数字组成的字符串，查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
        'partner' => '2088821127795392',
        'seller_id'  => '3127906043@qq.com',
        //注意：和java生成的私钥是一样的
        //H商户的私钥,此处填写原始私钥去头去尾，RSA公私钥生成：https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.nBDxfy&treeId=58&articleId=103242&docType=1
        'private_key'	=> 'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCLKGY/JMY+DHB0sBUkPkiOLmUqygqGrvgYyx3IFyfDjuqd0+B3qAXm0SywYm7Hz5qEiudccWEwl79aDtvUuNfjRN7+BZxkJmkWNI+5RykxkidNb+7aYTaYrpx9G0L2iHLl3Xv6Gx3FxwoddgvwBl3i8nigLDTvINszFiaIvi28TJb5/G1PzDq2B01l//Qz7j/YT2l+Xp2LJlxczlOSDNAmZc6wbhW8MgzQ0N12cZ4nOrn1bCfACKiGvQGnb8nJBogpCZwpwwSroF97YsV9K6najSbhjX1FuV9cFB18MQO+DMM1d9yu33f28+xh/RlRKaH0tA997nW/N26tiYlyD5zlAgMBAAECggEAdDM+9fk6RPPUL8wdS09q/LcHKWzez0ppxyJM8xlKzgSYK0SSirhWfUAr5fm78cOMjqMCwPG4K4B7e7Muo/nZ5GBNKKb8ybA9ThqD7T3byxX1FbejJt3IXkRoThumlgComQOSk+1ytgUXkDRTkZFvcqPwUCe55/hysb0MXmEYBcRXmrswQZ02y5LEv3j+FtTdJ94/Zy6u8A0KDCDnb5jHDSsU3BOYhDvx5PVznHBMQQN9JD3UHEdaat1ZJJQ1e/jnOYN6hrnu2Aq59cYpCkDxUhfs/Sd9ZvvBprUlYmI9rrull95nJEP3hVDVPEjBX0WY1+SuV8i16u1P5zXQr7UkPQKBgQDsN78eSHXRvy6CSE3EJNzr2RYZki/wXVtdDnc6vBUMk5Lu4ZfvJCtiNywVPsdOqpBbB0DMBwJ6mJ3f8uLyy2OlzDrhcs3NkuqFE0HOLfSd9ykzt0gtUMKu4rHxYbeHtg6RyD8QLEWBaWdpKV1xH+lQzeAQuF7TnsGvHeSehjNVjwKBgQCWz8rc8uNZf/8gqjoj0nvlbjhUHKxkCAAB1zZPuD0Rt/wQNui6jsfAk9r/J9caaN/WwcVvDS2S39J/aMoS3OEqU3BjpeLM2fQEqjVu0cMiRaFufAZYP6fNZ67hcOt8R36PCzNPxRfUIG4BVBLmSFF61Gx/3KHkhvA7QKwxPNO0SwKBgBkk1fMjQ2dQMmMrzxtR0TJJqVCPfwrW5SLCp25ZFuR/0OQcn0+VSgjqVobsZ8q33SXTX9JX7KugEy4DbKTrgW/kjV/yGHYJbW0834RS3/bwmDIpEEXCkvaKquZGwIJxnptl9VCDZeKglaFcBdZEz1EKmQ8ukOl1vKgnRm6ZWl2BAoGASs3Yxs1nPY6CgrZMzlXqBHwuTnJGg/t/3WndU5+EExTaX0SNHXQS2o+8MZGWXVAlrwVAI/w6Xb7NT0sv6DlNKkxm09aFb6ywH9w5UrWS/53gG8hC8WTpm7XRBZuYAnYH7XXVxrxxPFO1nM0R7s1yNOXGx4kteAgCgrFqd6W/MI8CgYBerpn2iCxfp8XFTu+3ytjz2oDGFWcm9cVfpvfPuU9YoL2DItz6eLT3gGKq7p4URlk0n0bvqNsJOY2zbau7dEV0mFZCHsjmfXtcjXQ9hksXpfLfm+9XIRHG2NaJR/WejFTut46fd6Raa7qbCBHeqlz2/XITC5HZSkVyaeGp0GntfQ==',
        
        //生成的公钥（上传本地生成公钥，换取支付宝公钥），查看地址：https://openhome.alipay.com/platform/keyManage.htm?keyType=partner
        'alipay_public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB',
        
        //异步通知接口
        'service' => 'mobile.securitypay.pay',
        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
        
        //签名方式 不需修改
        'sign_type'    => strtoupper('RSA'),
        
        //字符编码格式 目前支持 gbk 或 utf-8
        'input_charset'=> strtolower('utf-8'),
        
        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        'cacert'    => getcwd().'/cacert.pem',
        
        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        'transport'    => 'http',
            
    )
);
?>
