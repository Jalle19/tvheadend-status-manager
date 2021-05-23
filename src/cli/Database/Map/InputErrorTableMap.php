<?php

namespace Jalle19\StatusManager\Database\Map;

use Jalle19\StatusManager\Database\InputError;
use Jalle19\StatusManager\Database\InputErrorQuery;
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
 * This class defines the structure of the 'input_error' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class InputErrorTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Jalle19.StatusManager.Database.Map.InputErrorTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'tvheadend_status_manager';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'input_error';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Jalle19\\StatusManager\\Database\\InputError';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Jalle19.StatusManager.Database.InputError';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'input_error.id';

    /**
     * the column name for the input_uuid field
     */
    const COL_INPUT_UUID = 'input_error.input_uuid';

    /**
     * the column name for the ber_average field
     */
    const COL_BER_AVERAGE = 'input_error.ber_average';

    /**
     * the column name for the unc_average field
     */
    const COL_UNC_AVERAGE = 'input_error.unc_average';

    /**
     * the column name for the cumulative_te field
     */
    const COL_CUMULATIVE_TE = 'input_error.cumulative_te';

    /**
     * the column name for the cumulative_cc field
     */
    const COL_CUMULATIVE_CC = 'input_error.cumulative_cc';

    /**
     * the column name for the created field
     */
    const COL_CREATED = 'input_error.created';

    /**
     * the column name for the modified field
     */
    const COL_MODIFIED = 'input_error.modified';

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
        self::TYPE_PHPNAME       => array('Id', 'InputUuid', 'BerAverage', 'UncAverage', 'CumulativeTe', 'CumulativeCc', 'Created', 'Modified', ),
        self::TYPE_CAMELNAME     => array('id', 'inputUuid', 'berAverage', 'uncAverage', 'cumulativeTe', 'cumulativeCc', 'created', 'modified', ),
        self::TYPE_COLNAME       => array(InputErrorTableMap::COL_ID, InputErrorTableMap::COL_INPUT_UUID, InputErrorTableMap::COL_BER_AVERAGE, InputErrorTableMap::COL_UNC_AVERAGE, InputErrorTableMap::COL_CUMULATIVE_TE, InputErrorTableMap::COL_CUMULATIVE_CC, InputErrorTableMap::COL_CREATED, InputErrorTableMap::COL_MODIFIED, ),
        self::TYPE_FIELDNAME     => array('id', 'input_uuid', 'ber_average', 'unc_average', 'cumulative_te', 'cumulative_cc', 'created', 'modified', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'InputUuid' => 1, 'BerAverage' => 2, 'UncAverage' => 3, 'CumulativeTe' => 4, 'CumulativeCc' => 5, 'Created' => 6, 'Modified' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'inputUuid' => 1, 'berAverage' => 2, 'uncAverage' => 3, 'cumulativeTe' => 4, 'cumulativeCc' => 5, 'created' => 6, 'modified' => 7, ),
        self::TYPE_COLNAME       => array(InputErrorTableMap::COL_ID => 0, InputErrorTableMap::COL_INPUT_UUID => 1, InputErrorTableMap::COL_BER_AVERAGE => 2, InputErrorTableMap::COL_UNC_AVERAGE => 3, InputErrorTableMap::COL_CUMULATIVE_TE => 4, InputErrorTableMap::COL_CUMULATIVE_CC => 5, InputErrorTableMap::COL_CREATED => 6, InputErrorTableMap::COL_MODIFIED => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'input_uuid' => 1, 'ber_average' => 2, 'unc_average' => 3, 'cumulative_te' => 4, 'cumulative_cc' => 5, 'created' => 6, 'modified' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var string[]
     */
    protected $normalizedColumnNameMap = [

        'Id' => 'ID',
        'InputError.Id' => 'ID',
        'id' => 'ID',
        'inputError.id' => 'ID',
        'InputErrorTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'id' => 'ID',
        'input_error.id' => 'ID',
        'InputUuid' => 'INPUT_UUID',
        'InputError.InputUuid' => 'INPUT_UUID',
        'inputUuid' => 'INPUT_UUID',
        'inputError.inputUuid' => 'INPUT_UUID',
        'InputErrorTableMap::COL_INPUT_UUID' => 'INPUT_UUID',
        'COL_INPUT_UUID' => 'INPUT_UUID',
        'input_uuid' => 'INPUT_UUID',
        'input_error.input_uuid' => 'INPUT_UUID',
        'BerAverage' => 'BER_AVERAGE',
        'InputError.BerAverage' => 'BER_AVERAGE',
        'berAverage' => 'BER_AVERAGE',
        'inputError.berAverage' => 'BER_AVERAGE',
        'InputErrorTableMap::COL_BER_AVERAGE' => 'BER_AVERAGE',
        'COL_BER_AVERAGE' => 'BER_AVERAGE',
        'ber_average' => 'BER_AVERAGE',
        'input_error.ber_average' => 'BER_AVERAGE',
        'UncAverage' => 'UNC_AVERAGE',
        'InputError.UncAverage' => 'UNC_AVERAGE',
        'uncAverage' => 'UNC_AVERAGE',
        'inputError.uncAverage' => 'UNC_AVERAGE',
        'InputErrorTableMap::COL_UNC_AVERAGE' => 'UNC_AVERAGE',
        'COL_UNC_AVERAGE' => 'UNC_AVERAGE',
        'unc_average' => 'UNC_AVERAGE',
        'input_error.unc_average' => 'UNC_AVERAGE',
        'CumulativeTe' => 'CUMULATIVE_TE',
        'InputError.CumulativeTe' => 'CUMULATIVE_TE',
        'cumulativeTe' => 'CUMULATIVE_TE',
        'inputError.cumulativeTe' => 'CUMULATIVE_TE',
        'InputErrorTableMap::COL_CUMULATIVE_TE' => 'CUMULATIVE_TE',
        'COL_CUMULATIVE_TE' => 'CUMULATIVE_TE',
        'cumulative_te' => 'CUMULATIVE_TE',
        'input_error.cumulative_te' => 'CUMULATIVE_TE',
        'CumulativeCc' => 'CUMULATIVE_CC',
        'InputError.CumulativeCc' => 'CUMULATIVE_CC',
        'cumulativeCc' => 'CUMULATIVE_CC',
        'inputError.cumulativeCc' => 'CUMULATIVE_CC',
        'InputErrorTableMap::COL_CUMULATIVE_CC' => 'CUMULATIVE_CC',
        'COL_CUMULATIVE_CC' => 'CUMULATIVE_CC',
        'cumulative_cc' => 'CUMULATIVE_CC',
        'input_error.cumulative_cc' => 'CUMULATIVE_CC',
        'Created' => 'CREATED',
        'InputError.Created' => 'CREATED',
        'created' => 'CREATED',
        'inputError.created' => 'CREATED',
        'InputErrorTableMap::COL_CREATED' => 'CREATED',
        'COL_CREATED' => 'CREATED',
        'created' => 'CREATED',
        'input_error.created' => 'CREATED',
        'Modified' => 'MODIFIED',
        'InputError.Modified' => 'MODIFIED',
        'modified' => 'MODIFIED',
        'inputError.modified' => 'MODIFIED',
        'InputErrorTableMap::COL_MODIFIED' => 'MODIFIED',
        'COL_MODIFIED' => 'MODIFIED',
        'modified' => 'MODIFIED',
        'input_error.modified' => 'MODIFIED',
    ];

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
        $this->setName('input_error');
        $this->setPhpName('InputError');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Jalle19\\StatusManager\\Database\\InputError');
        $this->setPackage('Jalle19.StatusManager.Database');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('input_uuid', 'InputUuid', 'VARCHAR', 'input', 'uuid', true, 255, null);
        $this->addColumn('ber_average', 'BerAverage', 'DOUBLE', true, null, null);
        $this->addColumn('unc_average', 'UncAverage', 'DOUBLE', true, null, null);
        $this->addColumn('cumulative_te', 'CumulativeTe', 'INTEGER', true, null, null);
        $this->addColumn('cumulative_cc', 'CumulativeCc', 'INTEGER', true, null, null);
        $this->addColumn('created', 'Created', 'TIMESTAMP', false, null, null);
        $this->addColumn('modified', 'Modified', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Input', '\\Jalle19\\StatusManager\\Database\\Input', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':input_uuid',
    1 => ':uuid',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created', 'update_column' => 'modified', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

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
        return $withPrefix ? InputErrorTableMap::CLASS_DEFAULT : InputErrorTableMap::OM_CLASS;
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
     * @return array           (InputError object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = InputErrorTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = InputErrorTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + InputErrorTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = InputErrorTableMap::OM_CLASS;
            /** @var InputError $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            InputErrorTableMap::addInstanceToPool($obj, $key);
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
            $key = InputErrorTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = InputErrorTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var InputError $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                InputErrorTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(InputErrorTableMap::COL_ID);
            $criteria->addSelectColumn(InputErrorTableMap::COL_INPUT_UUID);
            $criteria->addSelectColumn(InputErrorTableMap::COL_BER_AVERAGE);
            $criteria->addSelectColumn(InputErrorTableMap::COL_UNC_AVERAGE);
            $criteria->addSelectColumn(InputErrorTableMap::COL_CUMULATIVE_TE);
            $criteria->addSelectColumn(InputErrorTableMap::COL_CUMULATIVE_CC);
            $criteria->addSelectColumn(InputErrorTableMap::COL_CREATED);
            $criteria->addSelectColumn(InputErrorTableMap::COL_MODIFIED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.input_uuid');
            $criteria->addSelectColumn($alias . '.ber_average');
            $criteria->addSelectColumn($alias . '.unc_average');
            $criteria->addSelectColumn($alias . '.cumulative_te');
            $criteria->addSelectColumn($alias . '.cumulative_cc');
            $criteria->addSelectColumn($alias . '.created');
            $criteria->addSelectColumn($alias . '.modified');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria object containing the columns to remove.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function removeSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(InputErrorTableMap::COL_ID);
            $criteria->removeSelectColumn(InputErrorTableMap::COL_INPUT_UUID);
            $criteria->removeSelectColumn(InputErrorTableMap::COL_BER_AVERAGE);
            $criteria->removeSelectColumn(InputErrorTableMap::COL_UNC_AVERAGE);
            $criteria->removeSelectColumn(InputErrorTableMap::COL_CUMULATIVE_TE);
            $criteria->removeSelectColumn(InputErrorTableMap::COL_CUMULATIVE_CC);
            $criteria->removeSelectColumn(InputErrorTableMap::COL_CREATED);
            $criteria->removeSelectColumn(InputErrorTableMap::COL_MODIFIED);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.input_uuid');
            $criteria->removeSelectColumn($alias . '.ber_average');
            $criteria->removeSelectColumn($alias . '.unc_average');
            $criteria->removeSelectColumn($alias . '.cumulative_te');
            $criteria->removeSelectColumn($alias . '.cumulative_cc');
            $criteria->removeSelectColumn($alias . '.created');
            $criteria->removeSelectColumn($alias . '.modified');
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
        return Propel::getServiceContainer()->getDatabaseMap(InputErrorTableMap::DATABASE_NAME)->getTable(InputErrorTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(InputErrorTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(InputErrorTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new InputErrorTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a InputError or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or InputError object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(InputErrorTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Jalle19\StatusManager\Database\InputError) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(InputErrorTableMap::DATABASE_NAME);
            $criteria->add(InputErrorTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = InputErrorQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            InputErrorTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                InputErrorTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the input_error table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return InputErrorQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a InputError or Criteria object.
     *
     * @param mixed               $criteria Criteria or InputError object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputErrorTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from InputError object
        }

        if ($criteria->containsKey(InputErrorTableMap::COL_ID) && $criteria->keyContainsValue(InputErrorTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.InputErrorTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = InputErrorQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // InputErrorTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
InputErrorTableMap::buildTableMap();
