DROP TABLE IF EXISTS `areareagentspawn`;;
CREATE TABLE `areareagentspawn` ( 
	`id` INTEGER PRIMARY KEY AUTOINCREMENT  ,
	`x_loc` INTEGER,
	`y_loc` INTEGER,
	`area_id` INTEGER,
	`reagent_id` INTEGER,
	`votes_up` INTEGER,
	`votes_down` INTEGER,
	`areareagent_id` INTEGER  , 
	FOREIGN KEY(`area_id`)
		REFERENCES `area`(`id`)
		ON DELETE SET NULL ON UPDATE SET NULL, 
	FOREIGN KEY(`reagent_id`)
		REFERENCES `reagent`(`id`)
		ON DELETE SET NULL ON UPDATE SET NULL, 
	FOREIGN KEY(`areareagent_id`)
		REFERENCES `areareagent`(`id`)
		ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_areareagentspawn_areareagent ON `areareagentspawn` (areareagent_id) ;
CREATE INDEX index_foreignkey_areareagentspawn_reagent ON `areareagentspawn` (reagent_id) ;
CREATE INDEX index_foreignkey_areareagentspawn_area ON `areareagentspawn` (area_id) ;
