<?php
// +----------------------------------------------------------------------------+
// | 欢迎你光临开发者论坛：http://www.thmao.com                                     |
// +----------------------------------------------------------------------------+
// | 这类各类技术分享，都是由我自己在工作中总结出来，和在网上查询的资料整理，希望对各位有所帮助  |
// +----------------------------------------------------------------------------+
// | Author: 静静 <76966522@qq.com> <http://www.thmao.com>                       |
// +----------------------------------------------------------------------------+     	
namespace Addons\Shangcheng\Model;
use Home\Model\WeixinModel;
        	
/**
 * Shangcheng的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		// 其中token和openid这两个参数一定要传，否则程序不知道是哪个微信用户进入了系统
		$param ['token'] = get_token ();
		$param ['openid'] = get_openid ();

		$config = getAddonConfig ( 'Shangcheng' ); // 获取后台插件的配置参数
			$url = addons_url ( 'Shangcheng://Show/Index', $param );
			// 组装微信需要的图文数据，格式是固定的
			$articles [0] = array (
					'Title' => $config ['title'],
					'Description' => $config ['info'],
					'PicUrl' => get_cover_url ( $config ['cover'] ),
					'Url' => $url
			);
	$this->replyNews ( $articles );
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
        	