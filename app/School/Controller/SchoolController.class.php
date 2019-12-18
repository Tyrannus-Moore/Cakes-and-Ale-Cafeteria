<?php
// +----------------------------------------------------------------------
// |学校管理
// +----------------------------------------------------------------------
namespace School\Controller;
use Common\Controller\AuthController;
use Org\Util\Stringnew;
class SchoolController extends AuthController {

	/*
     *学校列表
     */
	public function index(){
		
		//搜索
		$search = I('search');
		if($search['name']){
			$where['name'] = array('like',"%".trim($search['name'])."%");
		}
		if($search['address']){
			$where['address'] = array('like',"%".trim($search['address'])."%");
		}
		$where['is_del'] = 1;
		
		$school_db = M('school');
		$count= $school_db->where($where)->count();// 查询满足要求的总记录数
		$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show= $Page->show();
		$list = $school_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('addtime desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('search',$search);
		$this->display();
	}
    public function schoolAdd()
    {
    	if(IS_AJAX){
    		$data['name'] = I('name');
    		$data['address'] = I('address');
    		$data['addtime'] = time();
			//添加学校信息
	        $result = M('school')->add($data);
			if($result!==false){
				$this->success('添加成功',U('index'),1);
			}else{
				$this->error('添加失败',U('index'),0);
			}
    	}
    	else
    	{
	    	$this->display();
    	}
    }                                                  
   	// 修改显示页面
    public function schoolEdit(){
        $school_id = I('school_id');
		$school_db = M('school');
		$infos = $school_db->where(array('school_id'=>$school_id))->find();
        $infos['status'] = 1;

        $this->ajaxReturn($infos,'json');
    }
    // 执行修改操作
    public function schoolRunEdit(){
        $school_id = I('school_id');
		$school_db = M('school');
		$data['name'] = I('name');
		$data['address'] = I('address');
		$data['addtime'] = time();
		//添加学校信息
        $result = $school_db->where(array('school_id'=>$school_id))->save($data);
		if($result!==false){
			$this->success('修改成功',U('index'),1);
		}else{
			$this->error('修改失败',U('index'),0);
		}
    	
    }
	
	/*
     * 学校删除
     */
	public function schoolDel(){
		$p = I('p');
		$school_id = I('school_id');
		//判断是否存在商家或者用户
		$is_shop = M('merchant')->where(array('school_id'=>$school_id,'delete'=>1))->find();
		$is_member = M('member_list')->where(array('school_id'=>$school_id))->find();
		if($is_shop || $is_member){
			$this->error('此学校存已在对应商家与用户，无法删除！',U('index', array('p' => $p)),0);
		}
		$rst = M('school')->where(array('school_id'=>$school_id))->setField('is_del',2);
		if($rst!==false){
			//删除下面所属学校
			M('faculty')->where(array('school_id'=>$school_id))->delete();
            $this->success('学校删除成功',U('index', array('p' => $p)),1);
        }else{
            $this->error('学校删除失败',U('index', array('p' => $p)),0);
        }
	}
	//院系列表
	public function faculty(){
		
		//搜索
		$search = I('search');
		if($search['faculty_name']){
			$where['faculty_name'] = array('like',"%".trim($search['faculty_name'])."%");
		}
		$where['school_id'] = I('school_id');
		
		$faculty_db = M('faculty');
		$count= $faculty_db->where($where)->count();// 查询满足要求的总记录数
		$Page= new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show= $Page->show();
		$list = $faculty_db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('addtime desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->assign('search',$search);
		$this->assign('school_id',I('school_id'));
		$this->display();
	}
	public function facultyAdd()
    {
    	if(IS_AJAX){
    		$data['school_id'] = I('school_id');
    		$data['faculty_name'] = I('faculty_name');
    		$data['num'] = I('num');
    		$data['addtime'] = time();
			//添加院系信息
	        $result = M('faculty')->add($data);
			if($result!==false){
				$this->success('添加成功',U('faculty',array('school_id'=>I('school_id'))),1);
			}else{
				$this->error('添加失败',U('faculty',array('school_id'=>I('school_id'))),0);
			}
    	}
    	else
    	{
	    	$this->display();
    	}
    }                                                  
   	// 修改显示页面
    public function facultyEdit(){
        $faculty_id = I('faculty_id');
		$faculty_db = M('faculty');
		$infos = $faculty_db->where(array('faculty_id'=>$faculty_id))->find();
        $infos['status'] = 1;

        $this->ajaxReturn($infos,'json');
    }
    // 执行修改操作
    public function facultyRunEdit(){
        $faculty_id = I('faculty_id');
		$faculty_db = M('faculty');
		$data['faculty_name'] = I('faculty_name');
		$data['num'] = I('num');
		$data['addtime'] = time();
		//添加学校信息
        $result = $faculty_db->where(array('faculty_id'=>$faculty_id))->save($data);
		if($result!==false){
			$this->success('修改成功',U('faculty',array('school_id'=>I('school_id'))),1);
		}else{
			$this->error('修改失败',U('faculty',array('school_id'=>I('school_id'))),0);
		}
    	
    }
	
	/*
     * 学校删除
     */
	public function facultyDel(){
		$p = I('p');
		$faculty_id = I('faculty_id');
		$rst = M('faculty')->where(array('faculty_id'=>$faculty_id))->delete();
		if($rst!==false){
            $this->success('删除成功',U('faculty', array('p' => $p,'school_id'=>I('school_id'))),1);
        }else{
            $this->error('删除失败',U('faculty', array('p' => $p,'school_id'=>I('school_id'))),0);
        }
	}
}