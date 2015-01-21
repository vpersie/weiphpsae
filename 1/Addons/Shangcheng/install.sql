CREATE TABLE IF NOT EXISTS `wp_wscaddress` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`token`  varchar(255) NOT NULL  COMMENT 'Token值',
`address`  varchar(255) NOT NULL  COMMENT '收货地址',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`) VALUES ('wscaddress','收货地址','0','','1','','1:基础','','','','','','10','','','1414404068','1414404068','1','MyISAM');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','Token值','varchar(255) NOT NULL','string','','','0','','0','0','1','1414404095','1414404095','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('address','收货地址','varchar(255) NOT NULL','string','','','0','','0','0','1','1414404111','1414404111','','3','','regex','','3','function');
UPDATE `wp_attribute` SET model_id= (SELECT MAX(id) FROM `wp_model`) WHERE model_id=0;

CREATE TABLE IF NOT EXISTS `wp_wscproduct` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NOT NULL  COMMENT '商品名',
`litpic`  int(10) UNSIGNED NOT NULL  COMMENT '商品展示图',
`desc`  text NOT NULL  COMMENT '描述',
`normalprice`  varchar(255) NOT NULL  COMMENT '出售价格',
`pdate`  int(10) NOT NULL  COMMENT '上柜日期',
`token`  varchar(255) NOT NULL  COMMENT 'Token值',
`categoryid`  char(50) NOT NULL  COMMENT '商品类别',
`price`  varchar(255) NOT NULL  COMMENT '原价',
`Recommend`  tinyint(2) NOT NULL  DEFAULT 0 COMMENT '推荐',
`read`  int(10) UNSIGNED NOT NULL  DEFAULT 200 COMMENT '阅读量',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`) VALUES ('wscproduct','商品信息表','0','','1','{"1":["name","categoryid","litpic","price","normalprice","Recommend","desc","pdate"]}','1:基础','','','','','id:编号\r\nname:商品名\r\nprice:原价\r\nnormalprice:出售价格\r\ncategoryid:商品类别\r\nid:操作:[EDIT]|编辑,[DELETE]|删除','10','name','name','1414120853','1414143202','1','MyISAM');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','商品名','varchar(255) NOT NULL','string','','','1','','0','0','1','1414120908','1414120908','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('litpic','商品展示图','int(10) UNSIGNED NOT NULL','picture','','','1','','0','0','1','1414120920','1414120920','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('desc','描述','text NOT NULL','editor','','','1','','0','0','1','1414120933','1414120933','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('normalprice','出售价格','varchar(255) NOT NULL','string','','','1','','0','0','1','1414120956','1414120956','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('pdate','上柜日期','int(10) NOT NULL','datetime','','','1','','0','0','1','1414120988','1414120988','','3','','regex','time','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','Token值','varchar(255) NOT NULL','string','','','0','','0','0','1','1414121004','1414121004','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('categoryid','商品类别','char(50) NOT NULL','cascade','','','1','','0','0','1','1414137419','1414121042','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('price','原价','varchar(255) NOT NULL','string','','','1','','0','0','1','1414142867','1414142867','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('Recommend','推荐','tinyint(2) NOT NULL','radio','0','','1','0:无\r\n1:推荐\r\n2:头条\r\n3:特荐','0','0','1','1414143299','1414143035','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('read','阅读量','int(10) UNSIGNED NOT NULL','num','200','','0','','0','0','1','1414490084','1414488618','','3','','regex','','3','function');
UPDATE `wp_attribute` SET model_id= (SELECT MAX(id) FROM `wp_model`) WHERE model_id=0;

CREATE TABLE IF NOT EXISTS `wp_wscsalesorder` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`addr`  varchar(255) NOT NULL  COMMENT '送货地址',
`name`  varchar(255) NOT NULL  COMMENT '商品名',
`odate`  varchar(255) NOT NULL  COMMENT '下单时间',
`normalprice`  varchar(255) NOT NULL  COMMENT '单价',
`pcount`  varchar(255) NOT NULL  COMMENT '数量',
`token`  varchar(255) NOT NULL  COMMENT 'Token值',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`) VALUES ('wscsalesorder','商品订单表','0','','1','{"1":["addr","name","odate","pcount","normalprice"]}','1:基础','','','','','name:商品名\r\nnormalprice:单价\r\npcount:订购数量\r\nodate:下单时间\r\nid:操作:[EDIT]|编辑,[DELETE]|删除','10','name','name','1414136543','1414466613','1','MyISAM');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('addr','送货地址','varchar(255) NOT NULL','string','','','1','','0','0','1','1414136618','1414136618','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','商品名','varchar(255) NOT NULL','string','','','1','','0','0','1','1414136632','1414136632','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('odate','下单时间','varchar(255) NOT NULL','string','','','1','','0','0','1','1414466437','1414136669','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('normalprice','单价','varchar(255) NOT NULL','string','','','1','','0','0','1','1414466527','1414136684','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('pcount','数量','varchar(255) NOT NULL','string','','','1','','0','0','1','1414136698','1414136698','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','Token值','varchar(255) NOT NULL','string','','','0','','0','0','1','1414136708','1414136708','','3','','regex','','3','function');
UPDATE `wp_attribute` SET model_id= (SELECT MAX(id) FROM `wp_model`) WHERE model_id=0;