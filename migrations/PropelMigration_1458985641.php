<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1458985641.
 * Generated on 2016-03-26 09:47:21 by vagrant
 */
class PropelMigration_1458985641
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

CREATE TEMPORARY TABLE [input__temp__56f65aa9de0ba] AS SELECT [uuid],[instance_name],[started],[input],[network],[mux],[weight] FROM [input];
DROP TABLE [input];

CREATE TABLE [input]
(
    [uuid] VARCHAR(255) NOT NULL,
    [instance_name] VARCHAR(255) NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [input] VARCHAR(255) NOT NULL,
    [network] VARCHAR(255) NOT NULL,
    [mux] VARCHAR(255) NOT NULL,
    [weight] INTEGER NOT NULL,
    PRIMARY KEY ([uuid]),
    UNIQUE ([uuid]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

INSERT INTO [input] (uuid, instance_name, started, input, network, mux, weight) SELECT uuid, instance_name, started, input, network, mux, weight FROM [input__temp__56f65aa9de0ba];
DROP TABLE [input__temp__56f65aa9de0ba];

CREATE TEMPORARY TABLE [input_error__temp__56f65aa9de371] AS SELECT [id],[input_uuid],[timestamp],[ber_average],[unc_average],[cumulative_te],[cumulative_cc] FROM [input_error];
DROP TABLE [input_error];

CREATE TABLE [input_error]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [input_uuid] VARCHAR(255) NOT NULL,
    [timestamp] TIMESTAMP NOT NULL,
    [ber_average] DOUBLE NOT NULL,
    [unc_average] DOUBLE NOT NULL,
    [cumulative_te] INTEGER NOT NULL,
    [cumulative_cc] INTEGER NOT NULL,
    UNIQUE ([id]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid])
);

INSERT INTO [input_error] (id, input_uuid, timestamp, ber_average, unc_average, cumulative_te, cumulative_cc) SELECT id, input_uuid, timestamp, ber_average, unc_average, cumulative_te, cumulative_cc FROM [input_error__temp__56f65aa9de371];
DROP TABLE [input_error__temp__56f65aa9de371];

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

CREATE TEMPORARY TABLE [input__temp__56f65aa9de5d0] AS SELECT [uuid],[instance_name],[started],[input],[network],[mux],[weight] FROM [input];
DROP TABLE [input];

CREATE TABLE [input]
(
    [uuid] VARCHAR(255) NOT NULL,
    [instance_name] VARCHAR(255) NOT NULL,
    [started] TIMESTAMP NOT NULL,
    [input] VARCHAR(255) NOT NULL,
    [network] VARCHAR(255) NOT NULL,
    [mux] VARCHAR(255) NOT NULL,
    [weight] INTEGER NOT NULL,
    PRIMARY KEY ([uuid]),
    UNIQUE ([uuid]),
    FOREIGN KEY ([uuid]) REFERENCES [input_error] ([input_uuid]),
    FOREIGN KEY ([instance_name]) REFERENCES [instance] ([name])
);

INSERT INTO [input] (uuid, instance_name, started, input, network, mux, weight) SELECT uuid, instance_name, started, input, network, mux, weight FROM [input__temp__56f65aa9de5d0];
DROP TABLE [input__temp__56f65aa9de5d0];

CREATE TEMPORARY TABLE [input_error__temp__56f65aa9de84b] AS SELECT [id],[input_uuid],[timestamp],[ber_average],[unc_average],[cumulative_te],[cumulative_cc] FROM [input_error];
DROP TABLE [input_error];

CREATE TABLE [input_error]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [input_uuid] VARCHAR(255) NOT NULL,
    [timestamp] TIMESTAMP NOT NULL,
    [ber_average] DOUBLE NOT NULL,
    [unc_average] DOUBLE NOT NULL,
    [cumulative_te] INTEGER NOT NULL,
    [cumulative_cc] INTEGER NOT NULL,
    UNIQUE ([input_uuid]),
    UNIQUE ([id]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid])
);

INSERT INTO [input_error] (id, input_uuid, timestamp, ber_average, unc_average, cumulative_te, cumulative_cc) SELECT id, input_uuid, timestamp, ber_average, unc_average, cumulative_te, cumulative_cc FROM [input_error__temp__56f65aa9de84b];
DROP TABLE [input_error__temp__56f65aa9de84b];

PRAGMA foreign_keys = ON;
',
);
    }

}