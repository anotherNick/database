CREATE TABLE `world` (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT
);

ALTER TABLE `area` RENAME TO `old_area`;
CREATE TABLE `area` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT,
    `image` TEXT,
    `world_id` INTEGER,
    FOREIGN KEY(`world_id`)
        REFERENCES `world`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL
);
INSERT INTO area 
    (id, name, image)
    SELECT
        id, name, image
    FROM
        old_area;
DROP TABLE old_area;

ALTER TABLE `areareagent` RENAME TO `old_areareagent`;
CREATE TABLE `areareagent` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `votes_up` INTEGER,
    `votes_down` INTEGER,
    `area_id` INTEGER,
    `reagent_id` INTEGER,
    FOREIGN KEY(`area_id`)
        REFERENCES `area`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL,
    FOREIGN KEY(`reagent_id`)
        REFERENCES `reagent`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL
);
INSERT INTO areareagent 
    (id, votes_up, votes_down, area_id, reagent_id) 
    SELECT
        id, votes_up, votes_down, area_id, reagent_id
    FROM
        old_areareagent;
DROP TABLE old_areareagent;
