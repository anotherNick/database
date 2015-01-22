<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1421643052.
 * Generated on 2015-01-19 05:50:52 
 */
class PropelMigration_1421643052
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'W101' => '
PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS [migrations];

CREATE TABLE [areafishspawn]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [x_loc] INTEGER,
    [y_loc] INTEGER,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
    [area_id] INTEGER,
    [fish_id] INTEGER,
    [areafish_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([areafish_id]) REFERENCES [areafish] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([fish_id]) REFERENCES [fish] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areafishspawn_area] ON [areafishspawn] ([area_id]);

CREATE INDEX [index_foreignkey_areafishspawn_fish] ON [areafishspawn] ([fish_id]);

CREATE INDEX [index_foreignkey_areafishspawn_areafish] ON [areafishspawn] ([areafish_id]);

CREATE TEMPORARY TABLE [areareagentspawn__temp__54bc8d2c2dad7] AS SELECT [id],[x_loc],[y_loc],[area_id],[reagent_id] FROM [areareagentspawn];
DROP TABLE [areareagentspawn];

CREATE TABLE [areareagentspawn]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [x_loc] INTEGER,
    [y_loc] INTEGER,
    [area_id] INTEGER,
    [reagent_id] INTEGER,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
    [areareagent_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([areareagent_id]) REFERENCES [areareagent] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([reagent_id]) REFERENCES [reagent] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areareagentspawn_area] ON [areareagentspawn] ([area_id]);

CREATE INDEX [index_foreignkey_areareagentspawn_reagent] ON [areareagentspawn] ([reagent_id]);

CREATE INDEX [index_foreignkey_areareagentspawn_areareagent] ON [areareagentspawn] ([areareagent_id]);

INSERT INTO [areareagentspawn] (id, x_loc, y_loc, area_id, reagent_id) SELECT id, x_loc, y_loc, area_id, reagent_id FROM [areareagentspawn__temp__54bc8d2c2dad7];
DROP TABLE [areareagentspawn__temp__54bc8d2c2dad7];

CREATE TEMPORARY TABLE [fish_housingitem__temp__54bc8d2c2ee5f] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
DROP TABLE [fish_housingitem];

CREATE TABLE [fish_housingitem]
(
    [id] INTEGER NOT NULL,
    [housingitem_id] INTEGER,
    [fish_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([housingitem_id],[fish_id]),
    UNIQUE ([id]),
    FOREIGN KEY ([fish_id]) REFERENCES [fish] ([id])
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY ([housingitem_id]) REFERENCES [housingitem] ([id])
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE INDEX [index_for_fish_housingitem_fish_id] ON [fish_housingitem] ([fish_id]);

CREATE INDEX [index_for_fish_housingitem_housingitem_id] ON [fish_housingitem] ([housingitem_id]);

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__54bc8d2c2ee5f];
DROP TABLE [fish_housingitem__temp__54bc8d2c2ee5f];

ALTER TABLE [housingitem] ADD [can_hold_fish] INTEGER;

PRAGMA foreign_keys = ON;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'W101' => '
PRAGMA foreign_keys = OFF;

DROP TABLE IF EXISTS [areafishspawn];

CREATE TABLE [migrations]
(
    [id] INTEGER NOT NULL,
    [file] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

CREATE TEMPORARY TABLE [areareagentspawn__temp__54bc8d2c2fe00] AS SELECT [id],[x_loc],[y_loc],[area_id],[reagent_id],[votes_up],[votes_down],[areareagent_id] FROM [areareagentspawn];
DROP TABLE [areareagentspawn];

CREATE TABLE [areareagentspawn]
(
    [id] INTEGER,
    [x_loc] INTEGER,
    [y_loc] INTEGER,
    [area_id] INTEGER,
    [reagent_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([reagent_id]) REFERENCES [reagent] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areareagentspawn_area] ON [areareagentspawn] ([area_id]);

CREATE INDEX [index_foreignkey_areareagentspawn_reagent] ON [areareagentspawn] ([reagent_id]);

INSERT INTO [areareagentspawn] (id, x_loc, y_loc, area_id, reagent_id) SELECT id, x_loc, y_loc, area_id, reagent_id FROM [areareagentspawn__temp__54bc8d2c2fe00];
DROP TABLE [areareagentspawn__temp__54bc8d2c2fe00];

CREATE TEMPORARY TABLE [fish_housingitem__temp__54bc8d2c309b8] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
DROP TABLE [fish_housingitem];

CREATE TABLE [fish_housingitem]
(
    [id] INTEGER NOT NULL,
    [housingitem_id] INTEGER,
    [fish_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([housingitem_id],[fish_id]),
    UNIQUE ([id]),
    FOREIGN KEY ([housingitem_id]) REFERENCES [housingitem] ([id])
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY ([fish_id]) REFERENCES [fish] ([id])
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE INDEX [index_for_fish_housingitem_housingitem_id] ON [fish_housingitem] ([housingitem_id]);

CREATE INDEX [index_for_fish_housingitem_fish_id] ON [fish_housingitem] ([fish_id]);

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__54bc8d2c309b8];
DROP TABLE [fish_housingitem__temp__54bc8d2c309b8];

CREATE TEMPORARY TABLE [housingitem__temp__54bc8d2c31570] AS SELECT [id],[name],[can_trade],[can_auction],[can_hold_fish],[housingtype_id] FROM [housingitem];
DROP TABLE [housingitem];

CREATE TABLE [housingitem]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [can_trade] INTEGER,
    [can_auction] INTEGER,
    [housingtype_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([housingtype_id]) REFERENCES [housingtype] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_housingitem_housingtype] ON [housingitem] ([housingtype_id]);

INSERT INTO [housingitem] (id, name, can_trade, can_auction, housingtype_id) SELECT id, name, can_trade, can_auction, housingtype_id FROM [housingitem__temp__54bc8d2c31570];
DROP TABLE [housingitem__temp__54bc8d2c31570];

PRAGMA foreign_keys = ON;
',
);
    }

}