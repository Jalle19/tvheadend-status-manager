<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1455269455.
 * Generated on 2016-02-12 09:30:55 by vagrant
 */
class PropelMigration_1455269455
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
  'tvheadend_status_manager' => '
PRAGMA foreign_keys = OFF;

CREATE TEMPORARY TABLE [subscription__temp__56bda64f12586] AS SELECT [id],[instance_name],[user_id],[channel_id],[subscription_id],[started],[stopped],[title],[service],[input_id] FROM [subscription];
DROP TABLE [subscription];

CREATE TABLE [subscription]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [input_uuid] VARCHAR(255),
    [user_id] INTEGER,
    [channel_id] INTEGER NOT NULL,
    [subscription_id] INTEGER NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [stopped] TIMESTAMP,
    [title] VARCHAR(255) NOT NULL,
    [service] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([channel_id]) REFERENCES [channel] ([id])
);

INSERT INTO [subscription] (id, instance_name, user_id, channel_id, subscription_id, started, stopped, title, service) SELECT id, instance_name, user_id, channel_id, subscription_id, started, stopped, title, service FROM [subscription__temp__56bda64f12586];
DROP TABLE [subscription__temp__56bda64f12586];

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
  'tvheadend_status_manager' => '
PRAGMA foreign_keys = OFF;

CREATE TEMPORARY TABLE [subscription__temp__56bda64f128f1] AS SELECT [id],[instance_name],[input_uuid],[user_id],[channel_id],[subscription_id],[started],[stopped],[title],[service] FROM [subscription];
DROP TABLE [subscription];

CREATE TABLE [subscription]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [user_id] INTEGER,
    [channel_id] INTEGER NOT NULL,
    [subscription_id] INTEGER NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [stopped] TIMESTAMP,
    [title] VARCHAR(255) NOT NULL,
    [service] VARCHAR(255) NOT NULL,
    [input_id] INTEGER,
    UNIQUE ([id]),
    FOREIGN KEY ([channel_id]) REFERENCES [channel] ([id]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

INSERT INTO [subscription] (id, instance_name, user_id, channel_id, subscription_id, started, stopped, title, service) SELECT id, instance_name, user_id, channel_id, subscription_id, started, stopped, title, service FROM [subscription__temp__56bda64f128f1];
DROP TABLE [subscription__temp__56bda64f128f1];

PRAGMA foreign_keys = ON;
',
);
    }

}