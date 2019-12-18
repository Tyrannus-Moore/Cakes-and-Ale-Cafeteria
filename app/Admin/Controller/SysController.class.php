<?php
namespace Admin\Controller;
use Common\Controller\AuthController;
use Think\Db;
use Think\Auth;
use OT\Database;
use Org\Util\Stringnew;
class SysController extends AuthController{
	//站点设置显示
	public function sys(){
		$arr=list_file(APP_PATH.'Home/View/');
		$tpls=array();
		foreach($arr as $v){
			if($v['isDir'] && strtolower($v['filename']!='public')){
				$tpls[]=$v['filename'];
			}
		}
		$this->assign("templates",$tpls);
		$info = webInfo();
        $this->assign('info',$info);
		$this->display();
	}
	//保存站点设置
	public function runsys(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('Sys/sys'),0);
		}else{
			//网站LOGO
			$checkpic=I('checkpic');
			$oldcheckpic=I('oldcheckpic');
			$options=I('options');
			if ($checkpic!=$oldcheckpic){
				//获取图片上传后路径
				$upload = new \Think\Upload();// 实例化上传类
				$upload->maxSize   =     3145728 ;// 设置附件上传大小
				$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->rootPath  =     C('UPLOAD_DIR'); // 设置附件上传根目录
				$upload->savePath  =     ''; // 设置附件上传（子）目录
				$upload->saveRule  =     'time';
				$info   =   $upload->upload();
				if($info) {
					$img_url=substr(C('UPLOAD_DIR'),1).$info[file0][savepath].$info[file0][savename];//如果上传成功则完成路径拼接
					//写入数据库
					$data['uptime'] = time();
					$data['filesize'] = $info[file0][size];
					$data['path'] = $img_url;
					M('plug_files')->add($data);
				}else{
					$this->error($upload->getError(),U('Sys/sys'),0);//否则就是上传错误，显示错误原因
				}
				$cfg_medias_dir = $img_url;
			}else{
				//原有图片
				$cfg_medias_dir = I('oldcheckpic');
			}
			$checkpic1=I('checkpic1');
			$oldcheckpic1=I('oldcheckpic1');
			if ($checkpic1!=$oldcheckpic1){
				//获取图片上传后路径
				$upload = new \Think\Upload();// 实例化上传类
				$upload->maxSize   =     3145728 ;// 设置附件上传大小
				$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->rootPath  =     C('UPLOAD_DIR2'); // 设置附件上传根目录
				$upload->savePath  =     ''; // 设置附件上传（子）目录
				$upload->saveRule  =     'time';
				$info1   =   $upload->upload();
				if($info1) {
					//$img_url1=substr(C('UPLOAD_DIR'),1).$info1[file1][savepath].$info1[file1][savename];//如果上传成功则完成路径拼接
					$img_url1=substr(C('UPLOAD_DIR2'),1).$info1[file1][savepath].$info1[file1][savename];//如果上传成功则完成路径拼接
					
				}else{
					$this->error($upload->getError(),U('Sys/sys'),0);//否则就是上传错误，显示错误原因
				}
				$cfg_watermark = $img_url1;
			}else{
				//原有图片
				$cfg_watermark = I('oldcheckpic1');
				
			}
			M('sysconfig')->where(array('varname'=>'cfg_medias_dir'))->setField('value',$cfg_medias_dir);
			M('sysconfig')->where(array('varname'=>'cfg_watermark'))->setField('value',$cfg_watermark);
			$cfg_webname = I('cfg_webname');
			M('sysconfig')->where(array('varname'=>'cfg_webname'))->setField('value',$cfg_webname);
			$cfg_basehost = I('cfg_basehost');
			M('sysconfig')->where(array('varname'=>'cfg_basehost'))->setField('value',$cfg_basehost);
			$cfg_beian = I('cfg_beian');
			M('sysconfig')->where(array('varname'=>'cfg_beian'))->setField('value',$cfg_beian);
			$cfg_statistics = I('cfg_statistics');
			M('sysconfig')->where(array('varname'=>'cfg_statistics'))->setField('value',$cfg_statistics);
			$cfg_powerby = I('cfg_powerby');
			M('sysconfig')->where(array('varname'=>'cfg_powerby'))->setField('value',$cfg_powerby);
			$cfg_company = I('cfg_company');
			M('sysconfig')->where(array('varname'=>'cfg_company'))->setField('value',$cfg_company);
			$cfg_address = I('cfg_address');
			M('sysconfig')->where(array('varname'=>'cfg_address'))->setField('value',$cfg_address);
			$cfg_tel = I('cfg_tel');
			M('sysconfig')->where(array('varname'=>'cfg_tel'))->setField('value',$cfg_tel);
			$cfg_title = I('cfg_title');
			M('sysconfig')->where(array('varname'=>'cfg_title'))->setField('value',$cfg_title);
			$cfg_keywords = I('cfg_keywords');
			M('sysconfig')->where(array('varname'=>'cfg_keywords'))->setField('value',$cfg_keywords);
			$cfg_description = I('cfg_description');
			M('sysconfig')->where(array('varname'=>'cfg_description'))->setField('value',$cfg_description);
            $cfg_video = trim(I('cfg_video'));
            M('sysconfig')->where(array('varname'=>'cfg_video'))->setField('value',$cfg_video);
			//保存F方法
			$list = M('sysconfig')->select();
			$arr = array();
			foreach ($list as $key => $value) {
				$arr[$value['varname']] = $value['value'];
			}
			F('webInfo',$arr);
			$this->success('站点设置保存成功',U('Sys/sys'),1);
		}
	}


	//发送邮件设置
	public function emailsys(){
		$sys=M('options')->where(array('option_name'=>'email_options'))->find();
		if(empty($sys)){
			$data['option_name']='email_options';
			$data['option_value']='';
			$data['autoload']=1;
			M('options')->add($data);
		}
		$sys=json_decode($sys['option_value'],true);
		$this->assign('sys',$sys)->display();
	}

	//保存邮箱设置
	public function runemail(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('emailsys'),0);
		}else{
			$rst=M('options')->where(array('option_name'=>'email_options'))->setField('option_value',json_encode(I('options')));
			if($rst!==false){
				F("email_options",null);
				$this->success('邮箱设置保存成功',U('emailsys'),1);
			}else{
				$this->error('提交参数不正确',U('emailsys'),0);
			}
		}
	}

	//帐号激活设置
	public function activesys(){
		$sys=M('options')->where(array('option_name'=>'active_options'))->find();
		
		if(empty($sys)){
			$data['option_name']='active_options';
			$data['option_value']='';
			$data['autoload']=1;
			M('options')->add($data);
		}
		$sys=json_decode($sys['option_value'],true);
		$this->assign('sys',$sys)->display();
	}

	//保存帐号激活设置
	public function runactive(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('Sys/activesys'),0);
		}else{
			//htmlspecialchars_decode(
			$options=I('options');
			$options['email_tpl']=htmlspecialchars_decode($options['email_tpl']);
			$rst=M('options')->where(array('option_name'=>'active_options'))->setField('option_value',json_encode($options));
			if($rst!==false){
				F("active_options",null);
				$this->success('帐号激活设置保存成功',U('Sys/activesys'),1);
			}else{
				$this->error('提交参数不正确',U('Sys/activesys'),0);
			}
		}
	}

 


    public function database($type = null){
        if(empty($type)){
            $type='export';
        }
        $title='';
        $list=array();
        switch ($type) {
            /* 数据还原 */
            case 'import':
                //列出备份文件列表
                $path = realpath(C('DB_PATH'));
                $flag = \FilesystemIterator::KEY_AS_FILENAME;
                $glob = new \FilesystemIterator($path,  $flag);

                $list = array();
                foreach ($glob as $name => $file) {
                    if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                        $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                        $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                        $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                        $part = $name[6];

                        if(isset($list["{$date} {$time}"])){
                            $info = $list["{$date} {$time}"];
                            $info['part'] = max($info['part'], $part);
                            $info['size'] = $info['size'] + $file->getSize();
                        } else {
                            $info['part'] = $part;
                            $info['size'] = $file->getSize();
                        }
                        $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                        $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                        $info['time']     = strtotime("{$date} {$time}");

                        $list["{$date} {$time}"] = $info;
                    }
                }
                $title = '数据还原';
                break;

            /* 数据备份 */
            case 'export':
                $Db    = Db::getInstance();
                $list  = $Db->query('SHOW TABLE STATUS FROM '.C('DB_NAME'));
                $list  = array_map('array_change_key_case', $list);
				//过滤非本项目前缀的表
 				foreach($list as $k=>$v){
					if(stripos($v['name'],strtolower(C('DB_PREFIX')))!==0){
						unset($list[$k]);
					}
				}
                $title = '数据备份';
                break;

            default:
                $this->error('参数错误！');
        }
        //渲染模板
        $this->assign('meta_title', $title);
        $this->assign('data_list', $list);
        $this->display($type);
    }

    public function import(){
        $path = realpath(C('DB_PATH'));
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,$flag);

        $list = array();
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];

                if(isset($list["{$date} {$time}"])){
                    $info = $list["{$date} {$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time']     = strtotime("{$date} {$time}");

                $list["{$date} {$time}"] = $info;
            }
        }
        //渲染模板
        $this->assign('data_list', $list);
        $this->display();
    }



    /**
     * 优化表
     * @param  String $tables 表名
     */
    public function optimize($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if($list){
                    $this->success("数据表优化完成！");
                } else {
                    $this->error("数据表优化出错请重试！");
                }
            } else {
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'优化完成！");
                } else {
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
            $this->error("请指定要优化的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("REPAIR TABLE `{$tables}`");

                if($list){
                    $this->success("数据表修复完成！");
                } else {
                    $this->error("数据表修复出错请重试！");
                }
            } else {
                $list = $Db->query("REPAIR TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'修复完成！",1,1);
                } else {
                    $this->error("数据表'{$tables}'修复出错请重试！");
                }
            }
        } else {
            $this->error("请指定要修复的表！");
        }
    }
    /**
     * 备份单表
     * @param  String $table 不含前缀表名
     */
    public function exportsql($table = null){
        if($table){
            if(stripos($table,C('DB_PREFIX'))==0){
                //含前缀的表,去除表前缀
                $table=str_replace(C('DB_PREFIX'),"",$table);
            }
            if (!db_is_valid_table_name($table)) {
                $this->error("不存在表" . ' ' . $table);
            }
            force_download_content(date('Ymd') . '_' . C('DB_PREFIX') . $table . '.sql', db_get_insert_sqls($table));
        }else{
            $this->error('未指定需备份的表');
        }
    }
    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DB_PATH')) . DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->error('备份文件删除失败，请检查权限！',U('Sys/import'),0);
            } else {
                $this->success('备份文件删除成功！',U('Sys/import'),1);
            }
        } else {
            $this->error('参数错误！',U('Sys/import'),0);
        }
    }
    /**
     * 还原数据库
     */
    public function restore($time = 0, $part = null, $start = null){
        //读取备份配置
        $config = array(
            'path'     => realpath(C('DB_PATH')) . DIRECTORY_SEPARATOR,
            'part'     => C('DB_PART'),
            'compress' => C('DB_COMPRESS'),
            'level'    => C('DB_LEVEL'),
        );
        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DB_PATH')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //缓存备份列表
                $this->restore(0,1,0);
            } else {
                $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            $db = new Database($list[$part],$config);
            $start = $db->import($start);
            if(false === $start){
                $this->error('还原数据出错！');
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
                    //$data = array('part' => $part, 'start' => 0);
                    $this->restore(0,$part,0);
                } else {
                    session('backup_list', null);
                    $this->success('还原完成！',U('Sys/import'),1);
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if($start[1]){
                    $this->restore(0,$part, $start[0]);
                } else {
                    $data['gz'] = 1;
                    $this->restore(0,$part, $start[0]);
                }
            }
        } else {
            $this->error('参数错误！');
        }
    }
    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     */
    public function export($tables = null, $id = null, $start = null){
        if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
            //读取备份配置
            $config = array(
                'path'     => realpath(C('DB_PATH')) . DIRECTORY_SEPARATOR,
                'part'     => C('DB_PART'),
                'compress' => C('DB_COMPRESS'),
                'level'    => C('DB_LEVEL'),
            );
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！',0,0);
            } else {
                //创建锁文件
                file_put_contents($lock, NOW_TIME);
            }

            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！',0,0);
            session('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
            session('backup_file', $file);

            //缓存要备份的表
            session('backup_tables', $tables);

            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                $this->error('初始化失败，备份文件创建失败！',0,0);
            }
        } elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new Database(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->error('备份出错！',0,0);
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
                    $this->success('备份完成！', '', array('tab' => $tab));
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    $this->success('备份完成！',1,1);
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }

        } else { //出错
            $this->error('参数错误！');
        }
    }







 

 

	public function excel_import(){
		$this->display();
	}

	public function excel_export(){
      $Db    = Db::getInstance();
		$list  = $Db->query('SHOW TABLE STATUS  FROM '.C('DB_NAME'));
        $list  = array_map('array_change_key_case', $list);
		//过滤非本项目前缀的表
		foreach($list as $k=>$v){
			if(stripos($v['name'],strtolower(C('DB_PREFIX')))!==0){
				unset($list[$k]);
			}
		}
        $this->assign('data_list', $list);
		$this->display();
	}

	/*
     * 表格导入
	 * @author rainfer <81818832@qq.com>
     */
	public function excel_runimport(){
		import("Org.Util.PHPExcel");
		$PHPExcel=new \PHPExcel();
		import("Org.Util.PHPExcel.Reader.Excel5");

		if (! empty ( $_FILES ['file_stu'] ['name'] )){
			$tmp_file = $_FILES ['file_stu'] ['tmp_name'];
			$file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
			$file_type = $file_types [count ( $file_types ) - 1];
			/*判别是不是.xls文件，判别是不是excel文件*/
			if (strtolower ( $file_type ) != "xls"){
				$this->error ( '不是Excel文件，重新上传',U('excel_import'),0);
			}
			/*设置上传路径*/
			$savePath = './public/excel/';
			/*以时间来命名上传的文件*/
			$str = time ( 'Ymdhis' );
			$file_name = $str . "." . $file_type;

			if (! copy ( $tmp_file, $savePath . $file_name )){
				$this->error ('上传失败',U('excel_import'),0);
			}

			$res = $this->read ( $savePath . $file_name );
			if (!$res){
				$this->error ('数据处理失败',U('excel_import'),0);
			}
			//spl_autoload_register ( array ('Think', 'autoload' ) );
			foreach ( $res as $k => $v ){
				if ($k != 1){
					$data ['news_title'] = $v[1];
					$data ['news_titleshort'] = $v[2];
					$data ['news_columnid'] = $v[3];
					$data ['news_columnviceid'] = $v[4];
					$data ['news_key'] = $v[5];
					$data ['news_tag'] = $v[6];
					$data ['news_auto'] = $v[7];
					$data ['news_source'] = $v[8];
					$data ['news_content'] = $v[9];
					$data ['news_scontent'] = $v[10];
					$data ['news_hits'] = $v[11];
					$data ['news_img'] = $v[12];
					$data ['news_time'] = $v[13];
					$data ['news_flag'] = $v[14];
					$data ['news_zaddress'] = $v[15];
					$data ['news_back'] = $v[16];
					$data ['news_open'] = $v[17];
					$data ['news_lvtype'] = $v[18];

					$result = M ('news')->add ($data);
					if (!$result){
						$this->error ('导入数据库失败',U('excel_import'),0);
					}
				}
			}
			$this->success ('导入数据库成功',U('excel_import'),1);
		}
	}

	private function read($filename,$encode='utf-8'){
		$objReader = \PHPExcel_IOFactory::createReader(Excel5);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			}
		}
		return $excelData;
	}

	/*
     * 数据导出功能
	 * @author rainfer <81818832@qq.com>
     */
	public function excel_runexport($table){
        export2excel($table);
	}

	public function clear(){
		clear_cache();
		$this->success ('清理缓存成功',1,1);
	}
  
	public function profile(){
        $admin=array();
        if(session('aid')){
            $rs=M('admin');
            $join1 = "".C('DB_PREFIX').'auth_group_access as b on a.admin_id =b.uid';
            $join2= "".C('DB_PREFIX').'auth_group as c on b.group_id = c.id';
            $admin=$rs->alias("a")->join($join1)->join($join2)->where(array('a.admin_id'=>session('aid')))->find();
 
        }
        $this->assign('admin', $admin);
		$this->display();
	}
	
    public function avatar(){
        $imgurl=I('post.imgurl');
        //去'/'
        $imgurl=str_replace('/','',$imgurl);
        $admin=M('admin')->where(array('admin_id'=>session('aid')))->find();
        $old_img=$admin['admin_avatar'];
        $data['admin_avatar']=$imgurl;
        $rst=M('admin')->where(array('admin_id'=>session('aid')))->save($data);
        if($rst!==false){
            session('admin_avatar',$imgurl);
			$url=substr(C('UPLOAD_DIR'),1).'avatar/'.$imgurl;
		 
			$this->success ('头像更新成功',U('profile'),1);
        }else{
            $this->error ('头像更新失败',U('profile'),0);
        }
    }
    
}