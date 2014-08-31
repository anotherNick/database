CREATE TABLE `areafishspawn` ( 
	`id` INTEGER PRIMARY KEY AUTOINCREMENT  ,
	`x_loc` INTEGER,
	`y_loc` INTEGER,
	`votes_up` INTEGER,
	`votes_down` INTEGER,
	`area_id` INTEGER,
	`fish_id` INTEGER,
	`areafish_id` INTEGER  ,
FOREIGN KEY(`area_id`)
	REFERENCES `area`(`id`) ON DELETE SET NULL ON UPDATE SET NULL, FOREIGN KEY(`fish_id`)
	REFERENCES `fish`(`id`) ON DELETE SET NULL ON UPDATE SET NULL, FOREIGN KEY(`areafish_id`)
	REFERENCES `areafish`(`id`) ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_areafishspawn_areafish ON `areafishspawn` (areafish_id) ;
CREATE INDEX index_foreignkey_areafishspawn_fish ON `areafishspawn` (fish_id) ;
CREATE INDEX index_foreignkey_areafishspawn_area ON `areafishspawn` (area_id) ;