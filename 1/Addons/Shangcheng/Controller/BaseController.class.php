<?php
// +----------------------------------------------------------------------------+
// | 欢迎你光临开发者论坛：http://www.thmao.com                                     |
// +----------------------------------------------------------------------------+
// | 这类各类技术分享，都是由我自己在工作中总结出来，和在网上查询的资料整理，希望对各位有所帮助  |
// +----------------------------------------------------------------------------+
// | Author: 静静 <76966522@qq.com> <http://www.thmao.com>                       |
// +----------------------------------------------------------------------------+
namespace Addons\Shangcheng\Controller;

use Home\Controller\AddonsController;

class BaseController extends AddonsController {
	function _initialize() {
		parent::_initialize();
		
		$controller = strtolower ( _CONTROLLER );
		
		$res ['title'] = '商品列表';
		$res ['url'] = addons_url ( 'Shangcheng://Shangcheng/lists' );
		$res ['class'] = $controller == 'Shangcheng' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '订单表';
		$res ['url'] = addons_url ( 'Shangcheng://Salesorder/lists' );
		$res ['class'] = $controller == 'Salesorder' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '系统设置';
		$res ['url'] = addons_url ( 'Shangcheng://Shangcheng/config' );
		$res ['class'] = $controller == 'Shangcheng' ? 'current' : '';
		$nav [] = $res;
		
		$this->assign ( 'nav', $nav );
		
		$config = getAddonConfig ( 'Shangcheng' );
		$config ['background_url'] = $config ['background'] == 11 ? $config ['background_custom'] : ADDON_PUBLIC_PATH . '/card_bg_' . $config ['background'] . '.png';
		$this->assign ( 'config', $config );
		//dump ( $config );
		//dump(get_token());
	}
}