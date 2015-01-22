<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1421949403.
 * Generated on 2015-01-22 18:56:43 
 */
class PropelMigration_1421949403
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

CREATE TEMPORARY TABLE [fish_housingitem__temp__54c139dbcb576] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
DROP TABLE [fish_housingitem];

CREATE TABLE [fish_housingitem]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [housingitem_id] INTEGER,
    [fish_id] INTEGER,
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

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__54c139dbcb576];
DROP TABLE [fish_housingitem__temp__54c139dbcb576];

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

CREATE TEMPORARY TABLE [fish_housingitem__temp__54c139dbcc12e] AS SELECT [id],[housingitem_id],[fish_id] FROM [fish_housingitem];
DROP TABLE [fish_housingitem];

CREATE TABLE [fish_housingitem]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [housingitem_id] INTEGER,
    [fish_id] INTEGER,
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

INSERT INTO [fish_housingitem] (id, housingitem_id, fish_id) SELECT id, housingitem_id, fish_id FROM [fish_housingitem__temp__54c139dbcc12e];
DROP TABLE [fish_housingitem__temp__54c139dbcc12e];

PRAGMA foreign_keys = ON;
',
);
    }

}