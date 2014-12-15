<?php

namespace Addons/Testqj;

use Comon\Controller\Addon;

class TestqjAddon extends Addon{
	public $info = array (
		'name' => 'Testqj',
		'title' => ' ',
		'description' => '',
		'status' => 1,
		'author' => '',
		'version' => '1.0'
		);
	public $admin_list = array(
		'model' => 'Testqj',
		'fields' => '*',
		'map'=>'',
		'order'=> 'id desc',
		'listKey'=> array(
			'zduanmin'=> 'biaotouxiansm'
			)
		);
	public function install(){
		$install_sql = './Addons/Suggestions/install.sql';
		if(file_exists( $install_sql)){
			execute_sql_file( $install_sql);
		}
		return true;
	}

	public function uninstall(){
		$uninstall_sql = './Addons/Suggestions/uninstall.sql';
		if(file_exists($uninstall_sql)){
			execute_sql_file( $uninstall_sql);
		}
		return true;
	}

	public function weixin($param){
		
	}
}