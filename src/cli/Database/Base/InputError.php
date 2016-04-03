<?php

namespace Jalle19\StatusManager\Database\Base;

use \DateTime;
use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Input as ChildInput;
use Jalle19\StatusManager\Database\InputError as ChildInputError;
use Jalle19\StatusManager\Database\InputErrorQuery as ChildInputErrorQuery;
use Jalle19\StatusManager\Database\InputQuery as ChildInputQuery;
use Jalle19\StatusManager\Database\Map\InputErrorTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'input_error' table.
 *
 *
 *
* @package    propel.generator.Jalle19.StatusManager.Database.Base
*/
abstract class InputError implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Jalle19\\StatusManager\\Database\\Map\\InputErrorTableMap';


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
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the input_uuid field.
     *
     * @var        string
     */
    protected $input_uuid;

    /**
     * The value for the ber_average field.
     *
     * @var        double
     */
    protected $ber_average;

    /**
     * The value for the unc_average field.
     *
     * @var        double
     */
    protected $unc_average;

    /**
     * The value for the cumulative_te field.
     *
     * @var        int
     */
    protected $cumulative_te;

    /**
     * The value for the cumulative_cc field.
     *
     * @var        int
     */
    protected $cumulative_cc;

    /**
     * The value for the created field.
     *
     * @var        \DateTime
     */
    protected $created;

    /**
     * The value for the modified field.
     *
     * @var        \DateTime
     */
    protected $modified;

    /**
     * @var        ChildInput
     */
    protected $aInput;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of Jalle19\StatusManager\Database\Base\InputError object.
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
     * Compares this with another <code>InputError</code> instance.  If
     * <code>obj</code> is an instance of <code>InputError</code>, delegates to
     * <code>equals(InputError)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|InputError The current object, for fluid interface
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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [input_uuid] column value.
     *
     * @return string
     */
    public function getInputUuid()
    {
        return $this->input_uuid;
    }

    /**
     * Get the [ber_average] column value.
     *
     * @return double
     */
    public function getBerAverage()
    {
        return $this->ber_average;
    }

    /**
     * Get the [unc_average] column value.
     *
     * @return double
     */
    public function getUncAverage()
    {
        return $this->unc_average;
    }

    /**
     * Get the [cumulative_te] column value.
     *
     * @return int
     */
    public function getCumulativeTe()
    {
        return $this->cumulative_te;
    }

    /**
     * Get the [cumulative_cc] column value.
     *
     * @return int
     */
    public function getCumulativeCc()
    {
        return $this->cumulative_cc;
    }

    /**
     * Get the [optionally formatted] temporal [created] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreated($format = NULL)
    {
        if ($format === null) {
            return $this->created;
        } else {
            return $this->created instanceof \DateTime ? $this->created->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [modified] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getModified($format = NULL)
    {
        if ($format === null) {
            return $this->modified;
        } else {
            return $this->modified instanceof \DateTime ? $this->modified->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[InputErrorTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [input_uuid] column.
     *
     * @param string $v new value
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setInputUuid($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->input_uuid !== $v) {
            $this->input_uuid = $v;
            $this->modifiedColumns[InputErrorTableMap::COL_INPUT_UUID] = true;
        }

        if ($this->aInput !== null && $this->aInput->getUuid() !== $v) {
            $this->aInput = null;
        }

        return $this;
    } // setInputUuid()

    /**
     * Set the value of [ber_average] column.
     *
     * @param double $v new value
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setBerAverage($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->ber_average !== $v) {
            $this->ber_average = $v;
            $this->modifiedColumns[InputErrorTableMap::COL_BER_AVERAGE] = true;
        }

        return $this;
    } // setBerAverage()

    /**
     * Set the value of [unc_average] column.
     *
     * @param double $v new value
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setUncAverage($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->unc_average !== $v) {
            $this->unc_average = $v;
            $this->modifiedColumns[InputErrorTableMap::COL_UNC_AVERAGE] = true;
        }

        return $this;
    } // setUncAverage()

    /**
     * Set the value of [cumulative_te] column.
     *
     * @param int $v new value
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setCumulativeTe($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cumulative_te !== $v) {
            $this->cumulative_te = $v;
            $this->modifiedColumns[InputErrorTableMap::COL_CUMULATIVE_TE] = true;
        }

        return $this;
    } // setCumulativeTe()

    /**
     * Set the value of [cumulative_cc] column.
     *
     * @param int $v new value
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setCumulativeCc($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->cumulative_cc !== $v) {
            $this->cumulative_cc = $v;
            $this->modifiedColumns[InputErrorTableMap::COL_CUMULATIVE_CC] = true;
        }

        return $this;
    } // setCumulativeCc()

    /**
     * Sets the value of [created] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setCreated($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created !== null || $dt !== null) {
            if ($this->created === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created->format("Y-m-d H:i:s")) {
                $this->created = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InputErrorTableMap::COL_CREATED] = true;
            }
        } // if either are not null

        return $this;
    } // setCreated()

    /**
     * Sets the value of [modified] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     */
    public function setModified($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->modified !== null || $dt !== null) {
            if ($this->modified === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->modified->format("Y-m-d H:i:s")) {
                $this->modified = $dt === null ? null : clone $dt;
                $this->modifiedColumns[InputErrorTableMap::COL_MODIFIED] = true;
            }
        } // if either are not null

        return $this;
    } // setModified()

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : InputErrorTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : InputErrorTableMap::translateFieldName('InputUuid', TableMap::TYPE_PHPNAME, $indexType)];
            $this->input_uuid = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : InputErrorTableMap::translateFieldName('BerAverage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ber_average = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : InputErrorTableMap::translateFieldName('UncAverage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->unc_average = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : InputErrorTableMap::translateFieldName('CumulativeTe', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cumulative_te = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : InputErrorTableMap::translateFieldName('CumulativeCc', TableMap::TYPE_PHPNAME, $indexType)];
            $this->cumulative_cc = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : InputErrorTableMap::translateFieldName('Created', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : InputErrorTableMap::translateFieldName('Modified', TableMap::TYPE_PHPNAME, $indexType)];
            $this->modified = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = InputErrorTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Jalle19\\StatusManager\\Database\\InputError'), 0, $e);
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
        if ($this->aInput !== null && $this->input_uuid !== $this->aInput->getUuid()) {
            $this->aInput = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(InputErrorTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildInputErrorQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aInput = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see InputError::setDeleted()
     * @see InputError::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputErrorTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildInputErrorQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(InputErrorTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(InputErrorTableMap::COL_CREATED)) {
                    $this->setCreated(time());
                }
                if (!$this->isColumnModified(InputErrorTableMap::COL_MODIFIED)) {
                    $this->setModified(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(InputErrorTableMap::COL_MODIFIED)) {
                    $this->setModified(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                InputErrorTableMap::addInstanceToPool($this);
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

            if ($this->aInput !== null) {
                if ($this->aInput->isModified() || $this->aInput->isNew()) {
                    $affectedRows += $this->aInput->save($con);
                }
                $this->setInput($this->aInput);
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

        $this->modifiedColumns[InputErrorTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . InputErrorTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(InputErrorTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_INPUT_UUID)) {
            $modifiedColumns[':p' . $index++]  = 'input_uuid';
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_BER_AVERAGE)) {
            $modifiedColumns[':p' . $index++]  = 'ber_average';
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_UNC_AVERAGE)) {
            $modifiedColumns[':p' . $index++]  = 'unc_average';
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_CUMULATIVE_TE)) {
            $modifiedColumns[':p' . $index++]  = 'cumulative_te';
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_CUMULATIVE_CC)) {
            $modifiedColumns[':p' . $index++]  = 'cumulative_cc';
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_CREATED)) {
            $modifiedColumns[':p' . $index++]  = 'created';
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_MODIFIED)) {
            $modifiedColumns[':p' . $index++]  = 'modified';
        }

        $sql = sprintf(
            'INSERT INTO input_error (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'input_uuid':
                        $stmt->bindValue($identifier, $this->input_uuid, PDO::PARAM_STR);
                        break;
                    case 'ber_average':
                        $stmt->bindValue($identifier, $this->ber_average, PDO::PARAM_STR);
                        break;
                    case 'unc_average':
                        $stmt->bindValue($identifier, $this->unc_average, PDO::PARAM_STR);
                        break;
                    case 'cumulative_te':
                        $stmt->bindValue($identifier, $this->cumulative_te, PDO::PARAM_INT);
                        break;
                    case 'cumulative_cc':
                        $stmt->bindValue($identifier, $this->cumulative_cc, PDO::PARAM_INT);
                        break;
                    case 'created':
                        $stmt->bindValue($identifier, $this->created ? $this->created->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'modified':
                        $stmt->bindValue($identifier, $this->modified ? $this->modified->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

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
        $pos = InputErrorTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getId();
                break;
            case 1:
                return $this->getInputUuid();
                break;
            case 2:
                return $this->getBerAverage();
                break;
            case 3:
                return $this->getUncAverage();
                break;
            case 4:
                return $this->getCumulativeTe();
                break;
            case 5:
                return $this->getCumulativeCc();
                break;
            case 6:
                return $this->getCreated();
                break;
            case 7:
                return $this->getModified();
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

        if (isset($alreadyDumpedObjects['InputError'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['InputError'][$this->hashCode()] = true;
        $keys = InputErrorTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getInputUuid(),
            $keys[2] => $this->getBerAverage(),
            $keys[3] => $this->getUncAverage(),
            $keys[4] => $this->getCumulativeTe(),
            $keys[5] => $this->getCumulativeCc(),
            $keys[6] => $this->getCreated(),
            $keys[7] => $this->getModified(),
        );
        if ($result[$keys[6]] instanceof \DateTime) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aInput) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'input';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'input';
                        break;
                    default:
                        $key = 'Input';
                }

                $result[$key] = $this->aInput->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\Jalle19\StatusManager\Database\InputError
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = InputErrorTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Jalle19\StatusManager\Database\InputError
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setInputUuid($value);
                break;
            case 2:
                $this->setBerAverage($value);
                break;
            case 3:
                $this->setUncAverage($value);
                break;
            case 4:
                $this->setCumulativeTe($value);
                break;
            case 5:
                $this->setCumulativeCc($value);
                break;
            case 6:
                $this->setCreated($value);
                break;
            case 7:
                $this->setModified($value);
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
        $keys = InputErrorTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setInputUuid($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setBerAverage($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUncAverage($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setCumulativeTe($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCumulativeCc($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCreated($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setModified($arr[$keys[7]]);
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
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object, for fluid interface
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
        $criteria = new Criteria(InputErrorTableMap::DATABASE_NAME);

        if ($this->isColumnModified(InputErrorTableMap::COL_ID)) {
            $criteria->add(InputErrorTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_INPUT_UUID)) {
            $criteria->add(InputErrorTableMap::COL_INPUT_UUID, $this->input_uuid);
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_BER_AVERAGE)) {
            $criteria->add(InputErrorTableMap::COL_BER_AVERAGE, $this->ber_average);
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_UNC_AVERAGE)) {
            $criteria->add(InputErrorTableMap::COL_UNC_AVERAGE, $this->unc_average);
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_CUMULATIVE_TE)) {
            $criteria->add(InputErrorTableMap::COL_CUMULATIVE_TE, $this->cumulative_te);
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_CUMULATIVE_CC)) {
            $criteria->add(InputErrorTableMap::COL_CUMULATIVE_CC, $this->cumulative_cc);
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_CREATED)) {
            $criteria->add(InputErrorTableMap::COL_CREATED, $this->created);
        }
        if ($this->isColumnModified(InputErrorTableMap::COL_MODIFIED)) {
            $criteria->add(InputErrorTableMap::COL_MODIFIED, $this->modified);
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
        $criteria = ChildInputErrorQuery::create();
        $criteria->add(InputErrorTableMap::COL_ID, $this->id);

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
        $validPk = null !== $this->getId();

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
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Jalle19\StatusManager\Database\InputError (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setInputUuid($this->getInputUuid());
        $copyObj->setBerAverage($this->getBerAverage());
        $copyObj->setUncAverage($this->getUncAverage());
        $copyObj->setCumulativeTe($this->getCumulativeTe());
        $copyObj->setCumulativeCc($this->getCumulativeCc());
        $copyObj->setCreated($this->getCreated());
        $copyObj->setModified($this->getModified());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
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
     * @return \Jalle19\StatusManager\Database\InputError Clone of current object.
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
     * Declares an association between this object and a ChildInput object.
     *
     * @param  ChildInput $v
     * @return $this|\Jalle19\StatusManager\Database\InputError The current object (for fluent API support)
     * @throws PropelException
     */
    public function setInput(ChildInput $v = null)
    {
        if ($v === null) {
            $this->setInputUuid(NULL);
        } else {
            $this->setInputUuid($v->getUuid());
        }

        $this->aInput = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildInput object, it will not be re-added.
        if ($v !== null) {
            $v->addInputError($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildInput object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildInput The associated ChildInput object.
     * @throws PropelException
     */
    public function getInput(ConnectionInterface $con = null)
    {
        if ($this->aInput === null && (($this->input_uuid !== "" && $this->input_uuid !== null))) {
            $this->aInput = ChildInputQuery::create()->findPk($this->input_uuid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aInput->addInputErrors($this);
             */
        }

        return $this->aInput;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aInput) {
            $this->aInput->removeInputError($this);
        }
        $this->id = null;
        $this->input_uuid = null;
        $this->ber_average = null;
        $this->unc_average = null;
        $this->cumulative_te = null;
        $this->cumulative_cc = null;
        $this->created = null;
        $this->modified = null;
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
        } // if ($deep)

        $this->aInput = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(InputErrorTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildInputError The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[InputErrorTableMap::COL_MODIFIED] = true;

        return $this;
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
