<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1417333690.
 * Generated on 2014-11-30 08:48:10 
 */
class PropelMigration_1417333690
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

CREATE TEMPORARY TABLE [area__temp__547acbb972a35] AS SELECT [id],[name],[image],[world_id] FROM [area];
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

INSERT INTO [area] (id, name, image, world_id) SELECT id, name, image, world_id FROM [area__temp__547acbb972a35];
DROP TABLE [area__temp__547acbb972a35];

CREATE TEMPORARY TABLE [areafish__temp__547acbb97dde8] AS SELECT [id],[votes_up],[votes_down],[area_id],[fish_id] FROM [areafish];
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
    FOREIGN KEY ([fish_id]) REFERENCES [fish] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areafish_area] ON [areafish] ([area_id]);

CREATE INDEX [index_foreignkey_areafish_fish] ON [areafish] ([fish_id]);

INSERT INTO [areafish] (id, votes_up, votes_down, area_id, fish_id) SELECT id, votes_up, votes_down, area_id, fish_id FROM [areafish__temp__547acbb97dde8];
DROP TABLE [areafish__temp__547acbb97dde8];

CREATE TEMPORARY TABLE [areareagent__temp__547acbb98fb14] AS SELECT [id],[votes_up],[votes_down],[area_id],[reagent_id] FROM [areareagent];
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
    FOREIGN KEY ([reagent_id]) REFERENCES [reagent] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([area_id]) REFERENCES [area] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_areareagent_reagent] ON [areareagent] ([reagent_id]);

CREATE INDEX [index_foreignkey_areareagent_area] ON [areareagent] ([area_id]);

INSERT INTO [areareagent] (id, votes_up, votes_down, area_id, reagent_id) SELECT id, votes_up, votes_down, area_id, reagent_id FROM [areareagent__temp__547acbb98fb14];
DROP TABLE [areareagent__temp__547acbb98fb14];

CREATE TEMPORARY TABLE [class__temp__547acbb9a08a0] AS SELECT [id],[name] FROM [class];
DROP TABLE [class];

CREATE TABLE [class]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [class] (id, name) SELECT id, name FROM [class__temp__547acbb9a08a0];
DROP TABLE [class__temp__547acbb9a08a0];

CREATE TEMPORARY TABLE [fish__temp__547acbb9a81b9] AS SELECT [id],[name],[image],[rank],[description],[initial_xp],[rarity_id],[class_id] FROM [fish];
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
    FOREIGN KEY ([class_id]) REFERENCES [class] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL,
    FOREIGN KEY ([rarity_id]) REFERENCES [rarity] ([id])
        ON UPDATE SET NULL
        ON DELETE SET NULL
);

CREATE INDEX [index_foreignkey_fish_rarity] ON [fish] ([rarity_id]);

CREATE INDEX [index_foreignkey_fish_class] ON [fish] ([class_id]);

INSERT INTO [fish] (id, name, image, rank, description, initial_xp, rarity_id, class_id) SELECT id, name, image, rank, description, initial_xp, rarity_id, class_id FROM [fish__temp__547acbb9a81b9];
DROP TABLE [fish__temp__547acbb9a81b9];

CREATE TEMPORARY TABLE [fish_housingitem__temp__547acbb9b9afe] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
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

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__547acbb9b9afe];
DROP TABLE [fish_housingitem__temp__547acbb9b9afe];

CREATE TEMPORARY TABLE [housingitem__temp__547acbb9c5298] AS SELECT [id],[name],[can_trade],[can_auction],[housingtype_id] FROM [housingitem];
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

INSERT INTO [housingitem] (id, name, can_trade, can_auction, housingtype_id) SELECT id, name, can_trade, can_auction, housingtype_id FROM [housingitem__temp__547acbb9c5298];
DROP TABLE [housingitem__temp__547acbb9c5298];

CREATE TEMPORARY TABLE [housingtype__temp__547acbb9cf2c3] AS SELECT [id],[name] FROM [housingtype];
DROP TABLE [housingtype];

CREATE TABLE [housingtype]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [housingtype] (id, name) SELECT id, name FROM [housingtype__temp__547acbb9cf2c3];
DROP TABLE [housingtype__temp__547acbb9cf2c3];

CREATE TEMPORARY TABLE [migrations__temp__547acbb9d3914] AS SELECT [id],[file] FROM [migrations];
DROP TABLE [migrations];

CREATE TABLE [migrations]
(
    [id] INTEGER NOT NULL,
    [file] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [migrations] (id, file) SELECT id, file FROM [migrations__temp__547acbb9d3914];
DROP TABLE [migrations__temp__547acbb9d3914];

CREATE TEMPORARY TABLE [rarity__temp__547acbb9d834d] AS SELECT [id],[name] FROM [rarity];
DROP TABLE [rarity];

CREATE TABLE [rarity]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [rarity] (id, name) SELECT id, name FROM [rarity__temp__547acbb9d834d];
DROP TABLE [rarity__temp__547acbb9d834d];

CREATE TEMPORARY TABLE [reagent__temp__547acbb9df0ae] AS SELECT [id],[name],[rank],[image],[description],[can_auction],[is_crowns_only],[is_retired],[class_id] FROM [reagent];
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

INSERT INTO [reagent] (id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id) SELECT id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id FROM [reagent__temp__547acbb9df0ae];
DROP TABLE [reagent__temp__547acbb9df0ae];

CREATE TEMPORARY TABLE [world__temp__547acbb9ecf5a] AS SELECT [id],[name],[image] FROM [world];
DROP TABLE [world];

CREATE TABLE [world]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [world] (id, name, image) SELECT id, name, image FROM [world__temp__547acbb9ecf5a];
DROP TABLE [world__temp__547acbb9ecf5a];

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

CREATE TEMPORARY TABLE [area__temp__547acbba0777d] AS SELECT [id],[name],[image],[world_id] FROM [area];
DROP TABLE [area];

CREATE TABLE [area]
(
    [id] INTEGER,
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

INSERT INTO [area] (id, name, image, world_id) SELECT id, name, image, world_id FROM [area__temp__547acbba0777d];
DROP TABLE [area__temp__547acbba0777d];

CREATE TEMPORARY TABLE [areafish__temp__547acbba113bf] AS SELECT [id],[votes_up],[votes_down],[area_id],[fish_id] FROM [areafish];
DROP TABLE [areafish];

CREATE TABLE [areafish]
(
    [id] INTEGER,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
    [area_id] INTEGER,
    [fish_id] INTEGER,
    PRIMARY KEY ([id]),
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

INSERT INTO [areafish] (id, votes_up, votes_down, area_id, fish_id) SELECT id, votes_up, votes_down, area_id, fish_id FROM [areafish__temp__547acbba113bf];
DROP TABLE [areafish__temp__547acbba113bf];

CREATE TEMPORARY TABLE [areareagent__temp__547acbba1f653] AS SELECT [id],[votes_up],[votes_down],[area_id],[reagent_id] FROM [areareagent];
DROP TABLE [areareagent];

CREATE TABLE [areareagent]
(
    [id] INTEGER,
    [votes_up] INTEGER,
    [votes_down] INTEGER,
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

CREATE INDEX [index_foreignkey_areareagent_reagent] ON [areareagent] ([reagent_id]);

CREATE INDEX [index_foreignkey_areareagent_area] ON [areareagent] ([area_id]);

INSERT INTO [areareagent] (id, votes_up, votes_down, area_id, reagent_id) SELECT id, votes_up, votes_down, area_id, reagent_id FROM [areareagent__temp__547acbba1f653];
DROP TABLE [areareagent__temp__547acbba1f653];

CREATE TEMPORARY TABLE [class__temp__547acbba2bd8d] AS SELECT [id],[name] FROM [class];
DROP TABLE [class];

CREATE TABLE [class]
(
    [id] INTEGER,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [class] (id, name) SELECT id, name FROM [class__temp__547acbba2bd8d];
DROP TABLE [class__temp__547acbba2bd8d];

CREATE TEMPORARY TABLE [fish__temp__547acbba31f37] AS SELECT [id],[name],[image],[rank],[description],[initial_xp],[rarity_id],[class_id] FROM [fish];
DROP TABLE [fish];

CREATE TABLE [fish]
(
    [id] INTEGER,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    [rank] INTEGER,
    [description] MEDIUMTEXT,
    [initial_xp] INTEGER,
    [rarity_id] INTEGER,
    [class_id] INTEGER,
    PRIMARY KEY ([id]),
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

INSERT INTO [fish] (id, name, image, rank, description, initial_xp, rarity_id, class_id) SELECT id, name, image, rank, description, initial_xp, rarity_id, class_id FROM [fish__temp__547acbba31f37];
DROP TABLE [fish__temp__547acbba31f37];

CREATE TEMPORARY TABLE [fish_housingitem__temp__547acbba428db] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
DROP TABLE [fish_housingitem];

CREATE TABLE [fish_housingitem]
(
    [id] INTEGER,
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

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__547acbba428db];
DROP TABLE [fish_housingitem__temp__547acbba428db];

CREATE TEMPORARY TABLE [housingitem__temp__547acbba4d0d5] AS SELECT [id],[name],[can_trade],[can_auction],[housingtype_id] FROM [housingitem];
DROP TABLE [housingitem];

CREATE TABLE [housingitem]
(
    [id] INTEGER,
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

INSERT INTO [housingitem] (id, name, can_trade, can_auction, housingtype_id) SELECT id, name, can_trade, can_auction, housingtype_id FROM [housingitem__temp__547acbba4d0d5];
DROP TABLE [housingitem__temp__547acbba4d0d5];

CREATE TEMPORARY TABLE [housingtype__temp__547acbba57100] AS SELECT [id],[name] FROM [housingtype];
DROP TABLE [housingtype];

CREATE TABLE [housingtype]
(
    [id] INTEGER,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [housingtype] (id, name) SELECT id, name FROM [housingtype__temp__547acbba57100];
DROP TABLE [housingtype__temp__547acbba57100];

CREATE TEMPORARY TABLE [migrations__temp__547acbba5c309] AS SELECT [id],[file] FROM [migrations];
DROP TABLE [migrations];

CREATE TABLE [migrations]
(
    [id] INTEGER,
    [file] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [migrations] (id, file) SELECT id, file FROM [migrations__temp__547acbba5c309];
DROP TABLE [migrations__temp__547acbba5c309];

CREATE TEMPORARY TABLE [rarity__temp__547acbba6112a] AS SELECT [id],[name] FROM [rarity];
DROP TABLE [rarity];

CREATE TABLE [rarity]
(
    [id] INTEGER,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [rarity] (id, name) SELECT id, name FROM [rarity__temp__547acbba6112a];
DROP TABLE [rarity__temp__547acbba6112a];

CREATE TEMPORARY TABLE [reagent__temp__547acbba676bb] AS SELECT [id],[name],[rank],[image],[description],[can_auction],[is_crowns_only],[is_retired],[class_id] FROM [reagent];
DROP TABLE [reagent];

CREATE TABLE [reagent]
(
    [id] INTEGER,
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

INSERT INTO [reagent] (id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id) SELECT id, name, rank, image, description, can_auction, is_crowns_only, is_retired, class_id FROM [reagent__temp__547acbba676bb];
DROP TABLE [reagent__temp__547acbba676bb];

CREATE TEMPORARY TABLE [world__temp__547acbba76507] AS SELECT [id],[name],[image] FROM [world];
DROP TABLE [world];

CREATE TABLE [world]
(
    [id] INTEGER,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

INSERT INTO [world] (id, name, image) SELECT id, name, image FROM [world__temp__547acbba76507];
DROP TABLE [world__temp__547acbba76507];

PRAGMA foreign_keys = ON;
',
);
    }

}