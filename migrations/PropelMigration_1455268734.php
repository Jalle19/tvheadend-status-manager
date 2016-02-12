<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1455268734.
 * Generated on 2016-02-12 09:18:54 by vagrant
 */
class PropelMigration_1455268734
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

DROP TABLE IF EXISTS [input];

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

DROP TABLE IF EXISTS [input];

PRAGMA foreign_keys = ON;
',
);
    }

}
