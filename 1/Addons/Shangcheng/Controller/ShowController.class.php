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

class ShowController extends AddonsController {
	var $model;
	var $forms_id;
	function _initialize() {
		parent::_initialize ();
		
		$this->model = $this->getModel ( 'wscproduct' );
		$this->name = parse_name ( get_table_name ( $this->model ['id'] ), true );
	}
	public function Index(){
		$user = M('wscproduct');
		//头条
		$link = $user->where(array('Recommend' => 2))->order("'read' asc")->limit(4)->select();
		foreach ( $link as &$vo ) {
			$src = get_cover_url ( $vo ['litpic'] );
			$vo ['litpic'] = empty($src) ? '' : '<img style="background:#ddd" src="' . $src . '" width="150px" >';
		}
		$this->assign('link',$link);
		//推荐
		$tuijian = $user->where(array('Recommend' => 1))->limit(6)->select();
		foreach ( $tuijian as &$vo ) {
			$src = get_cover_url ( $vo ['litpic'] );
			$vo ['litpic'] = empty($src) ? '' : '<img style="background:#ddd" src="' . $src . '" width="150px" >';
		}
		$this->assign('tuijian',$tuijian);
		$this->display();
		}
	public function xiadan(){
		$id = $_GET['id'];
		$user = M('wscproduct');
		$res = $user->where(array('id'=>$id))->find();
		$src = get_cover_url ( $res['litpic'] );
		$res['litpic'] = empty($src) ? '' : '<img style="background:#ddd" src="' . $src . '" width="300px" >';
		$this->assign('res',$res);
		$this->display();
		}
	public function splist(){
		//最新商品列表
		$page = I ( 'p', 1, 'intval' );
		$row = 3;
		$map['token'] = get_token();
		
		$name = parse_name ( get_table_name ( $this->model ['id'] ), true );
		$list = M($name)->where($map)->order ( 'pdate asc' )->selectPage($row);
		foreach ( $list['list_data'] as &$vo ) {
			$src = get_cover_url ( $vo ['litpic'] );
			$vo ['litpic'] = empty($src) ? '' : '<img style="background:#ddd" src="' . $src . '" width="100px" >';
		}
		$splist = ($list['list_data']);
		$page = ($list['_page']);
		$this->assign('page',$page);
		$this->assign('list',$splist);
		$this->display('list');
		}
	public function dd(){
		$map['token'] = get_token();
		if(M('wscaddress')->where($map)->find()){
			$id = $_POST['id'];
			$tr = M($this->name)->where(array('id'=>$id))->find();
			$td = M('wscaddress')->where($map)->find();
			$pcount = $_POST['pcount'];
			$data['name'] = $tr['name'];
			$data['odate'] = date('Y-m-d H:i:s', time());
			$data['normalprice'] = $tr['normalprice'];
			$data['pcount'] = $pcount;
			$data['token'] = get_token();
			$data['addr'] = $td['address'];	
			if(M('wscsalesorder')->add($data)){
			$this->success('下单成功,准备收货吧 亲!');
			}else{
			$this->error('下单失败,请亲认真检查一下额!');
			}
			}else{
			$this->error('对不起,你没有填写收货地址,请填写',U('/addon/Shangcheng/Show/address'));
			//_404('对不起,你没有填写收货地址,请填写',U('Show/address'));
			//redirect('../Show/address', 3, '对不起,你没有填写收货地址,请填写...');
			}
		}
	public function address(){
		
		$this->display();
		}
	public function addr(){
		$a = $_POST['sheng'];
		$b = $_POST['shi'];
		$c = $_POST['xian'];
		$d = $_POST['jie'];
		$map['address'] = $a.'省'.$b.'市'.$c.'区'.$d;
		$map['token'] = get_token();
		if(M('wscaddress')->add($map)){
			$this->success('提交成功,快去购买商品吧!');
			}else{
			$htis->error('提交失败,请认真查看未填项!');	
			}
		
		}
	public function seach(){
		$name = $_POST['keyword'];
		$map['name'] = array('like','%'.$name.'%');
		
		$page = I ( 'p', 1, 'intval' );
		$row = 3;
		$name = parse_name ( get_table_name ( $this->model ['id'] ), true );
		$list = M($name)->where($map)->order ( 'pdate asc' )->selectPage($row);
		foreach ( $list['list_data'] as &$vo ) {
			$src = get_cover_url ( $vo ['litpic'] );
			$vo ['litpic'] = empty($src) ? '' : '<img style="background:#ddd" src="' . $src . '" width="100px" >';
		}
		//echo M('wscproduct')->getLastSql();
		$seach = ($list['list_data']);
		$page = ($list['_page']);
		$this->assign('page',$page);
		$this->assign('list',$seach);
		$this->display();
		}
	public function mydd(){
		$token['token']= get_token();
		$link = M('wscsalesorder')->where($token)->select();
		$this->assign('mydd',$link);
		$this->display();
		}
	public function read(){
		$id = $_GET['id'];
		$read = read($id);
		echo $read;
		}
}

?>