function SelectorView(id,selType){
	// 为了在函数中引用.
	var self = this;

	this.selType = selType;
	
	this.id = id;
	this._rendered = false;

	/**
	 * 当前控件所处的HTML节点引用.
	 * @type DOMElement
	 */
	this.container = null;
	/**
	 * 备选框TableView.
	 * @type TableView
	 */
	this.src = null;
	/**
	 * 已选框TableView.
	 * @type TableView
	 */
	this.dsts = [];
	
	this.typeid=0;

	this._init = function(){
		var div = document.getElementById(this.id);
		div.view = this;

		var id_prefix = 'asdfsafokmlv';
		var src_id = this.id + '_' + id_prefix + '_src';
		var dst_id = this.id + '_' + id_prefix + '_dst';
		var str = '';
		str += '<div class="SelectorView">\n';
		str += '<ul id="tabTransUl" class="tablist">';
		if(self.selType.length >0){
			for(var i=0;i<self.selType.length;i++){
				str += '<li class="tab"><a href="#tabpan_'+self.selType[i].id+'" class="tab_a '+(i==0?"tab_on":"")+'">'+self.selType[i].name+'</a></li>';
			}		
		}
		str += '</ul>';
		str += '<div class="selector_table cxdiv">\n';		
		str += '<div  class="src cxleft">';
			str += '<div id="' + src_id + '" class="src_div"></div>';
			str += '<input class="btn" type="button" value="选择" onclick="document.getElementById(\'' + this.id + '\').view.select()" />';
		str += "</div>\n";
		str += '<div  class="dst cxright">';
			str += '<ul class="tab_content">';
			if(self.selType.length >0){
				for(var i=0;i<self.selType.length;i++){
					str += '<li cxid="'+i+'" id="tabpan_'+self.selType[i].id+'" class="tabpanel" '+(i != 0?"style='display:none'":"")+'>';
					str += '<div id="' + dst_id+"_"+self.selType[i].id + '" class="dst_div"></div>';
					str += '<input class="btn" type="button" value="取消选择" onclick="document.getElementById(\'' + this.id + '\').view.unselect()" />';
					str +='</li>';
				}		
			}
			str += '</ul>';
			
		str += "</div>\n";
		str += "</div>\n";
		str += '</div>\n';
		div.innerHTML = str;
		
		this.container = div;
		this.src = new TableView(src_id);
		if(self.selType.length >0){
			for(var i=0;i<self.selType.length;i++){
				var tableview = new TableView(dst_id+"_"+self.selType[i].id);
				this.dsts.push(tableview);
			}		
		}
	}

	this._init();

	// 重写数据表格的行双击方法.
	this.src.dblclick = function(id){
		var row = self.src.get(id);
		if(row == false){
			return;
		}
		self.dsts[self.typeid].add(row);
		self.src.del(row);
	};

	// 重写数据表格的行双击方法.
	if(self.selType.length >0){
			for(var i=0;i<self.selType.length;i++){
				this.dsts[i].dblclick = function(id){
					var row = self.dsts[self.typeid].get(id);
					if(row == false){
						return;
					}
					self.src.add(row);
					self.dsts[self.typeid].del(row);
				};
			}		
	}
	

	/**
	 * 渲染整个选择控件.
	 */
	this.render = function(){
		this.src.render();
		if(self.selType.length >0){
			for(var i=0;i<self.selType.length;i++){
				this.dsts[i].render();
			}		
		}
		
		//渲染tab
		$("#tabTransUl a").powerSwitch({
			classAdd: "tab_on",				
			onSwitch: function(target) {
				var cxid = $(target).attr("cxid");
				//切换后执行
				self.typeid=cxid;
			}	
		});
		
		this._rendered = true;
	};

	/**
	 * 将备选框中选中的数据移动到已选框中.
	 */
	this.select = function(){
		var rows = this.src.getSelected();
		this.dsts[self.typeid].addRange(rows);
		this.src.delRange(rows);
		this.src.unselectAll();
	};

	/**
	 * 将已选框中选中的数据移动到备选框中.
	 */
	this.unselect = function(){
		var rows = this.dsts[self.typeid].getSelected();
		this.src.addRange(rows);
		this.dsts[self.typeid].delRange(rows);
		this.dsts[self.typeid].unselectAll();
	};

	/**
	 * 获取已选择的的记录对象的列表, 也即已选框中的所有记录.
	 */
	this.getSelected = function(){
		return this.dsts[self.typeid].getDataSource();
	};

	/**
	 * 获取所有已选择的数据对象键值列表.
	 */
	this.getSelectedKeys = function(){
		var result = [];
		if(self.selType.length >0){
			for(var i=0;i<self.selType.length;i++){
				var n_typeid = self.selType[i].id;				
				var rows = this.dsts[i].getDataSourceIDS();					
				var res = {};res["cxid"] =n_typeid;res["datas"]=rows;
				result.push(res);				
			}		
		}	
		return result;
	};
}
