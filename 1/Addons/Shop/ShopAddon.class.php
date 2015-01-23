<?php

namespace Addons\Shop;
use Common\Controller\Addon;

/**
 * 微商店插件
 * @author 凡星
 */

    class ShopAddon extends Addon{

        public $info = array(
            'name'=>'Shop',
            'title'=>'微商店',
            'description'=>'实现商店商品管理、分类管理等功能',
            'status'=>1,
            'author'=>'凡星',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Shop/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Shop/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }