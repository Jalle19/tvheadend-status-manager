<?php

namespace Jalle19\StatusManager\Database\Map;

use Jalle19\StatusManager\Database\Input;
use Jalle19\StatusManager\Database\InputQuery;
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
 * This class defines the structure of the 'input' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class InputTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Jalle19.StatusManager.Database.Map.InputTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'tvheadend_status_manager';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'input';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Jalle19\\StatusManager\\Database\\Input';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Jalle19.StatusManager.Database.Input';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the uuid field
     */
    const COL_UUID = 'input.uuid';

    /**
     * the column name for the instance_name field
     */
    const COL_INSTANCE_NAME = 'input.instance_name';

    /**
     * the column name for the started field
     */
    const COL_STARTED = 'input.started';

    /**
     * the column name for the input field
     */
    const COL_INPUT = 'input.input';

    /**
     * the column name for the network field
     */
    const COL_NETWORK = 'input.network';

    /**
     * the column name for the mux field
     */
    const COL_MUX = 'input.mux';

    /**
     * the column name for the weight field
     */
    const COL_WEIGHT = 'input.weight';

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
        self::TYPE_PHPNAME       => array('Uuid', 'InstanceName', 'Started', 'Input', 'Network', 'Mux', 'Weight', ),
        self::TYPE_CAMELNAME     => array('uuid', 'instanceName', 'started', 'input', 'network', 'mux', 'weight', ),
        self::TYPE_COLNAME       => array(InputTableMap::COL_UUID, InputTableMap::COL_INSTANCE_NAME, InputTableMap::COL_STARTED, InputTableMap::COL_INPUT, InputTableMap::COL_NETWORK, InputTableMap::COL_MUX, InputTableMap::COL_WEIGHT, ),
        self::TYPE_FIELDNAME     => array('uuid', 'instance_name', 'started', 'input', 'network', 'mux', 'weight', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Uuid' => 0, 'InstanceName' => 1, 'Started' => 2, 'Input' => 3, 'Network' => 4, 'Mux' => 5, 'Weight' => 6, ),
        self::TYPE_CAMELNAME     => array('uuid' => 0, 'instanceName' => 1, 'started' => 2, 'input' => 3, 'network' => 4, 'mux' => 5, 'weight' => 6, ),
        self::TYPE_COLNAME       => array(InputTableMap::COL_UUID => 0, InputTableMap::COL_INSTANCE_NAME => 1, InputTableMap::COL_STARTED => 2, InputTableMap::COL_INPUT => 3, InputTableMap::COL_NETWORK => 4, InputTableMap::COL_MUX => 5, InputTableMap::COL_WEIGHT => 6, ),
        self::TYPE_FIELDNAME     => array('uuid' => 0, 'instance_name' => 1, 'started' => 2, 'input' => 3, 'network' => 4, 'mux' => 5, 'weight' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('input');
        $this->setPhpName('Input');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Jalle19\\StatusManager\\Database\\Input');
        $this->setPackage('Jalle19.StatusManager.Database');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('uuid', 'Uuid', 'VARCHAR', true, 255, null);
        $this->addForeignKey('instance_name', 'InstanceName', 'VARCHAR', 'instance', 'name', true, 255, null);
        $this->addColumn('started', 'Started', 'TIMESTAMP', true, null, null);
        $this->addColumn('input', 'Input', 'VARCHAR', true, 255, null);
        $this->addColumn('network', 'Network', 'VARCHAR', true, 255, null);
        $this->addColumn('mux', 'Mux', 'VARCHAR', true, 255, null);
        $this->addColumn('weight', 'Weight', 'INTEGER', true, null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)];
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
        return (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? InputTableMap::CLASS_DEFAULT : InputTableMap::OM_CLASS;
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
     * @return array           (Input object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = InputTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InputTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InputTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InputTableMap::OM_CLASS;
            /** @var Input $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InputTableMap::addInstanceToPool($obj, $key);
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
            $key = InputTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InputTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Input $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InputTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(InputTableMap::COL_UUID);
            $criteria->addSelectColumn(InputTableMap::COL_INSTANCE_NAME);
            $criteria->addSelectColumn(InputTableMap::COL_STARTED);
            $criteria->addSelectColumn(InputTableMap::COL_INPUT);
            $criteria->addSelectColumn(InputTableMap::COL_NETWORK);
            $criteria->addSelectColumn(InputTableMap::COL_MUX);
            $criteria->addSelectColumn(InputTableMap::COL_WEIGHT);
        } else {
            $criteria->addSelectColumn($alias . '.uuid');
            $criteria->addSelectColumn($alias . '.instance_name');
            $criteria->addSelectColumn($alias . '.started');
            $criteria->addSelectColumn($alias . '.input');
            $criteria->addSelectColumn($alias . '.network');
            $criteria->addSelectColumn($alias . '.mux');
            $criteria->addSelectColumn($alias . '.weight');
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
        return Propel::getServiceContainer()->getDatabaseMap(InputTableMap::DATABASE_NAME)->getTable(InputTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(InputTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(InputTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new InputTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Input or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Input object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(InputTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Jalle19\StatusManager\Database\Input) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InputTableMap::DATABASE_NAME);
            $criteria->add(InputTableMap::COL_UUID, (array) $values, Criteria::IN);
        }

        $query = InputQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InputTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InputTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the input table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return InputQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Input or Criteria object.
     *
     * @param mixed               $criteria Criteria or Input object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Input object
        }


        // Set the correct dbName
        $query = InputQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // InputTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
InputTableMap::buildTableMap();
