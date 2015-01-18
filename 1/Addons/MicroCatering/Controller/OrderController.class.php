<?php

namespace Addons\MicroCatering\Controller;

use Addons\MicroCatering\Controller\BaseController;

class OrderController extends BaseController{	
	function _initialize() {
		parent::_initialize ();
		
		// 子导航
		$action = strtolower ( _ACTION );
		$controller = strtolower ( _CONTROLLER );
		
		$res ['title'] = '点餐订单';
		$res ['url'] = addons_url (  'MicroCatering://Order/lists'  );
		$res ['class'] = ($action == 'lists' || $action == 'listsadd' || $action == 'listsedit')  ? 'cur' : '';
		$nav [] = $res;		
			
		$res ['title'] = '外卖订单';
		$res ['url'] = addons_url ( 'MicroCatering://Order/lists1' );
		$res ['class'] = $action == 'lists1' ? 'cur' : '';
		$nav [] = $res;
		
		$res ['title'] = '订餐订单';
		$res ['url'] = addons_url ( 'MicroCatering://Order/lists2' );
		$res ['class'] = $action == 'lists2' ? 'cur' : '';
		$nav [] = $res;
		
		$res ['title'] = '电话预约管理';
		$res ['url'] = addons_url ( 'MicroCatering://DiscountType/lists' );
		$res ['class'] = $controller == 'discounttype' ? 'cur' : '';
		$nav [] = $res;		
		
		$this->assign ( 'sub_nav', $nav );
	
	}


	protected $model;
	public function __construct() {
		parent::__construct ();
		$this->model = M ( 'Model' )->getByName ( 'ml_microcatering_order' );
		$this->model || $this->error ( '模型不存在！' );
		$this->assign ( 'model', $this->model );
	}
		
	/**********************桌台管理******************************/
	
	//查看菜品
	public function lookcp(){
		$ctid = I ( 'get.ctid', 0, 'intval' );
		$ddid = I ( 'get.id', 0, 'intval' );
		$token = get_token();
		
		$this->assign ( 'ctid', $ctid);
			
		$fx = C ( 'DB_PREFIX' );
		
		//查询订单信息		
		$querysql = "select b.truename,b.tel,b.address,a.* from ".$fx ."ml_microcatering_order a left join ".$fx ."ml_microcatering_users b on a.contactid=b.id where a.set_id=".$ctid." and a.token='".$token."' and a.id='".$ddid."' ORDER BY a.ctime desc";
		$myorderdatas = M ()->query($querysql);
		$newdata = array();
		foreach($myorderdatas as $cp){
			//还原数据			
			$cpdataids = unserialize($cp["dishes_count_datas"]);
			$cp["dishes_count_datas"] = $cpdataids;
			$newdata[]  = $cp;
		}
		$this->assign ( 'ddcpdatas', $newdata);
		
		
		$this->display ( T ( 'Addons://MicroCatering@default/Order/lookcp' ) );	
	}
	/**
	 * 显示指定模型列表数据
	 */
	public function lists($dctype=0) {
		$token = get_token();
		$ctid = I("get.ctid",0, 'intval' );
		
		$page = I ( 'p', 1, 'intval' ); // 默认显示第一页数据		                                
		// 解析列表规则
		$fields = array ();
		$grids = preg_split ( '/[;\r\n]+/s', $this->model ['list_grid'] );
		foreach ( $grids as &$value ) {
			// 字段:标题:链接
			$val = explode ( ':', $value );
			// 支持多个字段显示
			$field = explode ( ',', $val [0] );
			$value = array (
					'field' => $field,
					'title' => $val [1] 
			);
			if (isset ( $val [2] )) {
				// 链接信息
				$value ['href'] = $val [2];
				// 搜索链接信息中的字段信息
				preg_replace_callback ( '/\[([a-z_]+)\]/', function ($match) use(&$fields) {
					$fields [] = $match [1];
				}, $value ['href'] );
			}
			if (strpos ( $val [1], '|' )) {
				// 显示格式定义
				list ( $value ['title'], $value ['format'] ) = explode ( '|', $val [1] );
			}
			foreach ( $field as $val ) {
				$array = explode ( '|', $val );
				$fields [] = $array [0];
			}
		}
		// 过滤重复字段信息
		$fields = array_unique ( $fields );
		// 关键字搜索
		$map ['token'] = $token;
		$key = $this->model ['search_key'] ? $this->model ['search_key'] : 'title';
		if (isset ( $_REQUEST [$key] )) {
			$map [$key] = array (
					'like',
					'%' . htmlspecialchars ( $_REQUEST [$key] ) . '%' 
			);
			unset ( $_REQUEST [$key] );
		}
		// 条件搜索
		foreach ( $_REQUEST as $name => $val ) {
			if (in_array ( $name, $fields )) {
				$map [$name] = $val;
			}
		}
		$row = empty ( $this->model ['list_row'] ) ? 20 : $this->model ['list_row'];
		
		//添加选择餐厅数据
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token))->order ( 'id asc' )->select ();
		$this->assign ( 'list_ctdata', $ctdata );
		//餐厅切换url
		$sel_ct_url = U('lists?ctid={id}');
		$this->assign ( 'sel_ct_url', $sel_ct_url );
		
		
		//默认只取第一个餐厅的数据
		if($ctid == 0){
			$map ['set_id'] = $ctdata[0]["id"];
			$ctid = $ctdata[0]["id"];
		}else{
			$map ['set_id'] = $ctid;			
		}
		$this->assign ( 'ctid', $ctid );
		
		$map["diningtypes"] = $dctype;
		
		// 读取模型数据列表		
		empty ( $fields ) || in_array ( 'id', $fields ) || array_push ( $fields, 'id' );
		$name = parse_name ( get_table_name ( $this->model ['id'] ), true );
		$data = M ( $name )->field ( empty ( $fields ) ? true : $fields )->where ( $map )->order ( 'id DESC' )->page ( $page, $row )->select ();		
		/* 查询记录总数 */
		$count = M ( $name )->where ( $map )->count ();
		
		// 分页
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		
		$this->assign ( 'list_grids', $grids );
		$this->assign ( 'list_data', $data );
		$this->meta_title = $this->model ['title'] . '列表';
		
		
		$this->display ( T ( 'Addons://MicroCatering@default/Order/lists' ) );		
	}
	
	public function lists1() {
		$this->lists(1);
	}
	
	public function lists2() {
		$this->lists(2);
	}
		
	//餐厅复制
	public function ctfz(){
		$from = I("post.from",0, 'intval' );
		$to = I("post.to",0, 'intval' );
		
		$token = get_token();
		//复制桌台
		$Orderdata_by_from = M ("ml_microcatering_Order")->where (array("token"=>$token,"setid"=>$from))->order ( 'id asc' )->select();
		//不能自己复制自己
		if($from != $to){
			$newOrder = M ("ml_microcatering_Order");
			foreach($Orderdata_by_from as $Order){			
				 $Order["setid"] = $to;			
				 unset($Order["id"]);
				 $newarray = $Order;				
				 $newOrder->add($newarray);
			}
		}
		echo "复制成功!";
	}
	
	public function add() {	
		$ctid = I("get.ctid",0, 'intval' );
		if (IS_POST) {
			// 自动补充token
			$_POST ['token'] = get_token ();
			$_POST ['setid'] =  $ctid ;
			
			$Model = D ( parse_name ( get_table_name ( $this->model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $this->model ['id'] );
			if ($Model->create () && $micsetid = $Model->add ()) {					
				$this->success ( '添加' . $this->model ['title'] . '成功！', U ( 'lists?ctid='.$ctid ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			
			$micset_fields = get_model_attribute ( $this->model ['id'] );
			$this->assign ( 'fields', $micset_fields );
			
			$this->assign ( 'ctid', $ctid );
			
			$this->meta_title = '新增' . $this->model ['title'];
			$this->display ('add');			
		}
	}
	
	public function edit() {
		// 获取模型信息
		$id = I ( 'id', 0, 'intval' );			
		if (IS_POST) {
			$Model = D ( parse_name ( get_table_name ( $this->model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $this->model ['id'] );
			if ($Model->create () && $Model->save ()) {	
				$this->success ( '保存' . $this->model ['title'] . '成功！', U ( 'lists?ctid='.$ctid ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $this->model ['id'] );			
			// 获取数据
			$data = M ( get_table_name ( $this->model ['id'] ) )->find ( $id );
			$data || $this->error ( '数据不存在！' );
						
			$this->assign ( 'fields', $fields );
			$this->assign ( 'data', $data );
			$this->meta_title = '编辑' . $this->model ['title'];
			$this->display ( 'edit');
		}
	}
	
	public function del() {
		$ids = I ( 'id', 0 );
		if (empty ( $ids )) {
			$ids = array_unique ( ( array ) I ( 'ids', 0 ) );
		}
		if (empty ( $ids )) {
			$this->error ( '请选择要操作的数据!' );
		}
		
		$Model = M ( get_table_name ( $this->model ['id'] ) );
		$map = array (
				'id' => array (
						'in',
						$ids 
				) 
		);
		$map ['token'] = get_token ();		
		
		if ($Model->where ( $map )->delete ()) {
			$this->success ( '删除成功' );
		} else {
			$this->error ( '删除失败！' );
		}
	}
}
