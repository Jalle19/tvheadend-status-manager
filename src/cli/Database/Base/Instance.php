<?php

namespace Jalle19\StatusManager\Database\Base;

use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Channel as ChildChannel;
use Jalle19\StatusManager\Database\ChannelQuery as ChildChannelQuery;
use Jalle19\StatusManager\Database\Connection as ChildConnection;
use Jalle19\StatusManager\Database\ConnectionQuery as ChildConnectionQuery;
use Jalle19\StatusManager\Database\Input as ChildInput;
use Jalle19\StatusManager\Database\InputQuery as ChildInputQuery;
use Jalle19\StatusManager\Database\Instance as ChildInstance;
use Jalle19\StatusManager\Database\InstanceQuery as ChildInstanceQuery;
use Jalle19\StatusManager\Database\Subscription as ChildSubscription;
use Jalle19\StatusManager\Database\SubscriptionQuery as ChildSubscriptionQuery;
use Jalle19\StatusManager\Database\User as ChildUser;
use Jalle19\StatusManager\Database\UserQuery as ChildUserQuery;
use Jalle19\StatusManager\Database\Map\ChannelTableMap;
use Jalle19\StatusManager\Database\Map\ConnectionTableMap;
use Jalle19\StatusManager\Database\Map\InputTableMap;
use Jalle19\StatusManager\Database\Map\InstanceTableMap;
use Jalle19\StatusManager\Database\Map\SubscriptionTableMap;
use Jalle19\StatusManager\Database\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'instance' table.
 *
 *
 *
 * @package    propel.generator.Jalle19.StatusManager.Database.Base
 */
abstract class Instance implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Jalle19\\StatusManager\\Database\\Map\\InstanceTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * @var        ObjectCollection|ChildUser[] Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;
    protected $collUsersPartial;

    /**
     * @var        ObjectCollection|ChildConnection[] Collection to store aggregation of ChildConnection objects.
     */
    protected $collConnections;
    protected $collConnectionsPartial;

    /**
     * @var        ObjectCollection|ChildInput[] Collection to store aggregation of ChildInput objects.
     */
    protected $collInputs;
    protected $collInputsPartial;

    /**
     * @var        ObjectCollection|ChildChannel[] Collection to store aggregation of ChildChannel objects.
     */
    protected $collChannels;
    protected $collChannelsPartial;

    /**
     * @var        ObjectCollection|ChildSubscription[] Collection to store aggregation of ChildSubscription objects.
     */
    protected $collSubscriptions;
    protected $collSubscriptionsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildConnection[]
     */
    protected $connectionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildInput[]
     */
    protected $inputsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildChannel[]
     */
    protected $channelsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildSubscription[]
     */
    protected $subscriptionsScheduledForDeletion = null;

    /**
     * Initializes internal state of Jalle19\StatusManager\Database\Base\Instance object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Instance</code> instance.  If
     * <code>obj</code> is an instance of <code>Instance</code>, delegates to
     * <code>equals(Instance)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of [name] column.
     *
     * @param string $v New value
     * @return $this|\Jalle19\StatusManager\Database\Instance The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[InstanceTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : InstanceTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 1; // 1 = InstanceTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Jalle19\\StatusManager\\Database\\Instance'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InstanceTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildInstanceQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collUsers = null;

            $this->collConnections = null;

            $this->collInputs = null;

            $this->collChannels = null;

            $this->collSubscriptions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Instance::setDeleted()
     * @see Instance::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InstanceTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildInstanceQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InstanceTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                InstanceTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    \Jalle19\StatusManager\Database\UserQuery::create()
                        ->filterByPrimaryKeys($this->usersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->usersScheduledForDeletion = null;
                }
            }

            if ($this->collUsers !== null) {
                foreach ($this->collUsers as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->connectionsScheduledForDeletion !== null) {
                if (!$this->connectionsScheduledForDeletion->isEmpty()) {
                    \Jalle19\StatusManager\Database\ConnectionQuery::create()
                        ->filterByPrimaryKeys($this->connectionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->connectionsScheduledForDeletion = null;
                }
            }

            if ($this->collConnections !== null) {
                foreach ($this->collConnections as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->inputsScheduledForDeletion !== null) {
                if (!$this->inputsScheduledForDeletion->isEmpty()) {
                    \Jalle19\StatusManager\Database\InputQuery::create()
                        ->filterByPrimaryKeys($this->inputsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->inputsScheduledForDeletion = null;
                }
            }

            if ($this->collInputs !== null) {
                foreach ($this->collInputs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->channelsScheduledForDeletion !== null) {
                if (!$this->channelsScheduledForDeletion->isEmpty()) {
                    \Jalle19\StatusManager\Database\ChannelQuery::create()
                        ->filterByPrimaryKeys($this->channelsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->channelsScheduledForDeletion = null;
                }
            }

            if ($this->collChannels !== null) {
                foreach ($this->collChannels as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->subscriptionsScheduledForDeletion !== null) {
                if (!$this->subscriptionsScheduledForDeletion->isEmpty()) {
                    \Jalle19\StatusManager\Database\SubscriptionQuery::create()
                        ->filterByPrimaryKeys($this->subscriptionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->subscriptionsScheduledForDeletion = null;
                }
            }

            if ($this->collSubscriptions !== null) {
                foreach ($this->collSubscriptions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(InstanceTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }

        $sql = sprintf(
            'INSERT INTO instance (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = InstanceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getName();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Instance'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Instance'][$this->hashCode()] = true;
        $keys = InstanceTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collUsers) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'users';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users';
                        break;
                    default:
                        $key = 'Users';
                }

                $result[$key] = $this->collUsers->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collConnections) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'connections';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'connections';
                        break;
                    default:
                        $key = 'Connections';
                }

                $result[$key] = $this->collConnections->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collInputs) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'inputs';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'inputs';
                        break;
                    default:
                        $key = 'Inputs';
                }

                $result[$key] = $this->collInputs->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collChannels) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'channels';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'channels';
                        break;
                    default:
                        $key = 'Channels';
                }

                $result[$key] = $this->collChannels->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSubscriptions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'subscriptions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'subscriptions';
                        break;
                    default:
                        $key = 'Subscriptions';
                }

                $result[$key] = $this->collSubscriptions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Jalle19\StatusManager\Database\Instance
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = InstanceTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Jalle19\StatusManager\Database\Instance
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setName($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = InstanceTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setName($arr[$keys[0]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Jalle19\StatusManager\Database\Instance The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(InstanceTableMap::DATABASE_NAME);

        if ($this->isColumnModified(InstanceTableMap::COL_NAME)) {
            $criteria->add(InstanceTableMap::COL_NAME, $this->name);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildInstanceQuery::create();
        $criteria->add(InstanceTableMap::COL_NAME, $this->name);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getName();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->getName();
    }

    /**
     * Generic method to set the primary key (name column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setName($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getName();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Jalle19\StatusManager\Database\Instance (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUsers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUser($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getConnections() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addConnection($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getInputs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addInput($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getChannels() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChannel($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSubscriptions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSubscription($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Jalle19\StatusManager\Database\Instance Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('User' === $relationName) {
            $this->initUsers();
            return;
        }
        if ('Connection' === $relationName) {
            $this->initConnections();
            return;
        }
        if ('Input' === $relationName) {
            $this->initInputs();
            return;
        }
        if ('Channel' === $relationName) {
            $this->initChannels();
            return;
        }
        if ('Subscription' === $relationName) {
            $this->initSubscriptions();
            return;
        }
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsers collection loaded partially.
     */
    public function resetPartialUsers($v = true)
    {
        $this->collUsersPartial = $v;
    }

    /**
     * Initializes the collUsers collection.
     *
     * By default this just sets the collUsers collection to an empty array (like clearcollUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsers($overrideExisting = true)
    {
        if (null !== $this->collUsers && !$overrideExisting) {
            return;
        }

        $collectionClassName = UserTableMap::getTableMap()->getCollectionClassName();

        $this->collUsers = new $collectionClassName;
        $this->collUsers->setModel('\Jalle19\StatusManager\Database\User');
    }

    /**
     * Gets an array of ChildUser objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInstance is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     * @throws PropelException
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                } else {
                    $collectionClassName = UserTableMap::getTableMap()->getCollectionClassName();

                    $collUsers = new $collectionClassName;
                    $collUsers->setModel('\Jalle19\StatusManager\Database\User');

                    return $collUsers;
                }
            } else {
                $collUsers = ChildUserQuery::create(null, $criteria)
                    ->filterByInstance($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersPartial && count($collUsers)) {
                        $this->initUsers(false);

                        foreach ($collUsers as $obj) {
                            if (false == $this->collUsers->contains($obj)) {
                                $this->collUsers->append($obj);
                            }
                        }

                        $this->collUsersPartial = true;
                    }

                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    foreach ($this->collUsers as $obj) {
                        if ($obj->isNew()) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of ChildUser objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $users A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        /** @var ChildUser[] $usersToDelete */
        $usersToDelete = $this->getUsers(new Criteria(), $con)->diff($users);


        $this->usersScheduledForDeletion = $usersToDelete;

        foreach ($usersToDelete as $userRemoved) {
            $userRemoved->setInstance(null);
        }

        $this->collUsers = null;
        foreach ($users as $user) {
            $this->addUser($user);
        }

        $this->collUsers = $users;
        $this->collUsersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related User objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related User objects.
     * @throws PropelException
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsers());
            }

            $query = ChildUserQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInstance($this)
                ->count($con);
        }

        return count($this->collUsers);
    }

    /**
     * Method called to associate a ChildUser object to this object
     * through the ChildUser foreign key attribute.
     *
     * @param  ChildUser $l ChildUser
     * @return $this|\Jalle19\StatusManager\Database\Instance The current object (for fluent API support)
     */
    public function addUser(ChildUser $l)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
            $this->collUsersPartial = true;
        }

        if (!$this->collUsers->contains($l)) {
            $this->doAddUser($l);

            if ($this->usersScheduledForDeletion and $this->usersScheduledForDeletion->contains($l)) {
                $this->usersScheduledForDeletion->remove($this->usersScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUser $user The ChildUser object to add.
     */
    protected function doAddUser(ChildUser $user)
    {
        $this->collUsers[]= $user;
        $user->setInstance($this);
    }

    /**
     * @param  ChildUser $user The ChildUser object to remove.
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) {
            $pos = $this->collUsers->search($user);
            $this->collUsers->remove($pos);
            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }
            $this->usersScheduledForDeletion[]= clone $user;
            $user->setInstance(null);
        }

        return $this;
    }

    /**
     * Clears out the collConnections collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addConnections()
     */
    public function clearConnections()
    {
        $this->collConnections = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collConnections collection loaded partially.
     */
    public function resetPartialConnections($v = true)
    {
        $this->collConnectionsPartial = $v;
    }

    /**
     * Initializes the collConnections collection.
     *
     * By default this just sets the collConnections collection to an empty array (like clearcollConnections());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initConnections($overrideExisting = true)
    {
        if (null !== $this->collConnections && !$overrideExisting) {
            return;
        }

        $collectionClassName = ConnectionTableMap::getTableMap()->getCollectionClassName();

        $this->collConnections = new $collectionClassName;
        $this->collConnections->setModel('\Jalle19\StatusManager\Database\Connection');
    }

    /**
     * Gets an array of ChildConnection objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInstance is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildConnection[] List of ChildConnection objects
     * @throws PropelException
     */
    public function getConnections(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collConnectionsPartial && !$this->isNew();
        if (null === $this->collConnections || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collConnections) {
                    $this->initConnections();
                } else {
                    $collectionClassName = ConnectionTableMap::getTableMap()->getCollectionClassName();

                    $collConnections = new $collectionClassName;
                    $collConnections->setModel('\Jalle19\StatusManager\Database\Connection');

                    return $collConnections;
                }
            } else {
                $collConnections = ChildConnectionQuery::create(null, $criteria)
                    ->filterByInstance($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collConnectionsPartial && count($collConnections)) {
                        $this->initConnections(false);

                        foreach ($collConnections as $obj) {
                            if (false == $this->collConnections->contains($obj)) {
                                $this->collConnections->append($obj);
                            }
                        }

                        $this->collConnectionsPartial = true;
                    }

                    return $collConnections;
                }

                if ($partial && $this->collConnections) {
                    foreach ($this->collConnections as $obj) {
                        if ($obj->isNew()) {
                            $collConnections[] = $obj;
                        }
                    }
                }

                $this->collConnections = $collConnections;
                $this->collConnectionsPartial = false;
            }
        }

        return $this->collConnections;
    }

    /**
     * Sets a collection of ChildConnection objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $connections A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function setConnections(Collection $connections, ConnectionInterface $con = null)
    {
        /** @var ChildConnection[] $connectionsToDelete */
        $connectionsToDelete = $this->getConnections(new Criteria(), $con)->diff($connections);


        $this->connectionsScheduledForDeletion = $connectionsToDelete;

        foreach ($connectionsToDelete as $connectionRemoved) {
            $connectionRemoved->setInstance(null);
        }

        $this->collConnections = null;
        foreach ($connections as $connection) {
            $this->addConnection($connection);
        }

        $this->collConnections = $connections;
        $this->collConnectionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Connection objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Connection objects.
     * @throws PropelException
     */
    public function countConnections(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collConnectionsPartial && !$this->isNew();
        if (null === $this->collConnections || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collConnections) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getConnections());
            }

            $query = ChildConnectionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInstance($this)
                ->count($con);
        }

        return count($this->collConnections);
    }

    /**
     * Method called to associate a ChildConnection object to this object
     * through the ChildConnection foreign key attribute.
     *
     * @param  ChildConnection $l ChildConnection
     * @return $this|\Jalle19\StatusManager\Database\Instance The current object (for fluent API support)
     */
    public function addConnection(ChildConnection $l)
    {
        if ($this->collConnections === null) {
            $this->initConnections();
            $this->collConnectionsPartial = true;
        }

        if (!$this->collConnections->contains($l)) {
            $this->doAddConnection($l);

            if ($this->connectionsScheduledForDeletion and $this->connectionsScheduledForDeletion->contains($l)) {
                $this->connectionsScheduledForDeletion->remove($this->connectionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildConnection $connection The ChildConnection object to add.
     */
    protected function doAddConnection(ChildConnection $connection)
    {
        $this->collConnections[]= $connection;
        $connection->setInstance($this);
    }

    /**
     * @param  ChildConnection $connection The ChildConnection object to remove.
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function removeConnection(ChildConnection $connection)
    {
        if ($this->getConnections()->contains($connection)) {
            $pos = $this->collConnections->search($connection);
            $this->collConnections->remove($pos);
            if (null === $this->connectionsScheduledForDeletion) {
                $this->connectionsScheduledForDeletion = clone $this->collConnections;
                $this->connectionsScheduledForDeletion->clear();
            }
            $this->connectionsScheduledForDeletion[]= clone $connection;
            $connection->setInstance(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Instance is new, it will return
     * an empty collection; or if this Instance has previously
     * been saved, it will retrieve related Connections from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Instance.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildConnection[] List of ChildConnection objects
     */
    public function getConnectionsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildConnectionQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getConnections($query, $con);
    }

    /**
     * Clears out the collInputs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addInputs()
     */
    public function clearInputs()
    {
        $this->collInputs = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collInputs collection loaded partially.
     */
    public function resetPartialInputs($v = true)
    {
        $this->collInputsPartial = $v;
    }

    /**
     * Initializes the collInputs collection.
     *
     * By default this just sets the collInputs collection to an empty array (like clearcollInputs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initInputs($overrideExisting = true)
    {
        if (null !== $this->collInputs && !$overrideExisting) {
            return;
        }

        $collectionClassName = InputTableMap::getTableMap()->getCollectionClassName();

        $this->collInputs = new $collectionClassName;
        $this->collInputs->setModel('\Jalle19\StatusManager\Database\Input');
    }

    /**
     * Gets an array of ChildInput objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInstance is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildInput[] List of ChildInput objects
     * @throws PropelException
     */
    public function getInputs(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collInputsPartial && !$this->isNew();
        if (null === $this->collInputs || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collInputs) {
                    $this->initInputs();
                } else {
                    $collectionClassName = InputTableMap::getTableMap()->getCollectionClassName();

                    $collInputs = new $collectionClassName;
                    $collInputs->setModel('\Jalle19\StatusManager\Database\Input');

                    return $collInputs;
                }
            } else {
                $collInputs = ChildInputQuery::create(null, $criteria)
                    ->filterByInstance($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collInputsPartial && count($collInputs)) {
                        $this->initInputs(false);

                        foreach ($collInputs as $obj) {
                            if (false == $this->collInputs->contains($obj)) {
                                $this->collInputs->append($obj);
                            }
                        }

                        $this->collInputsPartial = true;
                    }

                    return $collInputs;
                }

                if ($partial && $this->collInputs) {
                    foreach ($this->collInputs as $obj) {
                        if ($obj->isNew()) {
                            $collInputs[] = $obj;
                        }
                    }
                }

                $this->collInputs = $collInputs;
                $this->collInputsPartial = false;
            }
        }

        return $this->collInputs;
    }

    /**
     * Sets a collection of ChildInput objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $inputs A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function setInputs(Collection $inputs, ConnectionInterface $con = null)
    {
        /** @var ChildInput[] $inputsToDelete */
        $inputsToDelete = $this->getInputs(new Criteria(), $con)->diff($inputs);


        $this->inputsScheduledForDeletion = $inputsToDelete;

        foreach ($inputsToDelete as $inputRemoved) {
            $inputRemoved->setInstance(null);
        }

        $this->collInputs = null;
        foreach ($inputs as $input) {
            $this->addInput($input);
        }

        $this->collInputs = $inputs;
        $this->collInputsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Input objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Input objects.
     * @throws PropelException
     */
    public function countInputs(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collInputsPartial && !$this->isNew();
        if (null === $this->collInputs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collInputs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getInputs());
            }

            $query = ChildInputQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInstance($this)
                ->count($con);
        }

        return count($this->collInputs);
    }

    /**
     * Method called to associate a ChildInput object to this object
     * through the ChildInput foreign key attribute.
     *
     * @param  ChildInput $l ChildInput
     * @return $this|\Jalle19\StatusManager\Database\Instance The current object (for fluent API support)
     */
    public function addInput(ChildInput $l)
    {
        if ($this->collInputs === null) {
            $this->initInputs();
            $this->collInputsPartial = true;
        }

        if (!$this->collInputs->contains($l)) {
            $this->doAddInput($l);

            if ($this->inputsScheduledForDeletion and $this->inputsScheduledForDeletion->contains($l)) {
                $this->inputsScheduledForDeletion->remove($this->inputsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildInput $input The ChildInput object to add.
     */
    protected function doAddInput(ChildInput $input)
    {
        $this->collInputs[]= $input;
        $input->setInstance($this);
    }

    /**
     * @param  ChildInput $input The ChildInput object to remove.
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function removeInput(ChildInput $input)
    {
        if ($this->getInputs()->contains($input)) {
            $pos = $this->collInputs->search($input);
            $this->collInputs->remove($pos);
            if (null === $this->inputsScheduledForDeletion) {
                $this->inputsScheduledForDeletion = clone $this->collInputs;
                $this->inputsScheduledForDeletion->clear();
            }
            $this->inputsScheduledForDeletion[]= clone $input;
            $input->setInstance(null);
        }

        return $this;
    }

    /**
     * Clears out the collChannels collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChannels()
     */
    public function clearChannels()
    {
        $this->collChannels = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChannels collection loaded partially.
     */
    public function resetPartialChannels($v = true)
    {
        $this->collChannelsPartial = $v;
    }

    /**
     * Initializes the collChannels collection.
     *
     * By default this just sets the collChannels collection to an empty array (like clearcollChannels());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChannels($overrideExisting = true)
    {
        if (null !== $this->collChannels && !$overrideExisting) {
            return;
        }

        $collectionClassName = ChannelTableMap::getTableMap()->getCollectionClassName();

        $this->collChannels = new $collectionClassName;
        $this->collChannels->setModel('\Jalle19\StatusManager\Database\Channel');
    }

    /**
     * Gets an array of ChildChannel objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInstance is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildChannel[] List of ChildChannel objects
     * @throws PropelException
     */
    public function getChannels(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChannelsPartial && !$this->isNew();
        if (null === $this->collChannels || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collChannels) {
                    $this->initChannels();
                } else {
                    $collectionClassName = ChannelTableMap::getTableMap()->getCollectionClassName();

                    $collChannels = new $collectionClassName;
                    $collChannels->setModel('\Jalle19\StatusManager\Database\Channel');

                    return $collChannels;
                }
            } else {
                $collChannels = ChildChannelQuery::create(null, $criteria)
                    ->filterByInstance($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChannelsPartial && count($collChannels)) {
                        $this->initChannels(false);

                        foreach ($collChannels as $obj) {
                            if (false == $this->collChannels->contains($obj)) {
                                $this->collChannels->append($obj);
                            }
                        }

                        $this->collChannelsPartial = true;
                    }

                    return $collChannels;
                }

                if ($partial && $this->collChannels) {
                    foreach ($this->collChannels as $obj) {
                        if ($obj->isNew()) {
                            $collChannels[] = $obj;
                        }
                    }
                }

                $this->collChannels = $collChannels;
                $this->collChannelsPartial = false;
            }
        }

        return $this->collChannels;
    }

    /**
     * Sets a collection of ChildChannel objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $channels A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function setChannels(Collection $channels, ConnectionInterface $con = null)
    {
        /** @var ChildChannel[] $channelsToDelete */
        $channelsToDelete = $this->getChannels(new Criteria(), $con)->diff($channels);


        $this->channelsScheduledForDeletion = $channelsToDelete;

        foreach ($channelsToDelete as $channelRemoved) {
            $channelRemoved->setInstance(null);
        }

        $this->collChannels = null;
        foreach ($channels as $channel) {
            $this->addChannel($channel);
        }

        $this->collChannels = $channels;
        $this->collChannelsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Channel objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Channel objects.
     * @throws PropelException
     */
    public function countChannels(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChannelsPartial && !$this->isNew();
        if (null === $this->collChannels || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChannels) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChannels());
            }

            $query = ChildChannelQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInstance($this)
                ->count($con);
        }

        return count($this->collChannels);
    }

    /**
     * Method called to associate a ChildChannel object to this object
     * through the ChildChannel foreign key attribute.
     *
     * @param  ChildChannel $l ChildChannel
     * @return $this|\Jalle19\StatusManager\Database\Instance The current object (for fluent API support)
     */
    public function addChannel(ChildChannel $l)
    {
        if ($this->collChannels === null) {
            $this->initChannels();
            $this->collChannelsPartial = true;
        }

        if (!$this->collChannels->contains($l)) {
            $this->doAddChannel($l);

            if ($this->channelsScheduledForDeletion and $this->channelsScheduledForDeletion->contains($l)) {
                $this->channelsScheduledForDeletion->remove($this->channelsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildChannel $channel The ChildChannel object to add.
     */
    protected function doAddChannel(ChildChannel $channel)
    {
        $this->collChannels[]= $channel;
        $channel->setInstance($this);
    }

    /**
     * @param  ChildChannel $channel The ChildChannel object to remove.
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function removeChannel(ChildChannel $channel)
    {
        if ($this->getChannels()->contains($channel)) {
            $pos = $this->collChannels->search($channel);
            $this->collChannels->remove($pos);
            if (null === $this->channelsScheduledForDeletion) {
                $this->channelsScheduledForDeletion = clone $this->collChannels;
                $this->channelsScheduledForDeletion->clear();
            }
            $this->channelsScheduledForDeletion[]= clone $channel;
            $channel->setInstance(null);
        }

        return $this;
    }

    /**
     * Clears out the collSubscriptions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSubscriptions()
     */
    public function clearSubscriptions()
    {
        $this->collSubscriptions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSubscriptions collection loaded partially.
     */
    public function resetPartialSubscriptions($v = true)
    {
        $this->collSubscriptionsPartial = $v;
    }

    /**
     * Initializes the collSubscriptions collection.
     *
     * By default this just sets the collSubscriptions collection to an empty array (like clearcollSubscriptions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSubscriptions($overrideExisting = true)
    {
        if (null !== $this->collSubscriptions && !$overrideExisting) {
            return;
        }

        $collectionClassName = SubscriptionTableMap::getTableMap()->getCollectionClassName();

        $this->collSubscriptions = new $collectionClassName;
        $this->collSubscriptions->setModel('\Jalle19\StatusManager\Database\Subscription');
    }

    /**
     * Gets an array of ChildSubscription objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildInstance is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildSubscription[] List of ChildSubscription objects
     * @throws PropelException
     */
    public function getSubscriptions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSubscriptionsPartial && !$this->isNew();
        if (null === $this->collSubscriptions || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collSubscriptions) {
                    $this->initSubscriptions();
                } else {
                    $collectionClassName = SubscriptionTableMap::getTableMap()->getCollectionClassName();

                    $collSubscriptions = new $collectionClassName;
                    $collSubscriptions->setModel('\Jalle19\StatusManager\Database\Subscription');

                    return $collSubscriptions;
                }
            } else {
                $collSubscriptions = ChildSubscriptionQuery::create(null, $criteria)
                    ->filterByInstance($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSubscriptionsPartial && count($collSubscriptions)) {
                        $this->initSubscriptions(false);

                        foreach ($collSubscriptions as $obj) {
                            if (false == $this->collSubscriptions->contains($obj)) {
                                $this->collSubscriptions->append($obj);
                            }
                        }

                        $this->collSubscriptionsPartial = true;
                    }

                    return $collSubscriptions;
                }

                if ($partial && $this->collSubscriptions) {
                    foreach ($this->collSubscriptions as $obj) {
                        if ($obj->isNew()) {
                            $collSubscriptions[] = $obj;
                        }
                    }
                }

                $this->collSubscriptions = $collSubscriptions;
                $this->collSubscriptionsPartial = false;
            }
        }

        return $this->collSubscriptions;
    }

    /**
     * Sets a collection of ChildSubscription objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $subscriptions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function setSubscriptions(Collection $subscriptions, ConnectionInterface $con = null)
    {
        /** @var ChildSubscription[] $subscriptionsToDelete */
        $subscriptionsToDelete = $this->getSubscriptions(new Criteria(), $con)->diff($subscriptions);


        $this->subscriptionsScheduledForDeletion = $subscriptionsToDelete;

        foreach ($subscriptionsToDelete as $subscriptionRemoved) {
            $subscriptionRemoved->setInstance(null);
        }

        $this->collSubscriptions = null;
        foreach ($subscriptions as $subscription) {
            $this->addSubscription($subscription);
        }

        $this->collSubscriptions = $subscriptions;
        $this->collSubscriptionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Subscription objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Subscription objects.
     * @throws PropelException
     */
    public function countSubscriptions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSubscriptionsPartial && !$this->isNew();
        if (null === $this->collSubscriptions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSubscriptions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSubscriptions());
            }

            $query = ChildSubscriptionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByInstance($this)
                ->count($con);
        }

        return count($this->collSubscriptions);
    }

    /**
     * Method called to associate a ChildSubscription object to this object
     * through the ChildSubscription foreign key attribute.
     *
     * @param  ChildSubscription $l ChildSubscription
     * @return $this|\Jalle19\StatusManager\Database\Instance The current object (for fluent API support)
     */
    public function addSubscription(ChildSubscription $l)
    {
        if ($this->collSubscriptions === null) {
            $this->initSubscriptions();
            $this->collSubscriptionsPartial = true;
        }

        if (!$this->collSubscriptions->contains($l)) {
            $this->doAddSubscription($l);

            if ($this->subscriptionsScheduledForDeletion and $this->subscriptionsScheduledForDeletion->contains($l)) {
                $this->subscriptionsScheduledForDeletion->remove($this->subscriptionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildSubscription $subscription The ChildSubscription object to add.
     */
    protected function doAddSubscription(ChildSubscription $subscription)
    {
        $this->collSubscriptions[]= $subscription;
        $subscription->setInstance($this);
    }

    /**
     * @param  ChildSubscription $subscription The ChildSubscription object to remove.
     * @return $this|ChildInstance The current object (for fluent API support)
     */
    public function removeSubscription(ChildSubscription $subscription)
    {
        if ($this->getSubscriptions()->contains($subscription)) {
            $pos = $this->collSubscriptions->search($subscription);
            $this->collSubscriptions->remove($pos);
            if (null === $this->subscriptionsScheduledForDeletion) {
                $this->subscriptionsScheduledForDeletion = clone $this->collSubscriptions;
                $this->subscriptionsScheduledForDeletion->clear();
            }
            $this->subscriptionsScheduledForDeletion[]= clone $subscription;
            $subscription->setInstance(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Instance is new, it will return
     * an empty collection; or if this Instance has previously
     * been saved, it will retrieve related Subscriptions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Instance.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSubscription[] List of ChildSubscription objects
     */
    public function getSubscriptionsJoinInput(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubscriptionQuery::create(null, $criteria);
        $query->joinWith('Input', $joinBehavior);

        return $this->getSubscriptions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Instance is new, it will return
     * an empty collection; or if this Instance has previously
     * been saved, it will retrieve related Subscriptions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Instance.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSubscription[] List of ChildSubscription objects
     */
    public function getSubscriptionsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubscriptionQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getSubscriptions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Instance is new, it will return
     * an empty collection; or if this Instance has previously
     * been saved, it will retrieve related Subscriptions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Instance.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSubscription[] List of ChildSubscription objects
     */
    public function getSubscriptionsJoinChannel(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubscriptionQuery::create(null, $criteria);
        $query->joinWith('Channel', $joinBehavior);

        return $this->getSubscriptions($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->name = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collConnections) {
                foreach ($this->collConnections as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collInputs) {
                foreach ($this->collInputs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collChannels) {
                foreach ($this->collChannels as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSubscriptions) {
                foreach ($this->collSubscriptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUsers = null;
        $this->collConnections = null;
        $this->collInputs = null;
        $this->collChannels = null;
        $this->collSubscriptions = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(InstanceTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
