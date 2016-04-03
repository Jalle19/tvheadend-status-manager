<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1459669466.
 * Generated on 2016-04-03 07:44:26 by vagrant
 */
class PropelMigration_1459669466
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

CREATE TEMPORARY TABLE [input_error__temp__5700c9da727b5] AS SELECT [id],[input_uuid],[timestamp],[ber_average],[unc_average],[cumulative_te],[cumulative_cc] FROM [input_error];
DROP TABLE [input_error];

CREATE TABLE [input_error]
(
    [id] INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    [input_uuid] VARCHAR(255) NOT NULL,
    [ber_average] DOUBLE NOT NULL,
    [unc_average] DOUBLE NOT NULL,
    [cumulative_te] INTEGER NOT NULL,
    [cumulative_cc] INTEGER NOT NULL,
    [created] TIMESTAMP,
    [modified] TIMESTAMP,
    UNIQUE ([id]),
    FOREIGN KEY ([input_uuid]) REFERENCES [input] ([uuid])
);

INSERT INTO [input_error] (id, input_uuid, ber_average, unc_average, cumulative_te, cumulative_cc) SELECT id, input_uuid, ber_average, unc_average, cumulative_te, cumulative_cc FROM [input_error__temp__5700c9da727b5];
DROP TABLE [input_error__temp__5700c9da727b5];

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

CREATE TEMPORARY TABLE [input_error__temp__5700c9da72a0c] AS SELECT [id],[input_uuid],[ber_average],[unc_average],[cumulative_te],[cumulative_cc],[created],[modified] FROM [input_error];
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

INSERT INTO [input_error] (id, input_uuid, ber_average, unc_average, cumulative_te, cumulative_cc) SELECT id, input_uuid, ber_average, unc_average, cumulative_te, cumulative_cc FROM [input_error__temp__5700c9da72a0c];
DROP TABLE [input_error__temp__5700c9da72a0c];

PRAGMA foreign_keys = ON;
',
);
    }

}