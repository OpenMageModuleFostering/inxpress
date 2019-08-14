<?php 
$installer = $this;
$installer->startSetup();
$installer->run("
		CREATE TABLE IF NOT EXISTS `{$installer->getTable('inxpress/variant')}` (
		`id` int(11) NOT NULL auto_increment,
		`product_id` int(11),
		`website` int(11),
		`store` int(11),
		`variant` text,
		`modifieddate` datetime,
		`length` text,
		`width` text,
		`height` text,
		`dim_weight` text,
		`variable` text,
		`extra` text,
		PRIMARY KEY  (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
$installer->run("
		CREATE TABLE IF NOT EXISTS `{$installer->getTable('inxpress/dhl')}` (
		`id` int(11) NOT NULL auto_increment,
		`website` int(11),
		`store` int(11),
		`supplies` text,
		`modifieddate` datetime,
		`length` text,
		`width` text,
		`height` text,
		`dim_weight` text,
		`variable` text,
		`extra` text,
		PRIMARY KEY  (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Express Envelope','12.6','9.4','1',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Express Legal Envelope','15','9.4','1',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Small Padded Pouch','9.8','12','1',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Large Padded Pouch','11.9','14.8','1',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Standard Flyer (Small Express Pack)','11.8','15.7','1',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Large Flyer (Large Express Pack)','15','18.7','1',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Box #2 Cube','10.8','5.8','5.9',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Box #2 Small','12.5','11.1','1.5',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Box #2 Medium','13.2','12.6','2.0',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Box #3 Large','17.5','12.5','3.0',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Box #3 Small Tri-Tube','5','5','25',now());");
$installer->run("INSERT INTO {$this->getTable('inxpress/dhl')} (supplies,length,width,height,modifieddate) values ('Box #4 Large Tri-Tube','38.4','6.9','6.9',now());");
$installer->endSetup();
?>