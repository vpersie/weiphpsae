<?php
        	
namespace Addons\Idioms\Model;
use Home\Model\WeixinModel;
        	
/**
 * Idioms的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Idioms' ); // 获取后台插件的配置参数	
		//dump($config);
        $api = 'http://i.itpk.cn/api.php?question=@cy';
        if($dataArr['Content']=='成语接龙' || $dataArr['Content']=='Idioms'){
        	$keywordArr['step'] = 'input';
        	myset_user_status('Idioms', $keywordArr);
        	$this->replyText('请输入一个成语，比如：无疾而终');
        }
        if($keywordArr['step']=='input'){
        	if($dataArr['Content'] == '退出'){
        	$this->replyText('你已退出成语接龙模式，再次回复【成语接龙】即可进入。。');
        	return false;
        }
        $reply = file_get_contents($api.$dataArr['Content']);
        if($reply=='别来骗人家，不算随便打4个字就是成语哒！') || $reply=='成语必须为4个汉字'){
	       $keywordArr['step'] = 'input';
	       myset_user_status('Idioms' , $keywordArr);
	       $this->replyText($reply."\n".'重新输入一个成语开始继而，输入【退出】退出成语接龙');

        }else{
        	$keywordArr['step'] = 'input';
        	myset_user_status('Idioms' , $keywordArr);
        	$this->replyText($reply);
        }
	} 
       
}

    
    public function myset_user_status() {
		$user_status['addon'] = $addon;
	    $user_status['keywordArr'] = $keywordArr;
	    $openid = get_openid();
	    return S('user_status_'.$openid, $user_status);
	}
	// 关注公众号事件
	public function subscribe() {
		return true;
	}
	
	// 取消关注公众号事件
	public function unsubscribe() {
		return true;
	}
	
	// 扫描带参数二维码事件
	public function scan() {
		return true;
	}
	
	// 上报地理位置事件
	public function location() {
		return true;
	}
	
	// 自定义菜单事件
	public function click() {
		return true;
	}	
}
        	