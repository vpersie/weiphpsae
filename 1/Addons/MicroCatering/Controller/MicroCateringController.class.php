<?php

namespace Addons\MicroCatering\Controller;

use Addons\MicroCatering\Controller\BaseController;

class MicroCateringController extends BaseController{	
	function _initialize() {
		parent::_initialize ();
		
		// 子导航
		$controller = strtolower ( _CONTROLLER );
		$action = strtolower ( _ACTION );
		
		$res ['title'] = '餐厅设置';
		$res ['url'] = addons_url ( 'MicroCatering://MicroCatering/lists' );
		$res ['class'] = ($action == 'lists' || $action == 'listsadd' || $action == 'listsedit')  ? 'cur' : '';
		$nav [] = $res;		
			
		$res ['title'] = '菜品管理';
		$res ['url'] = addons_url ( 'MicroCatering://Dishes/lists' );
		$res ['class'] = $controller == 'dishes' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '菜系分类';
		$res ['url'] = addons_url ( 'MicroCatering://DishesType/lists' );
		$res ['class'] = $controller == 'dishestype' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '优惠分类';
		$res ['url'] = addons_url ( 'MicroCatering://DiscountType/lists' );
		$res ['class'] = $controller == 'discounttype' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '餐位管理';
		$res ['url'] = addons_url ( 'MicroCatering://TableManage/lists' );
		$res ['class'] = $controller == 'tablemanage' ? 'current' : '';
		$nav [] = $res;
		
		$this->assign ( 'sub_nav', $nav );
	
	}
	
	protected $model;
	public function __construct() {
		parent::__construct ();
		$this->model = M ( 'Model' )->getByName ( 'ml_microcatering_set' );
		$this->model || $this->error ( '模型不存在！' );
		$this->assign ( 'model', $this->model );
	}
	
	/***********************************手机显示*******************************************/
	
	//显示
	public function show(){
		$id = I ( 'id', 0, 'intval' );
		$token = get_token();
		
		$ctid=$id;
		$this->assign ( 'ctid', $ctid);
		
		$map ['token'] = $token;
		$map ['setid'] = $id;				
		//查询菜系信息
		$data = M ( "ml_microcatering_dishes_type" )->field ('id,name,setid,dishesdatas')->where ( $map )->order ( 'id DESC' )->select ();
		$newdata = array();
		foreach($data as $cx){
			//还原数据			
			$cpdataids = unserialize($cx["dishesdatas"]);
			$cx["dishesdatas"] = $cpdataids;
			
			$cx["cpinfo"] = array();
			if(isset($cpdataids)){
				//查询菜品信息
				$querysql = "select id,`name`,dishes_num,price,icon,introduction,paixu,(select left(name,2) from ".C ( 'DB_PREFIX' )."ml_microcatering_discount_type where id =youhuiid ) as yhname from ".C ( 'DB_PREFIX' )."ml_microcatering_dishes where token='".$token."' and id in (".implode(",",$cpdataids).") order by paixu desc";				
				$cx["cpinfo"] = M ()->query($querysql);
			}
			
			$newdata[] = $cx;			
		}
		$this->assign ( 'selType', $newdata);
		
		
		//查询优惠信息
		$yhdata = M ( "ml_microcatering_discount_type" )->field ('id,name,setid,dishesdatas')->where ( $map )->order ( 'id DESC' )->select ();
		$newyhdata = array();
		foreach($yhdata as $cx){
			//还原数据			
			$cpdataids = unserialize($cx["dishesdatas"]);
			$cx["dishesdatas"] = $cpdataids;
			
			$cx["cpinfo"] = array();
			if(isset($cpdataids)){
				//查询菜品信息			
				$cx["cpinfo"] = M ("ml_microcatering_dishes")->field("id,`name`,dishes_num,price,icon,introduction,paixu")
					->where (array("token"=>$token,"id"=>array("in",implode(",",$cpdataids))))
					->order ( 'paixu desc' )
					->select();		
			}
			
			$newyhdata[] = $cx;			
		}
		$this->assign ( 'yhdatas', $newyhdata);
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$id))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
			
		$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/index' ) );		
	}
	
	public function mylove(){
		echo "敬请期待！";
		exit();
	}
	
	/*******************************订单***********************************/
	
	//我的订单
	public function myorder(){
		$id = I ( 'ctid', 0, 'intval' );
		$token = get_token();
		$openid = get_openid();
		
		$ctid=$id;
		$this->assign ( 'ctid', $ctid);
		
		$map ['token'] = $token;
		$map ['set_id'] = $id;
		$map ['openid'] = $openid;
		
		$fx = C ( 'DB_PREFIX' );
		
		//查询我的订单
		$querysql = "select b.truename,b.tel,a.* from ".$fx ."ml_microcatering_order a left join ".$fx ."ml_microcatering_users b on a.contactid=b.id where a.set_id=".$id." and a.openid='".$openid."' and a.token='".$token."' and a.statekz !='6' ORDER BY a.ctime desc";
		$myorderdatas = M ()->query($querysql);
		$this->assign ( 'myorderdatas', $myorderdatas);
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$id))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
		
		$myorderid = M ( 'Model' )->getByName ( 'ml_microcatering_order' );
		$this->assign('myorderid',$myorderid ['id']);
		
		//是否成功
		$success = I('get.success',0,'intval');
		$this->assign ( 'success', $success);
	
		$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/myorder' ) );	
	}
	
	//查看菜品信息
	public function cpinfo(){
		$ctid = I ( 'get.ctid', 0, 'intval' );
		$id = I ( 'get.cpid', 0, 'intval' );
		$token = get_token();
		$openid = get_openid();
				
		$ctid=$ctid;
		$this->assign ( 'ctid', $ctid);
		
		$map1 ['token'] = $token;
		$map1 ['setid'] = $ctid;
		$map1 ['openid'] = $openid;
		
		$map ['token'] = $token;
		$map ['id'] = $id;	

		$sumprice = 0;
		$sumcount = 0;
		//查询菜品
		$myordertemp = M ( "ml_microcatering_order_temp" )->where ( $map1 )->order ( 'id DESC' )->select ();
		$myshopdatas = array();
		
		foreach($myordertemp as $order_temp){
			if(isset($myshopdatas[$order_temp["cpid"]])){
				$cpdata = $myshopdatas[$order_temp["cpid"]];
				$cpdata["num"] = ($cpdata["num"]+1);
				$sumprice= ($sumprice + intval($cpdata["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata;
			}else{
				$cpdata1 = M ( "ml_microcatering_dishes" )->where (array("token"=>$token,"id"=>$order_temp["cpid"]))->find();
				$cpdata1["num"] = 1;
				$sumprice= ($sumprice + intval($cpdata1["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata1;
			}			
		}	
				
		//统计价格
		$this->assign ( 'sumprice', $sumprice);
		$this->assign ( 'sumcount', $sumcount);			
		
		//菜品信息
		$cpdata = M ( "ml_microcatering_dishes" )->where ( $map )->order ( 'id DESC' )->find();
		if(isset($cpdata)){
			$cpdata["yhprice"] = $cpdata["price"];
		}
		$this->assign ( 'cpdata', $cpdata);
		
		//查询主要的优惠信息		
		$data = M ( "ml_microcatering_discount_type" )->field ('id,name,setid,dishesdatas,paixu')->where (array("token"=>$token,"setid"=>$ctid,"ismain"=>1))->order ( 'paixu DESC' )->limit("1")->select ();
		$newdata = array();
		foreach($data as $cx){
			//还原数据			
			$cpdataids = unserialize($cx["dishesdatas"]);
			$cx["dishesdatas"] = $cpdataids;
			
			$cx["cpinfo"] = array();
			if(isset($cpdataids)){
				//查询菜品信息			
				$cx["cpinfo"] = M ("ml_microcatering_dishes")->field("id,`name`,dishes_num,price,icon,introduction,paixu")
					->where (array("token"=>$token,"id"=>array("in",implode(",",$cpdataids))))
					->order ( 'paixu desc' )
					->select();		
			}
			
			$newdata = $cx;			
		}
		$this->assign ( 'yhlists', $newdata);
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$ctid))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
	
		$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/cpinfo' ) );	
	}
	
	//查看菜品信息
	public function cppicinfo(){
		$ctid = I ( 'get.ctid', 0, 'intval' );
		$id = I ( 'get.cpid', 0, 'intval' );
		$token = get_token();
		$openid = get_openid();
				
		$ctid=$ctid;
		$this->assign ( 'ctid', $ctid);
		
		$map1 ['token'] = $token;
		$map1 ['setid'] = $ctid;
		$map1 ['openid'] = $openid;
		
		$map ['token'] = $token;
		$map ['id'] = $id;

		$sumprice = 0;
		$sumcount = 0;
		//查询菜品
		$myordertemp = M ( "ml_microcatering_order_temp" )->where ( $map1 )->order ( 'id DESC' )->select ();
		$myshopdatas = array();
		foreach($myordertemp as $order_temp){
			if(isset($myshopdatas[$order_temp["cpid"]])){
				$cpdata = $myshopdatas[$order_temp["cpid"]];
				$cpdata["num"] = ($cpdata["num"]+1);
				$sumprice= ($sumprice + intval($cpdata["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata;
			}else{
				$cpdata1 = M ( "ml_microcatering_dishes" )->where (array("token"=>$token,"id"=>$order_temp["cpid"]))->find();
				$cpdata1["num"] = 1;
				$sumprice= ($sumprice + intval($cpdata1["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata1;
			}			
		}	
				
		//统计价格
		$this->assign ( 'sumprice', $sumprice);
		$this->assign ( 'sumcount', $sumcount);		
		
		//菜品信息
		$cpdata = M ( "ml_microcatering_dishes" )->where ( $map )->order ( 'id DESC' )->find();
		if(isset($cpdata)){
			$cpdata["yhprice"] = $cpdata["price"];
		}
		$this->assign ( 'cpdata', $cpdata);
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$ctid))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
	
		$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/cppicinfo' ) );	
	}
	
	
	//[postajax]添加到购物车
	public function add_cart(){
		$token = get_token();
		$ctid = I("get.ctid",0, 'intval' );
		$cpid = I("get.cpid",0, 'intval' );
		$num = I("post.num",0, 'intval' );
		$openid = get_openid();
		
		$data["token"] = $token;
		$data["openid"] = $openid;
		$data["cpid"] = $cpid;
		$data["setid"] = $ctid;
		if($num >=0){
			M ("ml_microcatering_order_temp")->add($data);		
		}else{
			//删除一条
			M ("ml_microcatering_order_temp")->where($data)->limit('1')->delete();
		}
		echo "ok";	
	}	
	
	//[getajax]删除菜单
	public function delshop(){
		$token = get_token();
		$ctid = I("get.ctid",0, 'intval' );
		$cpid = I("get.cpid",0, 'intval' );
		$openid = get_openid();
		
		$data["token"] = $token;
		$data["openid"] = $openid;
		$data["cpid"] = $cpid;
		$data["setid"] = $ctid;
		M ("ml_microcatering_order_temp")->where($data)->delete();	
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$ctid))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
		echo "ok";
	}
	
	//[postajax]查询菜品信息
	public function getcpinfobyid(){
		$token = get_token();
		$cpid = I("post.cpid",0, 'intval' );
			
		$cpinfo = M ("ml_microcatering_dishes")->where(array("token"=>$token,"id"=>$cpid))->find();				
		$cpinfo["icon"] = get_cover_url($cpinfo["icon"]);
		echo json_encode($cpinfo);	
	}
	
	//取消订单
	public function delmyorder(){
		$ctid = I ( 'get.ctid', 0, 'intval' );
		$ddid = I ( 'get.ddid', 0, 'intval' );
		$token = get_token();
		$openid = get_openid();
		
		$this->assign ( 'ctid', $ctid);
				
		$fx = C ( 'DB_PREFIX' );
		
		//查询订单是否已经确认,确认的需要进入审核由管理员进行删除
		$dddata = M ("ml_microcatering_order")->where (array("token"=>$token,"id"=>$ddid,"openid"=>$openid))->order ( 'id asc' )->find ();
		if($dddata["state"] == "1"){
			//进入审核			
			if($dddata["statekz"] == "0"){
				//未支付
				M ("ml_microcatering_order")->where (array("token"=>$token,"id"=>$ddid,"openid"=>$openid))->save(array("statekz"=>2));
			}else{
				//已经支付
				M ("ml_microcatering_order")->where (array("token"=>$token,"id"=>$ddid,"openid"=>$openid))->save(array("statekz"=>3));
			}
			 
			
		}else{
			if($dddata["statekz"] == "0"){
				//未支付
				//直接删除
				M ("ml_microcatering_order")->where (array("token"=>$token,"id"=>$ddid,"openid"=>$openid))->save(array("statekz"=>6));
			}else{
				//已经支付
				M ("ml_microcatering_order")->where (array("token"=>$token,"id"=>$ddid,"openid"=>$openid))->save(array("statekz"=>3));
			}
		}
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$ctid))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
		
		$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/myorder' ) );	
	}
	
	//查看菜品
	public function lookcp(){
		$ctid = I ( 'get.ctid', 0, 'intval' );
		$ddid = I ( 'get.ddid', 0, 'intval' );
		$token = get_token();
		$openid = get_openid();
		
		$this->assign ( 'ctid', $ctid);
				
		$fx = C ( 'DB_PREFIX' );
		
		//查询订单信息		
		$querysql = "select b.truename,b.tel,b.address,a.* from ".$fx ."ml_microcatering_order a left join ".$fx ."ml_microcatering_users b on a.contactid=b.id where a.set_id=".$ctid." and a.openid='".$openid."' and a.token='".$token."' and a.id='".$ddid."' and a.statekz != '6' ORDER BY a.ctime desc";
		$myorderdatas = M ()->query($querysql);
		$newdata = array();
		foreach($myorderdatas as $cp){
			//还原数据			
			$cpdataids = unserialize($cp["dishes_count_datas"]);
			$cp["dishes_count_datas"] = $cpdataids;
			$newdata[]  = $cp;
		}
		$this->assign ( 'ddcpdatas', $newdata);
		
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$ctid))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
		
		$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/myordercp' ) );	
	}
	
	
	/**************************************购物车***************************************************/
		
	public function myshop(){
		$ctid = I ( 'ctid', 0, 'intval' );
		$token = get_token();
		$openid = get_openid();
				
		$this->assign ( 'ctid', $ctid);
		
		$map ['token'] = $token;
		$map ['setid'] = $ctid;
		$map ['openid'] = $openid;
		
		$sumprice = 0;
		$sumcount = 0;
		//查询菜品
		$myordertemp = M ( "ml_microcatering_order_temp" )->where ( $map )->order ( 'id DESC' )->select ();
		$myshopdatas = array();
		foreach($myordertemp as $order_temp){
			if(isset($myshopdatas[$order_temp["cpid"]])){
				$cpdata = $myshopdatas[$order_temp["cpid"]];
				$cpdata["num"] = ($cpdata["num"]+1);
				$sumprice= ($sumprice + intval($cpdata["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata;
			}else{
				$cpdata1 = M ( "ml_microcatering_dishes" )->where (array("token"=>$token,"id"=>$order_temp["cpid"]))->find();
				$cpdata1["num"] = 1;
				$sumprice= ($sumprice + intval($cpdata1["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata1;
			}			
		}	
		
		$this->assign ( 'myshopdatas', $myshopdatas);
		
		//统计价格
		$this->assign ( 'sumprice', $sumprice);
		$this->assign ( 'sumcount', $sumcount);
		
		//查询模板
		$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$ctid))->order ( 'id asc' )->find ();
		$TemplateIndex = "default";
		
		if($ctdata["id"] != ""){
			$TemplateIndex = $ctdata["TemplateIndex"];		
		}
	
		$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/myshop' ) );	
	}
	
	
	public function jiesuan(){
		$ctid = I ( 'ctid', 0, 'intval' );
		$token = get_token();
		$openid = get_openid();
		
		$this->assign ( 'ctid', $ctid);
		
		$sumprice = 0;
		$sumcount = 0;
				
		$map ['token'] = $token;
		$map ['setid'] = $ctid;
		$map ['openid'] = $openid;
		
		//查询菜品		
		$myordertemp = M ( "ml_microcatering_order_temp" )->where ( $map )->order ( 'id DESC' )->select ();
		$myshopdatas = array();
		foreach($myordertemp as $order_temp){
			if(isset($myshopdatas[$order_temp["cpid"]])){
				$cpdata = $myshopdatas[$order_temp["cpid"]];
				$cpdata["num"] = ($cpdata["num"]+1);
				$sumprice= ($sumprice + intval($cpdata["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata;
			}else{
				$cpdata1 = M ( "ml_microcatering_dishes" )->where (array("token"=>$token,"id"=>$order_temp["cpid"]))->find();
				$cpdata1["num"] = 1;
				$sumprice= ($sumprice + intval($cpdata1["price"]));
				$sumcount= ($sumcount + 1);
				$myshopdatas[$order_temp["cpid"]] = $cpdata1;
			}			
		}	
		
		$this->assign ( 'myshopdatas', $myshopdatas);
		
		//统计价格
		$this->assign ( 'sumprice', $sumprice);
		$this->assign ( 'sumcount', $sumcount);
		
		if (IS_POST) {
			// 自动补充token
			$_POST ['token'] = get_token ();
			$_POST ['openid'] = $openid;
			$_POST ['jctime'] = $_POST["buytimestamp"]." ".$_POST["hour"].":00:00";
			$_POST ['ctime'] = date("y-m-d H:i:s",time());
			$_POST ['set_id'] = $ctid;
			$_POST ['dcnum'] = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
			//7天后过期自动删除(计划任务)
			$_POST ['endtime'] = date("y-m-d H:i:s",strtotime("+7 day"));
			
			$mm = M("ml_microcatering_order"); 
			//保存联系信息
			if($_POST ['contactid'] == "0"){
				$musers = M("ml_microcatering_users"); 
				//查询是否已经存在相同的
				$oldmuser = $musers->where(array("truename"=>$_POST ['username'],"tel"=>$_POST ['tel'],"address"=>$_POST ['address'],"openid"=>$openid,"set_id"=> $ctid))->find();
				if($oldmuser["id"] >0){
					$_POST ['contactid'] =  $oldmuser["id"];
				}else{					
					if ($musers->create () && $muserid = $musers->add ()) {	
						$_POST ['contactid'] = $muserid;
					}
				}
			}
			
			$_POST["dishes_count_datas"] = serialize($myshopdatas);
			if ($mm->create () && $orderid = $mm->add ()) {
				//成功后删除临时订单信息
				M ( "ml_microcatering_order_temp" )->where ( $map )->delete();
				$this->success ( '购买成功！', U ( 'myorder?success=1&ctid='.$ctid ) );
			} else {
				$this->error ( $Model->getError () );
			}			
			
		} else {
			
			//查询桌台
			$mytables = M ( "ml_microcatering_tablemanage" )->where (array("token"=>$token,"setid"=>$ctid) )->order ( 'paixu DESC' )->select ();
			$this->assign ( 'mytables', $mytables);
			
			//设置时间日期 
			$nowdates =array();
			for($i = 0; $i < 5; $i++){
				$dates["value"] =date("Y-m-d",strtotime("+".$i." day"));
				$dates["text"] = date("m月d日",strtotime("+".$i." day"));
				$nowdates[] = $dates;
			}
			$this->assign ( 'nowdates', $nowdates);
			
			//查询联系方式			
			$mycontact = M ( "ml_microcatering_users" )->where (array("token"=>$token,"set_id"=>$ctid,"openid"=>$openid) )->order ( 'id DESC' )->select ();
			$this->assign ( 'mycontact', $mycontact);
			
			//查询模板
			$ctdata = M ("ml_microcatering_set")->where (array("token"=>$token,"id"=>$ctid))->order ( 'id asc' )->find ();
			$TemplateIndex = "default";
			
			if($ctdata["id"] != ""){
				$TemplateIndex = $ctdata["TemplateIndex"];		
			}
			$this->display ( T ( 'Addons://MicroCatering@default/Template/'.$TemplateIndex.'/jiesuan' ) );
		}
	}
	
	/**************************************管理***************************************************/
		
	/**
	 * 显示指定模型列表数据
	 */
	public function lists() {
		// 使用提示
		$normal_tips = '“微餐饮”可以添加多个分店';
		$this->assign ( 'normal_tips', $normal_tips );
	
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
		$map ['token'] = get_token ();
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
		$this->display ( T ( 'Addons://MicroCatering@default/MicroCatering/lists' ) );		
	}	
	
	public function listsadd() {		
		if (IS_POST) {
			// 自动补充token
			$_POST ['token'] = get_token ();
			$Model = D ( parse_name ( get_table_name ( $this->model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $this->model ['id'] );
			if ($Model->create () && $micsetid = $Model->add ()) {				
				// 保存关键词
				D ( 'Common/Keyword' )->set ( I ( 'keyword' ), 'MicroCatering', $micsetid );
				
				$this->success ( '添加' . $this->model ['title'] . '成功！', U ( 'lists' ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			
			$micset_fields = get_model_attribute ( $this->model ['id'] );
			$this->assign ( 'fields', $micset_fields );
			
			$this->meta_title = '新增' . $this->model ['title'];
			$this->display ('listsadd');			
		}
	}
	
	public function listsedit() {
		// 获取模型信息
		$id = I ( 'id', 0, 'intval' );		
		if (IS_POST) {
			$Model = D ( parse_name ( get_table_name ( $this->model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $this->model ['id'] );
			if ($Model->create () && $Model->save ()) {				
				// 保存关键词
				D ( 'Common/Keyword' )->set ( I ( 'post.keyword' ), 'MicroCatering', I ( 'post.id' ) );
				
				$this->success ( '保存' . $this->model ['title'] . '成功！', U ( 'lists' ) );
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
			$this->display ( 'listsedit');
		}
	}
	
	public function listsdel() {
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
	
	
	public function tz(){
		echo "敬请期待！";
		exit();
	}
	
	public function detail(){
		echo "敬请期待！";
		exit();
	}
}
