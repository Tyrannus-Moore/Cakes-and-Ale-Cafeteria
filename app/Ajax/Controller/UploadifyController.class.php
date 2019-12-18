<?php
//功能：图片上传处理
namespace Ajax\Controller;
use Common\Controller\CommonController;
class UploadifyController extends CommonController
{
	/*
	*	@上传图片
	*	@参数1 	$title	标题
	*	@参数2 	$type	上传类型
	*	缩略图上传/image/image/1/500000/uploadify/picurl/undefined
	*/	
	public function uploads()
	{
		$title = I('title','');		//标题
		$type = I('type','');		//上传类型
		$path = I('dir','');		//上传的文件夹
		$num = I('num',0);				//上传个数
		$size = I('size',0);				//最大size大小
		$frame = I('frame','');		//iframe的ID
		$input = I('input','');	//父框架保存图片地址的input的id
		$desc=$type;													//类型描述
		$title = urldecode($title);		
		$units = array(3=>'G',2=>'M',1=>'KB',0=>'B');//单位字符,可类推添加更多字符.
		$size_str='';
		foreach($units as $i => $unit){
			if($i>0){
                $n = $size / pow(1024,$i) % pow(1024,$i);			   
			}else{
                $n = $size;
			}                
			if($n!=0){
                $str.=" $n{$unit} ";
				if(!$xi)
					$size_str;
			}			
		}
		$size_str;

		$this->assign('title',$title);
		$this->assign('type',$type);
		$this->assign('uptype','*.png;*.jpg;*.gif;*.jpeg;');
		$this->assign('path',$path);
		$this->assign('num',$num);
		$this->assign('size',$size);
		$this->assign('size_str',$size_str);
		$this->assign('frame',$frame);
		$this->assign('input',$input);
		$this->assign('desc',$desc);
		$this->assign('timestamp',time());

		$this->display();
	}
		public function qt_uploads()
	{
		$title = I('title','');		//标题
		$type = I('type','');		//上传类型
		$path = I('dir','');		//上传的文件夹
		$num = I('num',0);				//上传个数
		$size = I('size',0);				//最大size大小
		$frame = I('frame','');		//iframe的ID
		$input = I('input','');	//父框架保存图片地址的input的id
		$desc=$type;													//类型描述
		$title = urldecode($title);		
		$units = array(3=>'G',2=>'M',1=>'KB',0=>'B');//单位字符,可类推添加更多字符.
		$size_str='';
		foreach($units as $i => $unit){
			if($i>0){
                $n = $size / pow(1024,$i) % pow(1024,$i);			   
			}else{
                $n = $size;
			}                
			if($n!=0){
                $str.=" $n{$unit} ";
				if(!$xi)
					$size_str;
			}			
		}
		$size_str;

		$this->assign('title',$title);
		$this->assign('type',$type);
		$this->assign('uptype','*.png;*.jpg;*.gif;*.jpeg;');
		$this->assign('path',$path);
		$this->assign('num',$num);
		$this->assign('size',$size);
		$this->assign('size_str',$size_str);
		$this->assign('frame',$frame);
		$this->assign('input',$input);
		$this->assign('desc',$desc);
		$this->assign('timestamp',time());

		$this->display();
	}

	public function insert()
	{
		
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
		$upload->savePath  =     ''; // 设置附件上传（子）目录
		$upload->saveRule  =     'time';
		$info   =   $upload->upload();
		$picall_url="";
		if($info){
			$img_url = substr(C('UPLOAD_DIR'),1).$info[Filedata][savepath].$info[Filedata][savename];
			$water_img = substr(C('UPLOAD_DIR'),1).$info[Filedata][savepath].'water_'.$info[Filedata][savename];
			//添加水印
			$is_watermark = I('iswatermark');
			  if('true' === $is_watermark){
				$watermark = M('sysconfig')->where(array('aid'=>45))->getField('value');
				if($watermark){
					$image = new \Think\Image();
					//$image->open($img_url);
					//将图片裁剪为440x440并保存为corp.jpg
					//$image->crop(440, 440)->save('./crop.jpg');
					// 给裁剪后的图片添加图片水印（水印文件位于./logo.png），位置为右下角，保存为water.gif
					//$image->water($watermark)->save($info[Filedata][savename]);
					// 给原图添加水印并保存为water_o.gif（需要重新打开原图）
					$image->open('.'.$img_url)->water('.'.$watermark,\Think\Image::IMAGE_WATER_SOUTHEAST,80)->save('.'.$water_img);
					$img_url = $water_img;
				}
			}  
			
			$result = array(
					'code' => 0,
					'msg' => $img_url
				);
		}else{
			$result = array(
					'code' => 1,
					'msg' => $upload->getError()
				);
		}

		echo json_encode($result);exit;
	}

	public function delupload()
	{
		$action = I('action',''); 
		$filename = I('filename','');
		if($action && $filename)
		{
			$linkurl = ROOT_PATH.$filename;
			if(is_file($linkurl) && file_exists($linkurl))
			{
				unlink($linkurl);
				echo 1;
			}
			
		}

	}
}
