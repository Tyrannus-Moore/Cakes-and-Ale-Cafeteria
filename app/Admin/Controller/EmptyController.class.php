<?php
namespace Admin\Controller;
use Common\Controller\CommonController;
class EmptyController extends CommonController{
    public function index(){
        $this->error('此操作无效');
    }
}