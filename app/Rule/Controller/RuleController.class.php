<?php
// +----------------------------------------------------------------------
// |角色管理
// +----------------------------------------------------------------------
namespace Rule\Controller;
use Common\Controller\AuthController;
use Think\Db;
use Think\Auth;
use OT\Database;
use Org\Util\Stringnew;
class RuleController extends AuthController{
	//管理员列表
	public function admin_list(){
		$admin=M('admin');
		$val=I('val');
		$auth = new Auth();
		$this->assign('testval',$val);
		$map=array();
		if($val){
			$map['admin_username']= array('like',"%".$val."%");
		}
		
		$count= $admin->where($map)->count();// 查询满足要求的总记录数
		$Page= new \Think\Page($count,C('DB_PAGENUM'));// 实例化分页类 传入总记录数和每页显示的记录数
	
		foreach($map as $key=>$val) {
			$Page->parameter[$key]=urlencode($val);
		}
		$show= $Page->show();// 分页显示输出
	
		$admin_list=$admin->where($map)->order('admin_id')->limit($Page->firstRow.','.$Page->listRows)->select();
	
		foreach ($admin_list as $k=>$v){
			$group = $auth->getGroups($v['admin_id']);
			$admin_list[$k]['group'] = $group[0]['title'];
		}
		$this->assign('admin_list',$admin_list);
		$this->assign('page',$show);
		$this->display();
	}
	
	public function admin_add(){
		$auth_group=M('auth_group')->select();
		$this->assign('auth_group',$auth_group);
		$this->display();
	}
	//添加管理员
	public function admin_runadd(){
		$admin=M('admin');
		$admin_access=M('auth_group_access');
		$check_user=$admin->where(array('admin_username'=>I('admin_username')))->find();
		if ($check_user){
			$this->error('用户已存在，请重新输入用户名',U('admin_list'),0);
		}
		$admin_pwd_salt=Stringnew::randString(10);
		$sldata=array(
				'admin_username'=>I('admin_username'),
				'admin_pwd_salt' => $admin_pwd_salt,
				'admin_pwd'=>encrypt_password(I('admin_pwd'),$admin_pwd_salt),
				'admin_email'=>I('admin_email'),
				'admin_tel'=>I('admin_tel'),
				'admin_open'=>I('admin_open'),
				'admin_realname'=>I('admin_realname'),
				'admin_ip'=>get_client_ip(),
				'admin_addtime'=>time(),
				'admin_changepwd'=>time(),
		);
		$result=$admin->add($sldata);
		$accdata=array(
				'uid'=>$result,
				'group_id'=>I('group_id'),
		);
		$admin_access->add($accdata);
		$this->success('管理员添加成功',U('admin_list'),1);
	}
	//编辑管理员试图
	public function admin_edit(){
		$auth_group=M('auth_group')->select();
		$admin_list=M('admin')->where(array('admin_id'=>I('admin_id')))->find();
		$auth_group_access=M('auth_group_access')->where(array('uid'=>$admin_list['admin_id']))->getField('group_id');
		$this->assign('admin_list',$admin_list);
		$this->assign('auth_group',$auth_group);
		$this->assign('auth_group_access',$auth_group_access);
		$this->display();
	}
	//编辑管理员操作
	public function admin_runedit(){
		$admin_list=M('admin');
		$admin_pwd=I('admin_pwd');
		$group_id=I('group_id');
		$admindata['admin_id']=I('admin_id');
		if ($admin_pwd){
			$admin_pwd_salt=Stringnew::randString(10);
			$admindata['admin_pwd_salt']=$admin_pwd_salt;
			$admindata['admin_pwd']=encrypt_password(I('admin_pwd'),$admin_pwd_salt);
			$admindata['admin_changepwd']=time();
		}
		$admindata['admin_email']=I('admin_email');
		$admindata['admin_tel']=I('admin_tel');
		$admindata['admin_realname']=I('admin_realname');
		$admindata['admin_open']=I('admin_open',0,'intval');
		$admin_list->save($admindata);
		if($group_id){
			$rst=M('auth_group_access')->where(array('uid'=>I('admin_id')))->find();
			if($rst){
				//修改
				$rst=M('auth_group_access')->where(array('uid'=>I('admin_id')))->setField('group_id',$group_id);
			}else{
				//增加
				$data['uid']=I('admin_id');
				$data['group_id']=$group_id;
				$rst=M('auth_group_access')->add($data);
			}
		}
		if($rst!==false){
			$this->success('管理员修改成功',U('admin_list'),1);
		}else{
			$this->error('管理员修改失败',U('admin_list'),0);
		}
	}
	//删除管理员
	public function admin_del(){
		$admin_id=I('admin_id');
		if (empty($admin_id)){
			$this->error('用户ID不存在',U('admin_list'),0);
		}
		M('admin')->where(array('admin_id'=>I('admin_id')))->delete();
		$rst=M('auth_group_access')->where(array('uid'=>I('admin_id')))->delete();
		if($rst!==false){
			$this->success('管理员删除成功',U('admin_list'),1);
		}else{
			$this->error('管理员删除失败',U('admin_list'),0);
		}
	}
	
	public function admin_state(){
		$id=I('x');
		if (empty($id)){
			$this->error('用户ID不存在',U('admin_list'),0);
		}
		$status=M('admin')->where(array('admin_id'=>$id))->getField('admin_open');//判断当前状态情况
		if($status==1){
			$statedata = array('admin_open'=>0);
			M('admin')->where(array('admin_id'=>$id))->setField($statedata);
			$this->success('状态禁止',1,1);
		}else{
			$statedata = array('admin_open'=>1);
			M('admin')->where(array('admin_id'=>$id))->setField($statedata);
			$this->success('状态开启',1,1);
		}
	
	}
	
	//用户组管理
	public function admin_group_list(){
		$auth_group=M('auth_group')->select();
		$this->assign('auth_group',$auth_group);
		$this->display();
	}
	//用户组管理
	public function admin_group_add(){
		$this->display();
	}
	public function admin_group_runadd(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('admin_group_list'),0);
		}else{
			$sldata=array(
					'title'=>I('title'),
					'status'=>I('status'),
					'addtime'=>time(),
			);
			M('auth_group')->add($sldata);
			$this->success('用户组添加成功',U('admin_group_list'),1);
		}
	}
	
	public function admin_group_del(){
		$id = I('id');
		$result = M('auth_group_access')->where(array('group_id'=>$id))->find();
		if($result){
			$this->error('用户组已有管理员，删除失败',U('admin_group_list'),0);
		}else{
			$rst=M('auth_group')->where(array('id'=>I('id')))->delete();
			if($rst!==false){
				$this->success('用户组删除成功',U('admin_group_list'),1);
			}else{
				$this->error('用户组删除失败',U('admin_group_list'),0);
			}
		}
		
	}
	
	public function admin_group_edit(){
		$group=M('auth_group')->where(array('id'=>I('id')))->find();
		$this->assign('group',$group);
		$this->display();
	}
	
	public function admin_group_runedit(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('admin_group_list'),0);
		}else{
			$sldata=array(
					'id'=>I('id'),
					'title'=>I('title'),
					'status'=>I('status'),
			);
			M('auth_group')->save($sldata);
			$this->success('用户组修改成功',U('admin_group_list'),1);
		}
	}
	
	public function admin_group_state(){
		$id=I('x');
		$status=M('auth_group')->where(array('id'=>$id))->getField('status');//判断当前状态情况
		if($status==1){
			$statedata = array('status'=>0);
			$auth_group=M('auth_group')->where(array('id'=>$id))->setField($statedata);
			$this->success('状态禁止',1,1);
		}else{
			$statedata = array('status'=>1);
			$auth_group=M('auth_group')->where(array('id'=>$id))->setField($statedata);
			$this->success('状态开启',1,1);
		}
	}
	//四重权限配置
	public function admin_group_access(){
		$admin_group=M('auth_group')->where(array('id'=>I('id')))->find();
		$m = M('auth_rule');
		$data = $m->field('id,name,title')->where('pid=0')->select();
		foreach ($data as $k=>$v){
			$data[$k]['sub'] = $m->field('id,name,title')->where('pid='.$v['id'])->select();
			foreach ($data[$k]['sub'] as $kk=>$vv){
				$data[$k]['sub'][$kk]['sub'] = $m->field('id,name,title')->where('pid='.$vv['id'])->select();
				foreach ($data[$k]['sub'][$kk]['sub'] as $kkk=>$vvv){
					$data[$k]['sub'][$kk]['sub'][$kkk]['sub'] = $m->field('id,name,title')->where('pid='.$vvv['id'])->select();
				}
			}
		}
		$this->assign('admin_group',$admin_group);	// 顶级
		$this->assign('datab',$data);	// 顶级
		$this->display();
	}
	//四重权限配置操作
	public function admin_group_runaccess(){
		$m = M('auth_group');
		$new_rules = I('new_rules');
		$imp_rules = implode(',', $new_rules).',';
		$sldata=array(
				'id'=>I('id'),
				'rules'=>$imp_rules,
		);
		if($m->save($sldata)!==false){
			clear_cache();
			$this->success('权限配置成功',U('admin_group_list'),1);
		}else{
			$this->error('权限配置失败',U('admin_group_list'),0);
		}
	}
	//权限列表
	public function admin_rule_list(){
		$nav = new \Org\Util\Leftnav;
		$admin_rule=M('auth_rule')->order('sort')->select();
		$arr = $nav::rule($admin_rule);
		$this->assign('admin_rule',$arr);//权限列表
		$this->display();
	}
	//权限添加
	public function admin_rule_runadd(){
		if(!IS_AJAX){
//			echo "Eee";die;
			$this->error('提交方式不正确',U('admin_rule_list'),0);
		}else{
			$admin_rule=M('auth_rule');
			$pid=$admin_rule->where(array('id'=>I('pid')))->field('level')->find();
			$level=$pid['level']+1;
			//是否存在控制器/方法
			$arr=explode('/',I('name'));
			//检测name是否有效
			if($level==1){
				//是否存在控制器
				$class = $arr[0].'\\Controller\\' . $arr[1] . 'Controller';
				if (!class_exists($class)) {
					$this->error('不存在 '.$arr[1].' 的控制器',U('admin_rule_list'),0);
				}
			}elseif($level==2){
				//不检测
			}else{
				
				
				if(count($arr)==3){
					$class = $arr[0].'\\Controller\\' . $arr[1] . 'Controller';
					if (!class_exists($class)) {
						$this->error('不存在 '.$arr[1].' 的控制器',U('admin_rule_list'),0);
					}
					if (!method_exists($class, $arr[2])) {
						$this->error('控制器'.$arr[1].'不存在方法'.$arr[2],U('admin_rule_list'),0);
					}
				}else{
					$this->error('提交名称不规范',U('admin_rule_list'),0);
				}
			}
			$sldata=array(
					'name'=>I('name'),
					'title'=>I('title'),
					'status'=>I('status'),
					'sort'=>I('sort'),
					'addtime'=>time(),
					'pid'=>I('pid'),
					'css'=>I('css'),
					'level'=>$level,
			);
			$admin_rule->add($sldata);
			clear_cache();
			$this->success('权限添加成功',U('admin_rule_list'),1);
		}
	}
	
	public function admin_rule_state(){
		$id=I('x');
		$statusone=M('auth_rule')->where(array('id'=>$id))->getField('status');//判断当前状态情况
		if($statusone==1){
			$statedata = array('status'=>0);
			$auth_group=M('auth_rule')->where(array('id'=>$id))->setField($statedata);
			clear_cache();
			$this->success('状态禁止',1,1);
		}else{
			$statedata = array('status'=>1);
			$auth_group=M('auth_rule')->where(array('id'=>$id))->setField($statedata);
			clear_cache();
			$this->success('状态开启',1,1);
		}
	
	}
	//排序
	public function admin_rule_order(){
		if (!IS_AJAX){
			$this->error('提交方式不正确',U('admin_rule_list'),0);
		}else{
			$auth_rule=M('auth_rule');
			foreach ($_POST as $id => $sort){
				$auth_rule->where(array('id' => $id ))->setField('sort' , $sort);
			}
			clear_cache();
			$this->success('排序更新成功',U('admin_rule_list'),1);
		}
	}
	//修改权限
	public function admin_rule_edit(){
		//全部规则
		$nav = new \Org\Util\Leftnav;
		$admin_rule_all=M('auth_rule')->order('sort')->select();
		$arr = $nav::rule($admin_rule_all);
		$this->assign('admin_rule',$arr);
		//待编辑规则
		$admin_rule=M('auth_rule')->where(array('id'=>I('id')))->find();
		$this->assign('rule',$admin_rule);
		$this->display();
	}
	public function admin_rule_copy(){
		//全部规则
		$nav = new \Org\Util\Leftnav;
		$admin_rule_all=M('auth_rule')->order('sort')->select();
		$arr = $nav::rule($admin_rule_all);
		$this->assign('admin_rule',$arr);
		//待编辑规则
		$admin_rule=M('auth_rule')->where(array('id'=>I('id')))->find();
		$this->assign('rule',$admin_rule);
		$this->display();
	}
	//修改权限操作
	public function admin_rule_runedit(){
		if(!IS_AJAX){
			$this->error('提交方式不正确',U('admin_rule_list'),0);
		}else{
			$admin_rule=M('auth_rule');
			$pid=$admin_rule->where(array('id'=>I('pid')))->field('level')->find();
			$level=$pid['level']+1;
			$sldata=array(
					'id'=>I('id',1,'intval'),
					'name'=>I('name'),
					'title'=>I('title'),
					'status'=>I('status'),
					'pid'=>I('pid',0,'intval'),
					'css'=>I('css'),
					'sort'=>I('sort'),
					'level'=>$level,
			);
			$rst=$admin_rule->save($sldata);
			if($rst!==false){
				clear_cache();
				$this->success('权限修改成功',U('admin_rule_list'),1);
			}else{
				$this->error('权限修改失败',U('admin_rule_list'),0);
			}
		}
	}
	//删除权限
	public function admin_rule_del(){
		//TODO 自动删除子权限
		$rst=M('auth_rule')->where(array('id'=>I('id')))->delete();
		if($rst!==false){
			clear_cache();
			$this->success('权限删除成功',U('admin_rule_list'),1);
		}else{
			$this->error('权限删除失败',U('admin_rule_list'),0);
		}
	}
}