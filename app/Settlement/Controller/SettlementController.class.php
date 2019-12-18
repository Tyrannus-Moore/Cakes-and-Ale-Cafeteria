<?php
namespace Settlement\Controller;
use Common\Controller\AuthController;;
class SettlementController extends AuthController
{
    //商户结算
    public function settlementList()
    {
        $where = array();
        $search = I('search');
        if($search){
            if($search['due_deadline']){
                $startTime = substr($search['due_deadline'],0,10);
                $endTime = substr($search['due_deadline'],13);
                if($startTime && $endTime){
                    $where['a.creattime'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
            if($search['order_no']){
                $where['a.order_no'] = array('like','%'.trim($search['order_no']).'%');
            }
            if($search['shopname']){
                $where['b.ma_merchantname'] = array('like','%'.trim($search['shopname']).'%');
            }
            if($search['state']){
                $where['a.state'] = $search['state'];
            }
            if($search['statue']){
                $where['a.statue'] = $search['statue'];
            }
            if($search['type']){
                $where['a.type'] = $search['type'];
            }
        }
        //分页
        $count= M('finance')->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->where($where)->count();// 查询满足要求的总记录数
        $Page= new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $page= $Page->show();
        $list = M('finance')->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')
            ->field("a.id,a.order_no,a.state,a.money,a.type,a.statue,a.creattime as addtime,b.ma_merchantname as shopname")->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->order("a.creattime DESC")
            ->select();
        $lists = M('finance')->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')
            ->field("a.id,a.order_no,a.state,a.money,a.type,a.statue,a.creattime as addtime,b.ma_merchantname as shopname")->where($where)
            ->select();
        foreach ($lists as $key => $value) {
            if($value['statue'] == 1){
                $totalIncome+=$value['money'];
            }else{
                $totalExpend+=$value['money'];
            }
        }
        $totalReceipt = $totalIncome-$totalExpend;
        $this->assign('totalIncome',$totalIncome);
        $this->assign('totalExpend',$totalExpend);
        $this->assign('totalReceipt',$totalReceipt);
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('search',$search);
        $this->display();
    }

    //详情
    public function settlementDetail()
    {
        $id = I('id');
        $infos = M('finance')->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')->field("a.*,b.ma_merchantname as shopname")->where(array('a.id'=>$id))->find();
        $this->assign('infos',$infos);
        $this->display();
    }

    //导出
    public function settlementExport()
    {
        $where = array();
        //搜索
        $search = I('search');
        if($search){
            if($search['due_deadline']){
                $startTime = substr($search['due_deadline'],0,10);
                $endTime = substr($search['due_deadline'],13);
                if($startTime && $endTime){
                    $where['a.creattime'] = array(array('egt',strtotime($startTime)),array('elt',strtotime($endTime."23:59:59")));
                }
            }
            if($search['order_no']){
                $where['a.order_no'] = array('like','%'.trim($search['order_no']).'%');
            }
            if($search['shopname']){
                $where['b.ma_merchantname'] = array('like','%'.trim($search['shopname']).'%');
            }
            if($search['state']){
                $where['a.state'] = $search['state'];
            }
            if($search['statue']){
                $where['a.statue'] = $search['statue'];
            }
            if($search['type']){
                $where['a.type'] = $search['type'];
            }
        }
        //查询
        $list = M('finance')->join('AS a LEFT JOIN __MERCHANT__ AS b ON a.ma_id = b.ma_id')
            ->field("a.order_no,b.ma_merchantname as shopname,a.money,a.state,a.type,a.statue,a.creattime")->where($where)->order("a.creattime DESC")->select();
        //标题
        $headlist = array('订单编号', '商家名称', '订单金额', '结算类型','订单类别','流水','支付时间');

        $fileName = '商户结算';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . date('Ymd') . '.csv"');
        header('Cache-Control: max-age=0');
        //打开PHP文件句柄,php://output 表示直接输出到浏览器
        $fp = fopen('php://output', 'a');
        //输出Excel列名信息
        foreach ($headlist as $key => $value) {
            $headlist[$key] = iconv('utf-8', 'gbk', $value);
        }
        //将数据通过fputcsv写到文件句柄
        fputcsv($fp, $headlist);
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 10000;
        //逐行取出数据，不浪费内存
        //$count = count($list);
        foreach ($list as $k => $v) {
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($k % $limit == 0 && $k != 0) {
                ob_flush();
                flush();
            }
            $row = $list[$k];
            foreach ($row as $key => $value) {
                if ($key == 'order_no') {
                    $value = "\t" . $value;
                }
                if ($key == 'statue') {
                    if ($value == '1') {
                        $value = '收入';
                    }
                    if ($value == '2') {
                        $value = '支出';
                    }
                }
                if ($key == 'type') {
                    if ($value == '1') {
                        $value = '现金订单';
                    }
                    if ($value == '2') {
                        $value = '积分订单';
                    }
                }
                if ($key == 'state') {
                    switch ($value) {
                        case 1:
                            $value = '商户结算（订单金额）';
                            break;
                        case 2:
                            $value = '配送员结算（配送费）';
                            break;
                        case 3:
                            $value = '积分结算（运费）';
                            break;
                    }
                }
                if ($key == 'creattime') {
                    $value = date('Y年月d日', $value);
                }
                $row[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }
    }
}