<?php

namespace Addons\Mytest\Controller;
use Home\Controller\AddonsController;

class MytestController extends AddonsController{
    
    function cofig(){
    	$this->getModel();
    	if (IS_POST){
    		if($_POST ['config'] ['background'] == 1){
    			$_POST['config'] ['background_custom'] = get_cover_url ($_POST['config'] ['bg']);	
    		}

    		$flag = D('Common/AddonConfig') ->set ( _ADDONS,I('config'));
    		if($flag !== false){
               $this->success('保存成功', Cookie('__forward__') );
    		}else{
               $this->error ('保存失败');
    		}
    	}

    	$map['name'] = _ADDONS;
    	$addon = M('Addons')->where($map)->find();
    	if(! $addon)
    		$this->error('插件微安装');
    	$addon_class = get_addon_class( $addon['name']);

    	$data = new $addon_class();
    	$addon['addon_path'] = $data->addon_path;
    	$addon['custom_config'] = $data->custom_config;
    	$db_config = D ('Common/AddonConfig')->get(_ADDONS);
    	$addon['config'] = include $data->config_file;
    	if($db_config){
    		foreach ($addon['config'] as $key => $value) {
    			if($value ['type'] !='group'){
    				!isset ($db_config[$key]) || $addon ['config'][$key]['value'] = $db_config[$key];

    			}else{
    				foreach ($value['options'] as $gourp => $options) {
    					foreach ($options as $gkey => $value) {
    						!isset ($db_config[$key]) || $addon ['config'][$key]['options'][$gourp]['option'][$gkey]['value'] = $db_config[$gkey];

    					}
    				}
    			}


    		}
    	}

    	$this->assign('data', $addon);
    	$this->display();
    }
    function show(){
    	$tpl='show';
    	$map['uid'] = $this->mid;
    	$info = M('card_member')->where($map)->find():
    	if($info){
    		$tpl = 'show_card';
    		$this->assign('info' , $info);
    	}
    	$this->display($tpl);
    }
    function introduction(){
    	$this->display();
    }
    function storeList(){
        $this->display();
    }
    function writeCardInfo(){
    	$map ['uid'] = $this->mid;
    	$info = M('card_member')->where($map)->find();
    	if(IS_POST){

    		$data['username'] = I('post.username');
    		$data['phone'] = I('post.phone');
    		if($info){
    			$res = M('card_member')->where($map)->save($data);
    		}else{
    			$config = getAddonConfig('Card');
    			$map_token['token'] = get_token();
    			$data ['number'] = M('card_member')->where($map_token)->getField("max(number) as number");
    			if(empty($data['number'])){
    				$data[number] = $config['length'];
    			}else{
    				$data ['number'] +=1;
    			}

    			$data['uid']= $this->mid;
    			$data['cTime'] = time();
    			$data['token'] = get_token();
    			$res = M('card_member')->add($data);

    			add_credit('card_bind');
    		}
    		redirect(addons_url('Card://Card/showCard'));
    	}
    	$this->assign('info', $info);
    	$this->display('write_cardinfo');

    }
    function index(){
    	$this->display();
    }

    function bindCard(){
    	$this->display('bind_card');
    }

    function score(){
    	$this->display();
    }
    function exchange(){
    	$this->display();
    }
    function ticket(){
    	$this->display()
    }
    function showCard(){
    	$map['uid'] = $this->mid;
    	$info = M('card_member')->where($map)->find();
    	$this->assign('info' ,$info);
    	$this->display('show_card');
    }

}
