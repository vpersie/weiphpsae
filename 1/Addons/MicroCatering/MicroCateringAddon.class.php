<?php

namespace Addons\MicroCatering;
use Common\Controller\Addon;

/**
 * 微餐饮插件
 * @author 陌路生人(Les、拉帮姐派)
 */

    class MicroCateringAddon extends Addon{

        public $info = array(
            'name'=>'MicroCatering',
            'title'=>'微餐饮',
            'description'=>'微餐饮是专为餐饮行业开发的微信公众平台营销服务解决方案。基于微信公众平台，顾客可以通过微信查看餐厅介绍、就餐环境、菜品、订餐、订座、指引到店线路，顾客可以享受微信会员卡、积分、优惠券、大转盘刮刮卡等优惠服务。所有点单可通过pc来管理，并且订单有短信、飞信、邮件等多种通知方式。',
            'status'=>1,
            'author'=>'陌路生人(Les、拉帮姐派)',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/MicroCatering/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/MicroCatering/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }