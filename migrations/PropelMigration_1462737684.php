<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1462737684.
 * Generated on 2016-05-08 20:01:24 by vagrant
 */
class PropelMigration_1462737684
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

CREATE TEMPORARY TABLE [channel__temp__572f9b1435600] AS SELECT [id],[instance_name],[name] FROM [channel];
DROP TABLE [channel];

CREATE TABLE [channel]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

CREATE INDEX [channel_instance_name_name] ON [channel] ([instance_name],[name]);

INSERT INTO [channel] (id, instance_name, name) SELECT id, instance_name, name FROM [channel__temp__572f9b1435600];
DROP TABLE [channel__temp__572f9b1435600];

CREATE TEMPORARY TABLE [connection__temp__572f9b1435940] AS SELECT [id],[instance_name],[user_id],[peer],[started],[type] FROM [connection];
DROP TABLE [connection];

CREATE TABLE [connection]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [user_id] INTEGER,
    [peer] VARCHAR(255) NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [type] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id])
);

CREATE INDEX [connection_instance_name_peer_started] ON [connection] ([instance_name],[peer],[started]);

INSERT INTO [connection] (id, instance_name, user_id, peer, started, type) SELECT id, instance_name, user_id, peer, started, type FROM [connection__temp__572f9b1435940];
DROP TABLE [connection__temp__572f9b1435940];

CREATE TEMPORARY TABLE [subscription__temp__572f9b1435d29] AS SELECT [id],[instance_name],[input_uuid],[user_id],[channel_id],[subscription_id],[started],[stopped],[title],[service] FROM [subscription];
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
    [service] VARCHAR(255),
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([channel_id]) REFERENCES [channel] ([id])
);

CREATE INDEX [subscription_instance_name_user_id_channel_id_subscription_id_started] ON [subscription] ([instance_name],[user_id],[channel_id],[subscription_id],[started]);

INSERT INTO [subscription] (id, instance_name, input_uuid, user_id, channel_id, subscription_id, started, stopped, title, service) SELECT id, instance_name, input_uuid, user_id, channel_id, subscription_id, started, stopped, title, service FROM [subscription__temp__572f9b1435d29];
DROP TABLE [subscription__temp__572f9b1435d29];

CREATE TEMPORARY TABLE [user__temp__572f9b143642b] AS SELECT [id],[instance_name],[name] FROM [user];
DROP TABLE [user];

CREATE TABLE [user]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

CREATE INDEX [user_instance_name_name] ON [user] ([instance_name],[name]);

INSERT INTO [user] (id, instance_name, name) SELECT id, instance_name, name FROM [user__temp__572f9b143642b];
DROP TABLE [user__temp__572f9b143642b];

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

CREATE TEMPORARY TABLE [channel__temp__572f9b14366f9] AS SELECT [id],[instance_name],[name] FROM [channel];
DROP TABLE [channel];

CREATE TABLE [channel]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

CREATE INDEX [instance_name_name] ON [channel] ([instance_name],[name]);

INSERT INTO [channel] (id, instance_name, name) SELECT id, instance_name, name FROM [channel__temp__572f9b14366f9];
DROP TABLE [channel__temp__572f9b14366f9];

CREATE TEMPORARY TABLE [connection__temp__572f9b14369f9] AS SELECT [id],[instance_name],[user_id],[peer],[started],[type] FROM [connection];
DROP TABLE [connection];

CREATE TABLE [connection]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [user_id] INTEGER,
    [peer] VARCHAR(255) NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [type] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

CREATE INDEX [instance_name_peer_started] ON [connection] ([instance_name],[peer],[started]);

INSERT INTO [connection] (id, instance_name, user_id, peer, started, type) SELECT id, instance_name, user_id, peer, started, type FROM [connection__temp__572f9b14369f9];
DROP TABLE [connection__temp__572f9b14369f9];

CREATE TEMPORARY TABLE [subscription__temp__572f9b1436fce] AS SELECT [id],[instance_name],[input_uuid],[user_id],[channel_id],[subscription_id],[started],[stopped],[title],[service] FROM [subscription];
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
    [service] VARCHAR(255),
    UNIQUE ([id]),
    FOREIGN KEY ([channel_id]) REFERENCES [channel] ([id]),
    FOREIGN KEY ([user_id]) REFERENCES [user] ([id]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

CREATE INDEX [instance_name_user_id_channel_id_subscription_id_started] ON [subscription] ([instance_name],[user_id],[channel_id],[subscription_id],[started]);

INSERT INTO [subscription] (id, instance_name, input_uuid, user_id, channel_id, subscription_id, started, stopped, title, service) SELECT id, instance_name, input_uuid, user_id, channel_id, subscription_id, started, stopped, title, service FROM [subscription__temp__572f9b1436fce];
DROP TABLE [subscription__temp__572f9b1436fce];

CREATE TEMPORARY TABLE [user__temp__572f9b1437467] AS SELECT [id],[instance_name],[name] FROM [user];
DROP TABLE [user];

CREATE TABLE [user]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [instance_name] VARCHAR(255) NOT NULL,
    [name] VARCHAR(255) NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

INSERT INTO [user] (id, instance_name, name) SELECT id, instance_name, name FROM [user__temp__572f9b1437467];
DROP TABLE [user__temp__572f9b1437467];

PRAGMA foreign_keys = ON;
',
);
    }

}