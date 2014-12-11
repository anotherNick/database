
-----------------------------------------------------------------------
-- area
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [area];

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

-----------------------------------------------------------------------
-- areafish
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [areafish];

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

-----------------------------------------------------------------------
-- areafishspawn
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [areafishspawn];

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

-----------------------------------------------------------------------
-- areareagent
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [areareagent];

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

-----------------------------------------------------------------------
-- areareagentspawn
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [areareagentspawn];

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

-----------------------------------------------------------------------
-- class
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [class];

CREATE TABLE [class]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- fish
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [fish];

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

-----------------------------------------------------------------------
-- fish_housingitem
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [fish_housingitem];

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

-----------------------------------------------------------------------
-- housingitem
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [housingitem];

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

-----------------------------------------------------------------------
-- housingtype
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [housingtype];

CREATE TABLE [housingtype]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- migrations
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [migrations];

CREATE TABLE [migrations]
(
    [id] INTEGER NOT NULL,
    [file] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- rarity
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [rarity];

CREATE TABLE [rarity]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);

-----------------------------------------------------------------------
-- reagent
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [reagent];

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

-----------------------------------------------------------------------
-- world
-----------------------------------------------------------------------

DROP TABLE IF EXISTS [world];

CREATE TABLE [world]
(
    [id] INTEGER NOT NULL,
    [name] MEDIUMTEXT,
    [image] MEDIUMTEXT,
    PRIMARY KEY ([id]),
    UNIQUE ([id])
);
