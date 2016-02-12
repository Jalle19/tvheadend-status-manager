<?php

namespace Jalle19\StatusManager\Database\Base;

use \DateTime;
use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Input as ChildInput;
use Jalle19\StatusManager\Database\InputQuery as ChildInputQuery;
use Jalle19\StatusManager\Database\Instance as ChildInstance;
use Jalle19\StatusManager\Database\InstanceQuery as ChildInstanceQuery;
use Jalle19\StatusManager\Database\Subscription as ChildSubscription;
use Jalle19\StatusManager\Database\SubscriptionQuery as ChildSubscriptionQuery;
use Jalle19\StatusManager\Database\Map\InputTableMap;
use Jalle19\StatusManager\Database\Map\SubscriptionTableMap;
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
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'input' table.
 *
 *
 *
* @package    propel.generator.Jalle19.StatusManager.Database.Base
*/
abstract class Input implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Jalle19\\StatusManager\\Database\\Map\\InputTableMap';


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
     * The value for the uuid field.
     *
     * @var        string
     */
    protected $uuid;

    /**
     * The value for the instance_name field.
     *
     * @var        string
     */
    protected $instance_name;

    /**
     * The value for the started field.
     *
     * @var        \DateTime
     */
    protected $started;

    /**
     * The value for the input field.
     *
     * @var        string
     */
    protected $input;

    /**
     * The value for the network field.
     *
     * @var        string
     */
    protected $network;

    /**
     * The value for the mux field.
     *
     * @var        string
     */
    protected $mux;

    /**
     * The value for the weight field.
     *
     * @var        int
     */
    protected $weight;

    /**
     * @var        ChildInstance
     */
    protected $aInstance;

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
     * @var ObjectCollection|ChildSubscription[]
     */
    protected $subscriptionsScheduledForDeletion = null;

    /**
     * Initializes internal state of Jalle19\StatusManager\Database\Base\Input object.
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
     * Compares this with another <code>Input</code> instance.  If
     * <code>obj</code> is an instance of <code>Input</code>, delegates to
     * <code>equals(Input)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Input The current object, for fluid interface
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
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
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
     * Get the [uuid] column value.
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Get the [instance_name] column value.
     *
     * @return string
     */
    public function getInstanceName()
    {
        return $this->instance_name;
    }

    /**
     * Get the [optionally formatted] temporal [started] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getStarted($format = NULL)
    {
        if ($format === null) {
            return $this->started;
        } else {
            return $this->started instanceof \DateTime ? $this->started->format($format) : null;
        }
    }

    /**
     * Get the [input] column value.
     *
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Get the [network] column value.
     *
     * @return string
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * Get the [mux] column value.
     *
     * @return string
     */
    public function getMux()
    {
        return $this->mux;
    }

    /**
     * Get the [weight] column value.
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of [uuid] column.
     *
     * @param string $v new value
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     */
    public function setUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->uuid !== $v) {
            $this->uuid = $v;
            $this->modifiedColumns[InputTableMap::COL_UUID] = true;
        }

        return $this;
    } // setUuid()

    /**
     * Set the value of [instance_name] column.
     *
     * @param string $v new value
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     */
    public function setInstanceName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->instance_name !== $v) {
            $this->instance_name = $v;
            $this->modifiedColumns[InputTableMap::COL_INSTANCE_NAME] = true;
        }

        if ($this->aInstance !== null && $this->aInstance->getName() !== $v) {
            $this->aInstance = null;
        }

        return $this;
    } // setInstanceName()

    /**
     * Sets the value of [started] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     */
    public function setStarted($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->started !== null || $dt !== null) {
            if ($this->started === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->started->format("Y-m-d H:i:s")) {
                $this->started = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InputTableMap::COL_STARTED] = true;
            }
        } // if either are not null

        return $this;
    } // setStarted()

    /**
     * Set the value of [input] column.
     *
     * @param string $v new value
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     */
    public function setInput($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->input !== $v) {
            $this->input = $v;
            $this->modifiedColumns[InputTableMap::COL_INPUT] = true;
        }

        return $this;
    } // setInput()

    /**
     * Set the value of [network] column.
     *
     * @param string $v new value
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     */
    public function setNetwork($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->network !== $v) {
            $this->network = $v;
            $this->modifiedColumns[InputTableMap::COL_NETWORK] = true;
        }

        return $this;
    } // setNetwork()

    /**
     * Set the value of [mux] column.
     *
     * @param string $v new value
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     */
    public function setMux($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mux !== $v) {
            $this->mux = $v;
            $this->modifiedColumns[InputTableMap::COL_MUX] = true;
        }

        return $this;
    } // setMux()

    /**
     * Set the value of [weight] column.
     *
     * @param int $v new value
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     */
    public function setWeight($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->weight !== $v) {
            $this->weight = $v;
            $this->modifiedColumns[InputTableMap::COL_WEIGHT] = true;
        }

        return $this;
    } // setWeight()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : InputTableMap::translateFieldName('Uuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->uuid = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : InputTableMap::translateFieldName('InstanceName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->instance_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : InputTableMap::translateFieldName('Started', TableMap::TYPE_PHPNAME, $indexType)];
            $this->started = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : InputTableMap::translateFieldName('Input', TableMap::TYPE_PHPNAME, $indexType)];
            $this->input = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : InputTableMap::translateFieldName('Network', TableMap::TYPE_PHPNAME, $indexType)];
            $this->network = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : InputTableMap::translateFieldName('Mux', TableMap::TYPE_PHPNAME, $indexType)];
            $this->mux = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : InputTableMap::translateFieldName('Weight', TableMap::TYPE_PHPNAME, $indexType)];
            $this->weight = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = InputTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Jalle19\\StatusManager\\Database\\Input'), 0, $e);
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
        if ($this->aInstance !== null && $this->instance_name !== $this->aInstance->getName()) {
            $this->aInstance = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(InputTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildInputQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aInstance = null;
            $this->collSubscriptions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Input::setDeleted()
     * @see Input::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildInputQuery::create()
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

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
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
                InputTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aInstance !== null) {
                if ($this->aInstance->isModified() || $this->aInstance->isNew()) {
                    $affectedRows += $this->aInstance->save($con);
                }
                $this->setInstance($this->aInstance);
            }

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

            if ($this->subscriptionsScheduledForDeletion !== null) {
                if (!$this->subscriptionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->subscriptionsScheduledForDeletion as $subscription) {
                        // need to save related object because we set the relation to null
                        $subscription->save($con);
                    }
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
        if ($this->isColumnModified(InputTableMap::COL_UUID)) {
            $modifiedColumns[':p' . $index++]  = 'uuid';
        }
        if ($this->isColumnModified(InputTableMap::COL_INSTANCE_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'instance_name';
        }
        if ($this->isColumnModified(InputTableMap::COL_STARTED)) {
            $modifiedColumns[':p' . $index++]  = 'started';
        }
        if ($this->isColumnModified(InputTableMap::COL_INPUT)) {
            $modifiedColumns[':p' . $index++]  = 'input';
        }
        if ($this->isColumnModified(InputTableMap::COL_NETWORK)) {
            $modifiedColumns[':p' . $index++]  = 'network';
        }
        if ($this->isColumnModified(InputTableMap::COL_MUX)) {
            $modifiedColumns[':p' . $index++]  = 'mux';
        }
        if ($this->isColumnModified(InputTableMap::COL_WEIGHT)) {
            $modifiedColumns[':p' . $index++]  = 'weight';
        }

        $sql = sprintf(
            'INSERT INTO input (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'uuid':
                        $stmt->bindValue($identifier, $this->uuid, PDO::PARAM_STR);
                        break;
                    case 'instance_name':
                        $stmt->bindValue($identifier, $this->instance_name, PDO::PARAM_STR);
                        break;
                    case 'started':
                        $stmt->bindValue($identifier, $this->started ? $this->started->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'input':
                        $stmt->bindValue($identifier, $this->input, PDO::PARAM_STR);
                        break;
                    case 'network':
                        $stmt->bindValue($identifier, $this->network, PDO::PARAM_STR);
                        break;
                    case 'mux':
                        $stmt->bindValue($identifier, $this->mux, PDO::PARAM_STR);
                        break;
                    case 'weight':
                        $stmt->bindValue($identifier, $this->weight, PDO::PARAM_INT);
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
        $pos = InputTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getUuid();
                break;
            case 1:
                return $this->getInstanceName();
                break;
            case 2:
                return $this->getStarted();
                break;
            case 3:
                return $this->getInput();
                break;
            case 4:
                return $this->getNetwork();
                break;
            case 5:
                return $this->getMux();
                break;
            case 6:
                return $this->getWeight();
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

        if (isset($alreadyDumpedObjects['Input'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Input'][$this->hashCode()] = true;
        $keys = InputTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getUuid(),
            $keys[1] => $this->getInstanceName(),
            $keys[2] => $this->getStarted(),
            $keys[3] => $this->getInput(),
            $keys[4] => $this->getNetwork(),
            $keys[5] => $this->getMux(),
            $keys[6] => $this->getWeight(),
        );
        if ($result[$keys[2]] instanceof \DateTime) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aInstance) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'instance';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'instance';
                        break;
                    default:
                        $key = 'Instance';
                }

                $result[$key] = $this->aInstance->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\Jalle19\StatusManager\Database\Input
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = InputTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Jalle19\StatusManager\Database\Input
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setUuid($value);
                break;
            case 1:
                $this->setInstanceName($value);
                break;
            case 2:
                $this->setStarted($value);
                break;
            case 3:
                $this->setInput($value);
                break;
            case 4:
                $this->setNetwork($value);
                break;
            case 5:
                $this->setMux($value);
                break;
            case 6:
                $this->setWeight($value);
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
        $keys = InputTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setUuid($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setInstanceName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setStarted($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setInput($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setNetwork($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setMux($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setWeight($arr[$keys[6]]);
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
     * @return $this|\Jalle19\StatusManager\Database\Input The current object, for fluid interface
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
        $criteria = new Criteria(InputTableMap::DATABASE_NAME);

        if ($this->isColumnModified(InputTableMap::COL_UUID)) {
            $criteria->add(InputTableMap::COL_UUID, $this->uuid);
        }
        if ($this->isColumnModified(InputTableMap::COL_INSTANCE_NAME)) {
            $criteria->add(InputTableMap::COL_INSTANCE_NAME, $this->instance_name);
        }
        if ($this->isColumnModified(InputTableMap::COL_STARTED)) {
            $criteria->add(InputTableMap::COL_STARTED, $this->started);
        }
        if ($this->isColumnModified(InputTableMap::COL_INPUT)) {
            $criteria->add(InputTableMap::COL_INPUT, $this->input);
        }
        if ($this->isColumnModified(InputTableMap::COL_NETWORK)) {
            $criteria->add(InputTableMap::COL_NETWORK, $this->network);
        }
        if ($this->isColumnModified(InputTableMap::COL_MUX)) {
            $criteria->add(InputTableMap::COL_MUX, $this->mux);
        }
        if ($this->isColumnModified(InputTableMap::COL_WEIGHT)) {
            $criteria->add(InputTableMap::COL_WEIGHT, $this->weight);
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
        $criteria = ChildInputQuery::create();
        $criteria->add(InputTableMap::COL_UUID, $this->uuid);

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
        $validPk = null !== $this->getUuid();

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
        return $this->getUuid();
    }

    /**
     * Generic method to set the primary key (uuid column).
     *
     * @param       string $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setUuid($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getUuid();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Jalle19\StatusManager\Database\Input (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUuid($this->getUuid());
        $copyObj->setInstanceName($this->getInstanceName());
        $copyObj->setStarted($this->getStarted());
        $copyObj->setInput($this->getInput());
        $copyObj->setNetwork($this->getNetwork());
        $copyObj->setMux($this->getMux());
        $copyObj->setWeight($this->getWeight());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

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
     * @return \Jalle19\StatusManager\Database\Input Clone of current object.
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
     * Declares an association between this object and a ChildInstance object.
     *
     * @param  ChildInstance $v
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
     * @throws PropelException
     */
    public function setInstance(ChildInstance $v = null)
    {
        if ($v === null) {
            $this->setInstanceName(NULL);
        } else {
            $this->setInstanceName($v->getName());
        }

        $this->aInstance = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildInstance object, it will not be re-added.
        if ($v !== null) {
            $v->addInput($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildInstance object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildInstance The associated ChildInstance object.
     * @throws PropelException
     */
    public function getInstance(ConnectionInterface $con = null)
    {
        if ($this->aInstance === null && (($this->instance_name !== "" && $this->instance_name !== null))) {
            $this->aInstance = ChildInstanceQuery::create()->findPk($this->instance_name, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aInstance->addInputs($this);
             */
        }

        return $this->aInstance;
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
        if ('Subscription' == $relationName) {
            return $this->initSubscriptions();
        }
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
     * If this ChildInput is new, it will return
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
        if (null === $this->collSubscriptions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSubscriptions) {
                // return empty collection
                $this->initSubscriptions();
            } else {
                $collSubscriptions = ChildSubscriptionQuery::create(null, $criteria)
                    ->filterByInput($this)
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
     * @return $this|ChildInput The current object (for fluent API support)
     */
    public function setSubscriptions(Collection $subscriptions, ConnectionInterface $con = null)
    {
        /** @var ChildSubscription[] $subscriptionsToDelete */
        $subscriptionsToDelete = $this->getSubscriptions(new Criteria(), $con)->diff($subscriptions);


        $this->subscriptionsScheduledForDeletion = $subscriptionsToDelete;

        foreach ($subscriptionsToDelete as $subscriptionRemoved) {
            $subscriptionRemoved->setInput(null);
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
                ->filterByInput($this)
                ->count($con);
        }

        return count($this->collSubscriptions);
    }

    /**
     * Method called to associate a ChildSubscription object to this object
     * through the ChildSubscription foreign key attribute.
     *
     * @param  ChildSubscription $l ChildSubscription
     * @return $this|\Jalle19\StatusManager\Database\Input The current object (for fluent API support)
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
        $subscription->setInput($this);
    }

    /**
     * @param  ChildSubscription $subscription The ChildSubscription object to remove.
     * @return $this|ChildInput The current object (for fluent API support)
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
            $this->subscriptionsScheduledForDeletion[]= $subscription;
            $subscription->setInput(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Input is new, it will return
     * an empty collection; or if this Input has previously
     * been saved, it will retrieve related Subscriptions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Input.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildSubscription[] List of ChildSubscription objects
     */
    public function getSubscriptionsJoinInstance(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSubscriptionQuery::create(null, $criteria);
        $query->joinWith('Instance', $joinBehavior);

        return $this->getSubscriptions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Input is new, it will return
     * an empty collection; or if this Input has previously
     * been saved, it will retrieve related Subscriptions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Input.
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
     * Otherwise if this Input is new, it will return
     * an empty collection; or if this Input has previously
     * been saved, it will retrieve related Subscriptions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Input.
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
        if (null !== $this->aInstance) {
            $this->aInstance->removeInput($this);
        }
        $this->uuid = null;
        $this->instance_name = null;
        $this->started = null;
        $this->input = null;
        $this->network = null;
        $this->mux = null;
        $this->weight = null;
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
            if ($this->collSubscriptions) {
                foreach ($this->collSubscriptions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collSubscriptions = null;
        $this->aInstance = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(InputTableMap::DEFAULT_STRING_FORMAT);
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
