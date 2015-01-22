<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1421904861.
 * Generated on 2015-01-22 06:34:21 
 */
class PropelMigration_1421904861
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

CREATE TEMPORARY TABLE [area__temp__54c08bdda49ad] AS SELECT [id],[name],[image],[world_id] FROM [area];
DROP TABLE [area];

CREATE TABLE [area]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    [world_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([world_id]) REFERENCES [world] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_area_world] ON [area] ([world_id]);

INSERT INTO [area] (id, name, image, world_id) SELECT id, name, image, world_id FROM [area__temp__54c08bdda49ad];
DROP TABLE [area__temp__54c08bdda49ad];

CREATE TEMPORARY TABLE [areafish__temp__54c08bdda517d] AS SELECT [id],[votes_up],[votes_down],[area_id],[fish_id] FROM [areafish];
DROP TABLE [areafish];

CREATE TABLE [areafish]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
    [area_id] INTEGER,
    [fish_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([fish_id]) REFERENCES [fish] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areafish_area] ON [areafish] ([area_id]);

CREATE INDEX [index_foreignkey_areafish_fish] ON [areafish] ([fish_id]);

INSERT INTO [areafish] (id, votes_up, votes_down, area_id, fish_id) SELECT id, votes_up, votes_down, area_id, fish_id FROM [areafish__temp__54c08bdda517d];
DROP TABLE [areafish__temp__54c08bdda517d];

CREATE TEMPORARY TABLE [areareagent__temp__54c08bdda5d35] AS SELECT [id],[votes_up],[votes_down],[area_id],[reagent_id] FROM [areareagent];
DROP TABLE [areareagent];

CREATE TABLE [areareagent]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
    [area_id] INTEGER,
    [reagent_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([reagent_id]) REFERENCES [reagent] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areareagent_reagent] ON [areareagent] ([reagent_id]);

CREATE INDEX [index_foreignkey_areareagent_area] ON [areareagent] ([area_id]);

INSERT INTO [areareagent] (id, votes_up, votes_down, area_id, reagent_id) SELECT id, votes_up, votes_down, area_id, reagent_id FROM [areareagent__temp__54c08bdda5d35];
DROP TABLE [areareagent__temp__54c08bdda5d35];

CREATE TEMPORARY TABLE [class__temp__54c08bdda68ed] AS SELECT [id],[name] FROM [class];
DROP TABLE [class];

CREATE TABLE [class]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    UNIQUE ([id])
);

INSERT INTO [class] (id, name) SELECT id, name FROM [class__temp__54c08bdda68ed];
DROP TABLE [class__temp__54c08bdda68ed];

CREATE TEMPORARY TABLE [fish__temp__54c08bdda6cd5] AS SELECT [id],[name],[image],[rank],[description],[initial_xp],[rarity_id],[class_id] FROM [fish];
DROP TABLE [fish];

CREATE TABLE [fish]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    [rank] INTEGER,
    [description] MEDIUMTEXT,
    [initial_xp] INTEGER,
    [rarity_id] INTEGER,
    [class_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([class_id]) REFERENCES [class] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([rarity_id]) REFERENCES [rarity] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_fish_rarity] ON [fish] ([rarity_id]);

CREATE INDEX [index_foreignkey_fish_class] ON [fish] ([class_id]);

INSERT INTO [fish] (id, name, image, rank, description, initial_xp, rarity_id, class_id) SELECT id, name, image, rank, description, initial_xp, rarity_id, class_id FROM [fish__temp__54c08bdda6cd5];
DROP TABLE [fish__temp__54c08bdda6cd5];

CREATE TEMPORARY TABLE [fish_housingitem__temp__54c08bdda7c75] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
DROP TABLE [fish_housingitem];

CREATE TABLE [fish_housingitem]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [housingitem_id] INTEGER,
    [fish_id] INTEGER,
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

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__54c08bdda7c75];
DROP TABLE [fish_housingitem__temp__54c08bdda7c75];

CREATE TEMPORARY TABLE [housingitem__temp__54c08bdda882d] AS SELECT [id],[name],[can_trade],[can_auction],[housingtype_id],[can_hold_fish] FROM [housingitem];
DROP TABLE [housingitem];

CREATE TABLE [housingitem]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    [can_trade] INTEGER,
    [can_auction] INTEGER,
    [can_hold_fish] INTEGER,
    [housingtype_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([housingtype_id]) REFERENCES [housingtype] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_housingitem_housingtype] ON [housingitem] ([housingtype_id]);

INSERT INTO [housingitem] (id, name, can_trade, can_auction, can_hold_fish, housingtype_id) SELECT id, name, can_trade, can_auction, can_hold_fish, housingtype_id FROM [housingitem__temp__54c08bdda882d];
DROP TABLE [housingitem__temp__54c08bdda882d];

CREATE TEMPORARY TABLE [housingtype__temp__54c08bdda93e6] AS SELECT [id],[name] FROM [housingtype];
DROP TABLE [housingtype];

CREATE TABLE [housingtype]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    UNIQUE ([id])
);

INSERT INTO [housingtype] (id, name) SELECT id, name FROM [housingtype__temp__54c08bdda93e6];
DROP TABLE [housingtype__temp__54c08bdda93e6];

CREATE TEMPORARY TABLE [rarity__temp__54c08bdda97ce] AS SELECT [id],[name] FROM [rarity];
DROP TABLE [rarity];

CREATE TABLE [rarity]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    UNIQUE ([id])
);

INSERT INTO [rarity] (id, name) SELECT id, name FROM [rarity__temp__54c08bdda97ce];
DROP TABLE [rarity__temp__54c08bdda97ce];

CREATE TEMPORARY TABLE [reagent__temp__54c08bdda9bb6] AS SELECT [id],[name],[rank],[image],[description],[can_auction],[is_crowns_only],[is_retired],[class_id] FROM [reagent];
DROP TABLE [reagent];

CREATE TABLE [reagent]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    [rank] INTEGER,
    [image] MEDIUMTEXT,
    [description] MEDIUMTEXT,
    [can_auction] INTEGER,
    [is_crowns_only] INTEGER,
    [is_retired] INTEGER,
    [class_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([class_id]) REFERENCES [class] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_reagent_class] ON [reagent] ([class_id]);

INSERT INTO [reagent] (id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id) SELECT id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id FROM [reagent__temp__54c08bdda9bb6];
DROP TABLE [reagent__temp__54c08bdda9bb6];

CREATE TEMPORARY TABLE [world__temp__54c08bddaab56] AS SELECT [id],[name],[image] FROM [world];
DROP TABLE [world];

CREATE TABLE [world]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    UNIQUE ([id])
);

INSERT INTO [world] (id, name, image) SELECT id, name, image FROM [world__temp__54c08bddaab56];
DROP TABLE [world__temp__54c08bddaab56];

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

CREATE TEMPORARY TABLE [area__temp__54c08bddab70e] AS SELECT [id],[name],[image],[world_id] FROM [area];
DROP TABLE [area];

CREATE TABLE [area]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    [world_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([world_id]) REFERENCES [world] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_area_world] ON [area] ([world_id]);

INSERT INTO [area] (id, name, image, world_id) SELECT id, name, image, world_id FROM [area__temp__54c08bddab70e];
DROP TABLE [area__temp__54c08bddab70e];

CREATE TEMPORARY TABLE [areafish__temp__54c08bddac2c6] AS SELECT [id],[votes_up],[votes_down],[area_id],[fish_id] FROM [areafish];
DROP TABLE [areafish];

CREATE TABLE [areafish]
(
    [id] INTEGER NOT NULL,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
    [area_id] INTEGER,
    [fish_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([fish_id]) REFERENCES [fish] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areafish_fish] ON [areafish] ([fish_id]);

CREATE INDEX [index_foreignkey_areafish_area] ON [areafish] ([area_id]);

INSERT INTO [areafish] (id, votes_up, votes_down, area_id, fish_id) SELECT id, votes_up, votes_down, area_id, fish_id FROM [areafish__temp__54c08bddac2c6];
DROP TABLE [areafish__temp__54c08bddac2c6];

CREATE TEMPORARY TABLE [areareagent__temp__54c08bddace7f] AS SELECT [id],[votes_up],[votes_down],[area_id],[reagent_id] FROM [areareagent];
DROP TABLE [areareagent];

CREATE TABLE [areareagent]
(
    [id] INTEGER NOT NULL,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
    [area_id] INTEGER,
    [reagent_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([reagent_id]) REFERENCES [reagent] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areareagent_area] ON [areareagent] ([area_id]);

CREATE INDEX [index_foreignkey_areareagent_reagent] ON [areareagent] ([reagent_id]);

INSERT INTO [areareagent] (id, votes_up, votes_down, area_id, reagent_id) SELECT id, votes_up, votes_down, area_id, reagent_id FROM [areareagent__temp__54c08bddace7f];
DROP TABLE [areareagent__temp__54c08bddace7f];

CREATE TEMPORARY TABLE [class__temp__54c08bddada37] AS SELECT [id],[name] FROM [class];
DROP TABLE [class];

CREATE TABLE [class]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [class] (id, name) SELECT id, name FROM [class__temp__54c08bddada37];
DROP TABLE [class__temp__54c08bddada37];

CREATE TEMPORARY TABLE [fish__temp__54c08bddade1f] AS SELECT [id],[name],[image],[rank],[description],[initial_xp],[rarity_id],[class_id] FROM [fish];
DROP TABLE [fish];

CREATE TABLE [fish]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    [rank] INTEGER,
    [description] MEDIUMTEXT,
    [initial_xp] INTEGER,
    [rarity_id] INTEGER,
    [class_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([rarity_id]) REFERENCES [rarity] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([class_id]) REFERENCES [class] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_fish_class] ON [fish] ([class_id]);

CREATE INDEX [index_foreignkey_fish_rarity] ON [fish] ([rarity_id]);

INSERT INTO [fish] (id, name, image, rank, description, initial_xp, rarity_id, class_id) SELECT id, name, image, rank, description, initial_xp, rarity_id, class_id FROM [fish__temp__54c08bddade1f];
DROP TABLE [fish__temp__54c08bddade1f];

CREATE TEMPORARY TABLE [fish_housingitem__temp__54c08bddaedbf] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
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

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__54c08bddaedbf];
DROP TABLE [fish_housingitem__temp__54c08bddaedbf];

CREATE TEMPORARY TABLE [housingitem__temp__54c08bddaf58f] AS SELECT [id],[name],[can_trade],[can_auction],[can_hold_fish],[housingtype_id] FROM [housingitem];
DROP TABLE [housingitem];

CREATE TABLE [housingitem]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [can_trade] INTEGER,
    [can_auction] INTEGER,
    [housingtype_id] INTEGER,
    [can_hold_fish] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([housingtype_id]) REFERENCES [housingtype] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_housingitem_housingtype] ON [housingitem] ([housingtype_id]);

INSERT INTO [housingitem] (id, name, can_trade, can_auction, housingtype_id, can_hold_fish) SELECT id, name, can_trade, can_auction, housingtype_id, can_hold_fish FROM [housingitem__temp__54c08bddaf58f];
DROP TABLE [housingitem__temp__54c08bddaf58f];

CREATE TEMPORARY TABLE [housingtype__temp__54c08bddb0147] AS SELECT [id],[name] FROM [housingtype];
DROP TABLE [housingtype];

CREATE TABLE [housingtype]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [housingtype] (id, name) SELECT id, name FROM [housingtype__temp__54c08bddb0147];
DROP TABLE [housingtype__temp__54c08bddb0147];

CREATE TEMPORARY TABLE [rarity__temp__54c08bddb052f] AS SELECT [id],[name] FROM [rarity];
DROP TABLE [rarity];

CREATE TABLE [rarity]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [rarity] (id, name) SELECT id, name FROM [rarity__temp__54c08bddb052f];
DROP TABLE [rarity__temp__54c08bddb052f];

CREATE TEMPORARY TABLE [reagent__temp__54c08bddb0cff] AS SELECT [id],[name],[rank],[image],[description],[can_auction],[is_crowns_only],[is_retired],[class_id] FROM [reagent];
DROP TABLE [reagent];

CREATE TABLE [reagent]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [rank] INTEGER,
    [image] MEDIUMTEXT,
    [description] MEDIUMTEXT,
    [can_auction] INTEGER,
    [is_crowns_only] INTEGER,
    [is_retired] INTEGER,
    [class_id] INTEGER,
    PRIMARY KEY ([id]),
    UNIQUE ([id]),
    FOREIGN KEY ([class_id]) REFERENCES [class] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_reagent_class] ON [reagent] ([class_id]);

INSERT INTO [reagent] (id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id) SELECT id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id FROM [reagent__temp__54c08bddb0cff];
DROP TABLE [reagent__temp__54c08bddb0cff];

CREATE TEMPORARY TABLE [world__temp__54c08bddb18b8] AS SELECT [id],[name],[image] FROM [world];
DROP TABLE [world];

CREATE TABLE [world]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [world] (id, name, image) SELECT id, name, image FROM [world__temp__54c08bddb18b8];
DROP TABLE [world__temp__54c08bddb18b8];

PRAGMA foreign_keys = ON;
',
);
    }

}