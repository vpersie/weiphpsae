<?php
// +----------------------------------------------------------------------------+
// | 欢迎你光临开发者论坛：http://www.thmao.com                                     |
// +----------------------------------------------------------------------------+
// | 这类各类技术分享，都是由我自己在工作中总结出来，和在网上查询的资料整理，希望对各位有所帮助  |
// +----------------------------------------------------------------------------+
// | Author: 静静 <76966522@qq.com> <http://www.thmao.com>                       |
// +----------------------------------------------------------------------------+
namespace Addons\Shangcheng\Controller;

use Addons\Shangcheng\Controller\BaseController;

class ShangchengController extends BaseController {
	var $model;
	function _initialize() {
		$this->model = $this->getModel ( 'wscproduct' );
		parent::_initialize ();
	}
	// 通用插件的列表模型
	public function lists() {				
		$map ['token'] = get_token ();
		session ( 'common_condition', $map );
		
		$list_data = $this->_get_model_list ( $this->model );
		foreach ( $list_data ['list_data'] as &$vo ) {
			$src = get_cover_url ( $vo ['icon'] );
			$vo ['icon'] = empty($src) ? '' : '<img style="background:#ddd" src="' . $src . '" width="50px" >';
		}
		
		foreach ( $list_data ['list_data'] as &$vo ) {
			$class = getCommonCategoryTitle($vo ['categoryid']);
			$vo ['categoryid'] = $class;
		}
		$this->assign ( $list_data );
		//dump ( $list_data );
		
		$templateFile = $this->model ['template_list'] ? $this->model ['template_list'] : '';
		$this->display ( $templateFile );
	}
	// 通用插件的增加模型
	public function add() {
		$model = $this->model;
		$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
		
		if (IS_POST) {
			$_POST ['token'] = get_token();
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $id = $Model->add ()) {
				D ( 'Common/Keyword' )->set ( $_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news' );
				
				$this->success ( '添加' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			
			$extra = $this->getCateData ();
			if (! empty ( $extra )) {
				foreach ( $fields [1] as &$vo ) {
					if ($vo ['name'] == 'cate_id') {
						$vo ['extra'] .= "\r\n" . $extra;
					}
				}
			}
			$this->assign ( 'fields', $fields );
			$this->meta_title = '新增' . $model ['title'];
			
			$this->display ();
		}
	}
	// 通用插件的编辑模型
	public function edit() {
		$model = $this->model;
		$id = I ( 'id' );
		
		if (IS_POST) {
			$_POST ['token'] = get_token();
			$Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
			// 获取模型的字段信息
			$Model = $this->checkAttr ( $Model, $model ['id'] );
			if ($Model->create () && $Model->save ()) {
				D ( 'Common/Keyword' )->set ( $_POST ['keyword'], _ADDONS, $id, $_POST ['keyword_type'], 'custom_reply_news' );
				
				$this->success ( '保存' . $model ['title'] . '成功！', U ( 'lists?model=' . $model ['name'] ) );
			} else {
				$this->error ( $Model->getError () );
			}
		} else {
			$fields = get_model_attribute ( $model ['id'] );
			
			$extra = $this->getCateData ();
			if (! empty ( $extra )) {
				foreach ( $fields [1] as &$vo ) {
					if ($vo ['name'] == 'cate_id') {
						$vo ['extra'] .= "\r\n" . $extra;
					}
				}
			}
			
			// 获取数据
			$data = M ( get_table_name ( $model ['id'] ) )->find ( $id );
			$data || $this->error ( '数据不存在！' );
			
		$token = get_token ();
		if (isset ( $data ['token'] ) && $token != $data ['token'] && defined ( 'ADDON_PUBLIC_PATH' )) {
			$this->error ( '非法访问！' );
		}			
			
			$this->assign ( 'fields', $fields );
			$this->assign ( 'data', $data );
			$this->meta_title = '编辑' . $model ['title'];
			
			$this->display ();
		}
	}
	// 通用插件的删除模型
	public function del() {
		parent::common_del ( $this->model );
	}
	
	// 获取所属分类
	function getCateData() {
		$map ['is_show'] = 1;
		$map ['token'] = get_token ();
		$list = M ( 'weisite_category' )->where ( $map )->select ();
		foreach ( $list as $v ) {
			$extra .= $v ['id'] . ':' . $v ['title'] . "\r\n";
		}
		return $extra;
	}
}
