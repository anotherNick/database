CREATE TABLE `reagent` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT,
    `rank` INTEGER,
    `image` TEXT,`description` TEXT,
    `can_auction` INTEGER,`is_crowns_only` INTEGER,
    `is_retired` INTEGER,
    `class_id` INTEGER,
    FOREIGN KEY(`class_id`)
        REFERENCES `class`(`id`)
        ON DELETE SET NULL ON UPDATE SET NULL
)
