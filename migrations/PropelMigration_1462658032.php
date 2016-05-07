<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1462658032.
 * Generated on 2016-05-07 21:53:52 by vagrant
 */
class PropelMigration_1462658032
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

DROP TABLE IF EXISTS [input_error];

PRAGMA foreign_keys = ON;
',
);
    }

}