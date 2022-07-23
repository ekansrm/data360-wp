<?php
global $wpdb;
$order=$wpdb->prefix.'wpzt_order';
$collection=$wpdb->prefix.'wpzt_collection';


$ordertable="CREATE TABLE IF NOT EXISTS {$order} (
				id bigint(20) NOT NULL KEY AUTO_INCREMENT,
				sn varchar(35),
				uid int,
				pid int,
				type tinyint default 0,
				cookie varchar(128),
				session varchar(128),
				status tinyint default 0,
				price int,
				time int,
				paytype tinyint,
				paysn varchar(40),
				paytime int			
)";
$collectiontable="CREATE TABLE IF NOT EXISTS {$collection} (
					id bigint(20) NOT NULL KEY AUTO_INCREMENT,
					uid int,
					pid int,
					time int
)";



  require_once (ABSPATH."wp-admin/includes/upgrade.php");
  dbDelta($ordertable);
  dbDelta($collectiontable);
  update_option('wpzt_video_theme_version',WPZT_VERSION);
  