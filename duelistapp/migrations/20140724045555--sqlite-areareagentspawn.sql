CREATE TABLE `areareagentspawn` ( id INTEGER PRIMARY KEY AUTOINCREMENT ) ;
ALTER TABLE `areareagentspawn` ADD `x_loc` INTEGER ;
ALTER TABLE `areareagentspawn` ADD `y_loc` INTEGER ;
ALTER TABLE `areareagentspawn` ADD `area_id` INTEGER ;
ALTER TABLE `areareagentspawn` ADD `reagent_id` INTEGER ;
DROP TABLE IF EXISTS tmp_backup;;
CREATE TEMPORARY TABLE tmp_backup(`id`,`x_loc`,`y_loc`,`area_id`,`reagent_id`);;

INSERT INTO tmp_backup SELECT * FROM `areareagentspawn`;;
DROP TABLE `areareagentspawn`;;
CREATE TABLE `areareagentspawn` ( 
	`id` INTEGER PRIMARY KEY AUTOINCREMENT  ,
	`x_loc` INTEGER,
	`y_loc` INTEGER,
	`area_id` INTEGER,
	`reagent_id` INTEGER   
);;
CREATE INDEX index_foreignkey_areareagentspawn_area ON `areareagentspawn` (area_id) ;
INSERT INTO `areareagentspawn` SELECT * FROM tmp_backup;;

DROP TABLE tmp_backup;;
DROP TABLE IF EXISTS tmp_backup;;
CREATE TEMPORARY TABLE tmp_backup(
	`id`,
	`x_loc`,
	`y_loc`,
	`area_id`,
	`reagent_id`
);;

INSERT INTO tmp_backup SELECT * FROM `areareagentspawn`;;
DROP TABLE `areareagentspawn`;;
CREATE TABLE `areareagentspawn` ( 
	`id` INTEGER PRIMARY KEY AUTOINCREMENT  ,
	`x_loc` INTEGER,
	`y_loc` INTEGER,
	`area_id` INTEGER,
	`reagent_id` INTEGER  , 
	FOREIGN KEY (`area_id`)
        REFERENCES `area`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL );;
	CREATE INDEX index_foreignkey_areareagentspawn_area ON `areareagentspawn` (area_id) ;
INSERT INTO `areareagentspawn` SELECT * FROM tmp_backup;;
DROP TABLE tmp_backup;;
DROP TABLE IF EXISTS tmp_backup;;
CREATE TEMPORARY TABLE tmp_backup(`id`,`x_loc`,`y_loc`,`area_id`,`reagent_id`);;

INSERT INTO tmp_backup SELECT * FROM `areareagentspawn`;;
DROP TABLE `areareagentspawn`;;
CREATE TABLE `areareagentspawn` ( `
	id` INTEGER PRIMARY KEY AUTOINCREMENT  ,
	`x_loc` INTEGER,
	`y_loc` INTEGER,
	`area_id` INTEGER,
	`reagent_id` INTEGER  ,
	FOREIGN KEY(`area_id`)
        REFERENCES `area`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL );;
	CREATE INDEX index_foreignkey_areareagentspawn_area ON `areareagentspawn` (area_id) ;
	CREATE INDEX index_foreignkey_areareagentspawn_reagent ON `areareagentspawn` (reagent_id) ;
INSERT INTO `areareagentspawn` SELECT * FROM tmp_backup;;
DROP TABLE tmp_backup;;
DROP TABLE IF EXISTS tmp_backup;;
CREATE TEMPORARY TABLE tmp_backup(`id`,`x_loc`,`y_loc`,`area_id`,`reagent_id`);;

INSERT INTO tmp_backup SELECT * FROM `areareagentspawn`;;
DROP TABLE `areareagentspawn`;;
CREATE TABLE `areareagentspawn` ( 
	`id` INTEGER PRIMARY KEY AUTOINCREMENT  ,
	`x_loc` INTEGER,
	`y_loc` INTEGER,
	`area_id` INTEGER,
	`reagent_id` INTEGER  ,
	FOREIGN KEY(`area_id`)
        REFERENCES `area`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL, 
	FOREIGN KEY(`reagent_id`)
        REFERENCES `reagent`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL );;
	CREATE INDEX index_foreignkey_areareagentspawn_reagent ON `areareagentspawn` (reagent_id) ;
	CREATE INDEX index_foreignkey_areareagentspawn_area ON `areareagentspawn` (area_id) ;
INSERT INTO `areareagentspawn` SELECT * FROM tmp_backup;;
DROP TABLE tmp_backup;;