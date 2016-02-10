<?php

namespace Jalle19\StatusManager\Database\Map;

use Jalle19\StatusManager\Database\Subscription;
use Jalle19\StatusManager\Database\SubscriptionQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'subscription' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class SubscriptionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Jalle19.StatusManager.Database.Map.SubscriptionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'tvheadend_status_manager';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'subscription';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Jalle19\\StatusManager\\Database\\Subscription';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Jalle19.StatusManager.Database.Subscription';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'subscription.id';

    /**
     * the column name for the instance_name field
     */
    const COL_INSTANCE_NAME = 'subscription.instance_name';

    /**
     * the column name for the user_id field
     */
    const COL_USER_ID = 'subscription.user_id';

    /**
     * the column name for the channel_id field
     */
    const COL_CHANNEL_ID = 'subscription.channel_id';

    /**
     * the column name for the subscription_id field
     */
    const COL_SUBSCRIPTION_ID = 'subscription.subscription_id';

    /**
     * the column name for the started field
     */
    const COL_STARTED = 'subscription.started';

    /**
     * the column name for the stopped field
     */
    const COL_STOPPED = 'subscription.stopped';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'subscription.title';

    /**
     * the column name for the service field
     */
    const COL_SERVICE = 'subscription.service';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'InstanceName', 'UserId', 'ChannelId', 'SubscriptionId', 'Started', 'Stopped', 'Title', 'Service', ),
        self::TYPE_CAMELNAME     => array('id', 'instanceName', 'userId', 'channelId', 'subscriptionId', 'started', 'stopped', 'title', 'service', ),
        self::TYPE_COLNAME       => array(SubscriptionTableMap::COL_ID, SubscriptionTableMap::COL_INSTANCE_NAME, SubscriptionTableMap::COL_USER_ID, SubscriptionTableMap::COL_CHANNEL_ID, SubscriptionTableMap::COL_SUBSCRIPTION_ID, SubscriptionTableMap::COL_STARTED, SubscriptionTableMap::COL_STOPPED, SubscriptionTableMap::COL_TITLE, SubscriptionTableMap::COL_SERVICE, ),
        self::TYPE_FIELDNAME     => array('id', 'instance_name', 'user_id', 'channel_id', 'subscription_id', 'started', 'stopped', 'title', 'service', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'InstanceName' => 1, 'UserId' => 2, 'ChannelId' => 3, 'SubscriptionId' => 4, 'Started' => 5, 'Stopped' => 6, 'Title' => 7, 'Service' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'instanceName' => 1, 'userId' => 2, 'channelId' => 3, 'subscriptionId' => 4, 'started' => 5, 'stopped' => 6, 'title' => 7, 'service' => 8, ),
        self::TYPE_COLNAME       => array(SubscriptionTableMap::COL_ID => 0, SubscriptionTableMap::COL_INSTANCE_NAME => 1, SubscriptionTableMap::COL_USER_ID => 2, SubscriptionTableMap::COL_CHANNEL_ID => 3, SubscriptionTableMap::COL_SUBSCRIPTION_ID => 4, SubscriptionTableMap::COL_STARTED => 5, SubscriptionTableMap::COL_STOPPED => 6, SubscriptionTableMap::COL_TITLE => 7, SubscriptionTableMap::COL_SERVICE => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'instance_name' => 1, 'user_id' => 2, 'channel_id' => 3, 'subscription_id' => 4, 'started' => 5, 'stopped' => 6, 'title' => 7, 'service' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('subscription');
        $this->setPhpName('Subscription');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Jalle19\\StatusManager\\Database\\Subscription');
        $this->setPackage('Jalle19.StatusManager.Database');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('instance_name', 'InstanceName', 'VARCHAR', 'instance', 'name', true, 255, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'user', 'id', false, null, null);
        $this->addForeignKey('channel_id', 'ChannelId', 'INTEGER', 'channel', 'id', true, null, null);
        $this->addColumn('subscription_id', 'SubscriptionId', 'INTEGER', true, null, null);
        $this->addColumn('started', 'Started', 'TIMESTAMP', true, null, null);
        $this->addColumn('stopped', 'Stopped', 'TIMESTAMP', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->addColumn('service', 'Service', 'VARCHAR', true, 255, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Instance', '\\Jalle19\\StatusManager\\Database\\Instance', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':instance_name',
    1 => ':name',
  ),
), null, null, null, false);
        $this->addRelation('User', '\\Jalle19\\StatusManager\\Database\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':user_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Channel', '\\Jalle19\\StatusManager\\Database\\Channel', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':channel_id',
    1 => ':id',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? SubscriptionTableMap::CLASS_DEFAULT : SubscriptionTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Subscription object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = SubscriptionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = SubscriptionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + SubscriptionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SubscriptionTableMap::OM_CLASS;
            /** @var Subscription $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            SubscriptionTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = SubscriptionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = SubscriptionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Subscription $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SubscriptionTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(SubscriptionTableMap::COL_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_INSTANCE_NAME);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_USER_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_CHANNEL_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SUBSCRIPTION_ID);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_STARTED);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_STOPPED);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_TITLE);
            $criteria->addSelectColumn(SubscriptionTableMap::COL_SERVICE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.instance_name');
            $criteria->addSelectColumn($alias . '.user_id');
            $criteria->addSelectColumn($alias . '.channel_id');
            $criteria->addSelectColumn($alias . '.subscription_id');
            $criteria->addSelectColumn($alias . '.started');
            $criteria->addSelectColumn($alias . '.stopped');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.service');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(SubscriptionTableMap::DATABASE_NAME)->getTable(SubscriptionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(SubscriptionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(SubscriptionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new SubscriptionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Subscription or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Subscription object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Jalle19\StatusManager\Database\Subscription) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SubscriptionTableMap::DATABASE_NAME);
            $criteria->add(SubscriptionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = SubscriptionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            SubscriptionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                SubscriptionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the subscription table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return SubscriptionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Subscription or Criteria object.
     *
     * @param mixed               $criteria Criteria or Subscription object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Subscription object
        }

        if ($criteria->containsKey(SubscriptionTableMap::COL_ID) && $criteria->keyContainsValue(SubscriptionTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.SubscriptionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = SubscriptionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // SubscriptionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
SubscriptionTableMap::buildTableMap();
