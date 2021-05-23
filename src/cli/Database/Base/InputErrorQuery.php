<?php

namespace Jalle19\StatusManager\Database\Base;

use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\InputError as ChildInputError;
use Jalle19\StatusManager\Database\InputErrorQuery as ChildInputErrorQuery;
use Jalle19\StatusManager\Database\Map\InputErrorTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'input_error' table.
 *
 *
 *
 * @method     ChildInputErrorQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildInputErrorQuery orderByInputUuid($order = Criteria::ASC) Order by the input_uuid column
 * @method     ChildInputErrorQuery orderByBerAverage($order = Criteria::ASC) Order by the ber_average column
 * @method     ChildInputErrorQuery orderByUncAverage($order = Criteria::ASC) Order by the unc_average column
 * @method     ChildInputErrorQuery orderByCumulativeTe($order = Criteria::ASC) Order by the cumulative_te column
 * @method     ChildInputErrorQuery orderByCumulativeCc($order = Criteria::ASC) Order by the cumulative_cc column
 * @method     ChildInputErrorQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildInputErrorQuery orderByModified($order = Criteria::ASC) Order by the modified column
 *
 * @method     ChildInputErrorQuery groupById() Group by the id column
 * @method     ChildInputErrorQuery groupByInputUuid() Group by the input_uuid column
 * @method     ChildInputErrorQuery groupByBerAverage() Group by the ber_average column
 * @method     ChildInputErrorQuery groupByUncAverage() Group by the unc_average column
 * @method     ChildInputErrorQuery groupByCumulativeTe() Group by the cumulative_te column
 * @method     ChildInputErrorQuery groupByCumulativeCc() Group by the cumulative_cc column
 * @method     ChildInputErrorQuery groupByCreated() Group by the created column
 * @method     ChildInputErrorQuery groupByModified() Group by the modified column
 *
 * @method     ChildInputErrorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInputErrorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInputErrorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInputErrorQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInputErrorQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInputErrorQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInputErrorQuery leftJoinInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the Input relation
 * @method     ChildInputErrorQuery rightJoinInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Input relation
 * @method     ChildInputErrorQuery innerJoinInput($relationAlias = null) Adds a INNER JOIN clause to the query using the Input relation
 *
 * @method     ChildInputErrorQuery joinWithInput($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Input relation
 *
 * @method     ChildInputErrorQuery leftJoinWithInput() Adds a LEFT JOIN clause and with to the query using the Input relation
 * @method     ChildInputErrorQuery rightJoinWithInput() Adds a RIGHT JOIN clause and with to the query using the Input relation
 * @method     ChildInputErrorQuery innerJoinWithInput() Adds a INNER JOIN clause and with to the query using the Input relation
 *
 * @method     \Jalle19\StatusManager\Database\InputQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInputError|null findOne(ConnectionInterface $con = null) Return the first ChildInputError matching the query
 * @method     ChildInputError findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInputError matching the query, or a new ChildInputError object populated from the query conditions when no match is found
 *
 * @method     ChildInputError|null findOneById(int $id) Return the first ChildInputError filtered by the id column
 * @method     ChildInputError|null findOneByInputUuid(string $input_uuid) Return the first ChildInputError filtered by the input_uuid column
 * @method     ChildInputError|null findOneByBerAverage(double $ber_average) Return the first ChildInputError filtered by the ber_average column
 * @method     ChildInputError|null findOneByUncAverage(double $unc_average) Return the first ChildInputError filtered by the unc_average column
 * @method     ChildInputError|null findOneByCumulativeTe(int $cumulative_te) Return the first ChildInputError filtered by the cumulative_te column
 * @method     ChildInputError|null findOneByCumulativeCc(int $cumulative_cc) Return the first ChildInputError filtered by the cumulative_cc column
 * @method     ChildInputError|null findOneByCreated(string $created) Return the first ChildInputError filtered by the created column
 * @method     ChildInputError|null findOneByModified(string $modified) Return the first ChildInputError filtered by the modified column *

 * @method     ChildInputError requirePk($key, ConnectionInterface $con = null) Return the ChildInputError by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOne(ConnectionInterface $con = null) Return the first ChildInputError matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInputError requireOneById(int $id) Return the first ChildInputError filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOneByInputUuid(string $input_uuid) Return the first ChildInputError filtered by the input_uuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOneByBerAverage(double $ber_average) Return the first ChildInputError filtered by the ber_average column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOneByUncAverage(double $unc_average) Return the first ChildInputError filtered by the unc_average column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOneByCumulativeTe(int $cumulative_te) Return the first ChildInputError filtered by the cumulative_te column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOneByCumulativeCc(int $cumulative_cc) Return the first ChildInputError filtered by the cumulative_cc column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOneByCreated(string $created) Return the first ChildInputError filtered by the created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInputError requireOneByModified(string $modified) Return the first ChildInputError filtered by the modified column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInputError[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInputError objects based on current ModelCriteria
 * @method     ChildInputError[]|ObjectCollection findById(int $id) Return ChildInputError objects filtered by the id column
 * @method     ChildInputError[]|ObjectCollection findByInputUuid(string $input_uuid) Return ChildInputError objects filtered by the input_uuid column
 * @method     ChildInputError[]|ObjectCollection findByBerAverage(double $ber_average) Return ChildInputError objects filtered by the ber_average column
 * @method     ChildInputError[]|ObjectCollection findByUncAverage(double $unc_average) Return ChildInputError objects filtered by the unc_average column
 * @method     ChildInputError[]|ObjectCollection findByCumulativeTe(int $cumulative_te) Return ChildInputError objects filtered by the cumulative_te column
 * @method     ChildInputError[]|ObjectCollection findByCumulativeCc(int $cumulative_cc) Return ChildInputError objects filtered by the cumulative_cc column
 * @method     ChildInputError[]|ObjectCollection findByCreated(string $created) Return ChildInputError objects filtered by the created column
 * @method     ChildInputError[]|ObjectCollection findByModified(string $modified) Return ChildInputError objects filtered by the modified column
 * @method     ChildInputError[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InputErrorQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Jalle19\StatusManager\Database\Base\InputErrorQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'tvheadend_status_manager', $modelName = '\\Jalle19\\StatusManager\\Database\\InputError', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInputErrorQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInputErrorQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInputErrorQuery) {
            return $criteria;
        }
        $query = new ChildInputErrorQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildInputError|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InputErrorTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InputErrorTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInputError A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, input_uuid, ber_average, unc_average, cumulative_te, cumulative_cc, created, modified FROM input_error WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildInputError $obj */
            $obj = new ChildInputError();
            $obj->hydrate($row);
            InputErrorTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildInputError|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InputErrorTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InputErrorTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the input_uuid column
     *
     * Example usage:
     * <code>
     * $query->filterByInputUuid('fooValue');   // WHERE input_uuid = 'fooValue'
     * $query->filterByInputUuid('%fooValue%', Criteria::LIKE); // WHERE input_uuid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $inputUuid The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByInputUuid($inputUuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($inputUuid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_INPUT_UUID, $inputUuid, $comparison);
    }

    /**
     * Filter the query on the ber_average column
     *
     * Example usage:
     * <code>
     * $query->filterByBerAverage(1234); // WHERE ber_average = 1234
     * $query->filterByBerAverage(array(12, 34)); // WHERE ber_average IN (12, 34)
     * $query->filterByBerAverage(array('min' => 12)); // WHERE ber_average > 12
     * </code>
     *
     * @param     mixed $berAverage The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByBerAverage($berAverage = null, $comparison = null)
    {
        if (is_array($berAverage)) {
            $useMinMax = false;
            if (isset($berAverage['min'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_BER_AVERAGE, $berAverage['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($berAverage['max'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_BER_AVERAGE, $berAverage['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_BER_AVERAGE, $berAverage, $comparison);
    }

    /**
     * Filter the query on the unc_average column
     *
     * Example usage:
     * <code>
     * $query->filterByUncAverage(1234); // WHERE unc_average = 1234
     * $query->filterByUncAverage(array(12, 34)); // WHERE unc_average IN (12, 34)
     * $query->filterByUncAverage(array('min' => 12)); // WHERE unc_average > 12
     * </code>
     *
     * @param     mixed $uncAverage The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByUncAverage($uncAverage = null, $comparison = null)
    {
        if (is_array($uncAverage)) {
            $useMinMax = false;
            if (isset($uncAverage['min'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_UNC_AVERAGE, $uncAverage['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($uncAverage['max'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_UNC_AVERAGE, $uncAverage['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_UNC_AVERAGE, $uncAverage, $comparison);
    }

    /**
     * Filter the query on the cumulative_te column
     *
     * Example usage:
     * <code>
     * $query->filterByCumulativeTe(1234); // WHERE cumulative_te = 1234
     * $query->filterByCumulativeTe(array(12, 34)); // WHERE cumulative_te IN (12, 34)
     * $query->filterByCumulativeTe(array('min' => 12)); // WHERE cumulative_te > 12
     * </code>
     *
     * @param     mixed $cumulativeTe The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByCumulativeTe($cumulativeTe = null, $comparison = null)
    {
        if (is_array($cumulativeTe)) {
            $useMinMax = false;
            if (isset($cumulativeTe['min'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_CUMULATIVE_TE, $cumulativeTe['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cumulativeTe['max'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_CUMULATIVE_TE, $cumulativeTe['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_CUMULATIVE_TE, $cumulativeTe, $comparison);
    }

    /**
     * Filter the query on the cumulative_cc column
     *
     * Example usage:
     * <code>
     * $query->filterByCumulativeCc(1234); // WHERE cumulative_cc = 1234
     * $query->filterByCumulativeCc(array(12, 34)); // WHERE cumulative_cc IN (12, 34)
     * $query->filterByCumulativeCc(array('min' => 12)); // WHERE cumulative_cc > 12
     * </code>
     *
     * @param     mixed $cumulativeCc The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByCumulativeCc($cumulativeCc = null, $comparison = null)
    {
        if (is_array($cumulativeCc)) {
            $useMinMax = false;
            if (isset($cumulativeCc['min'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_CUMULATIVE_CC, $cumulativeCc['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cumulativeCc['max'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_CUMULATIVE_CC, $cumulativeCc['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_CUMULATIVE_CC, $cumulativeCc, $comparison);
    }

    /**
     * Filter the query on the created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated('2011-03-14'); // WHERE created = '2011-03-14'
     * $query->filterByCreated('now'); // WHERE created = '2011-03-14'
     * $query->filterByCreated(array('max' => 'yesterday')); // WHERE created > '2011-03-13'
     * </code>
     *
     * @param     mixed $created The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the modified column
     *
     * Example usage:
     * <code>
     * $query->filterByModified('2011-03-14'); // WHERE modified = '2011-03-14'
     * $query->filterByModified('now'); // WHERE modified = '2011-03-14'
     * $query->filterByModified(array('max' => 'yesterday')); // WHERE modified > '2011-03-13'
     * </code>
     *
     * @param     mixed $modified The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByModified($modified = null, $comparison = null)
    {
        if (is_array($modified)) {
            $useMinMax = false;
            if (isset($modified['min'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_MODIFIED, $modified['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($modified['max'])) {
                $this->addUsingAlias(InputErrorTableMap::COL_MODIFIED, $modified['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputErrorTableMap::COL_MODIFIED, $modified, $comparison);
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Input object
     *
     * @param \Jalle19\StatusManager\Database\Input|ObjectCollection $input The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInputErrorQuery The current query, for fluid interface
     */
    public function filterByInput($input, $comparison = null)
    {
        if ($input instanceof \Jalle19\StatusManager\Database\Input) {
            return $this
                ->addUsingAlias(InputErrorTableMap::COL_INPUT_UUID, $input->getUuid(), $comparison);
        } elseif ($input instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InputErrorTableMap::COL_INPUT_UUID, $input->toKeyValue('PrimaryKey', 'Uuid'), $comparison);
        } else {
            throw new PropelException('filterByInput() only accepts arguments of type \Jalle19\StatusManager\Database\Input or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Input relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function joinInput($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Input');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Input');
        }

        return $this;
    }

    /**
     * Use the Input relation Input object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Jalle19\StatusManager\Database\InputQuery A secondary query class using the current class as primary query
     */
    public function useInputQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInput($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Input', '\Jalle19\StatusManager\Database\InputQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildInputError $inputError Object to remove from the list of results
     *
     * @return $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function prune($inputError = null)
    {
        if ($inputError) {
            $this->addUsingAlias(InputErrorTableMap::COL_ID, $inputError->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the input_error table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputErrorTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InputErrorTableMap::clearInstancePool();
            InputErrorTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputErrorTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InputErrorTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InputErrorTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InputErrorTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(InputErrorTableMap::COL_MODIFIED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(InputErrorTableMap::COL_MODIFIED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(InputErrorTableMap::COL_MODIFIED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(InputErrorTableMap::COL_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(InputErrorTableMap::COL_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildInputErrorQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(InputErrorTableMap::COL_CREATED);
    }

} // InputErrorQuery
