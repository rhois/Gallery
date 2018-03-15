<?php
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('displaystore_gallery')};

CREATE TABLE {$this->getTable('displaystore_gallery')} (
  `gallery_id` bigint(20) NOT NULL primary key auto_increment,
  `title` VARCHAR( 255 ) NOT NULL,
  `uploaded_file` varchar(255) default NULL,
  `thumbnail` varchar(255) default NULL,
  `file_content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `product_ids` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `file_status` tinyint(4) NOT NULL default '2',
  `content_disp` tinyint(4) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  `update_time` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 