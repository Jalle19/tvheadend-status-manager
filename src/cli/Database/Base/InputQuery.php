<?php

namespace Jalle19\StatusManager\Database\Base;

use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Input as ChildInput;
use Jalle19\StatusManager\Database\InputQuery as ChildInputQuery;
use Jalle19\StatusManager\Database\Map\InputTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'input' table.
 *
 *
 *
 * @method     ChildInputQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method     ChildInputQuery orderByInstanceName($order = Criteria::ASC) Order by the instance_name column
 * @method     ChildInputQuery orderByStarted($order = Criteria::ASC) Order by the started column
 * @method     ChildInputQuery orderByInput($order = Criteria::ASC) Order by the input column
 * @method     ChildInputQuery orderByNetwork($order = Criteria::ASC) Order by the network column
 * @method     ChildInputQuery orderByMux($order = Criteria::ASC) Order by the mux column
 * @method     ChildInputQuery orderByWeight($order = Criteria::ASC) Order by the weight column
 *
 * @method     ChildInputQuery groupByUuid() Group by the uuid column
 * @method     ChildInputQuery groupByInstanceName() Group by the instance_name column
 * @method     ChildInputQuery groupByStarted() Group by the started column
 * @method     ChildInputQuery groupByInput() Group by the input column
 * @method     ChildInputQuery groupByNetwork() Group by the network column
 * @method     ChildInputQuery groupByMux() Group by the mux column
 * @method     ChildInputQuery groupByWeight() Group by the weight column
 *
 * @method     ChildInputQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInputQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInputQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInputQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInputQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInputQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInputQuery leftJoinInstance($relationAlias = null) Adds a LEFT JOIN clause to the query using the Instance relation
 * @method     ChildInputQuery rightJoinInstance($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Instance relation
 * @method     ChildInputQuery innerJoinInstance($relationAlias = null) Adds a INNER JOIN clause to the query using the Instance relation
 *
 * @method     ChildInputQuery joinWithInstance($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Instance relation
 *
 * @method     ChildInputQuery leftJoinWithInstance() Adds a LEFT JOIN clause and with to the query using the Instance relation
 * @method     ChildInputQuery rightJoinWithInstance() Adds a RIGHT JOIN clause and with to the query using the Instance relation
 * @method     ChildInputQuery innerJoinWithInstance() Adds a INNER JOIN clause and with to the query using the Instance relation
 *
 * @method     ChildInputQuery leftJoinInputError($relationAlias = null) Adds a LEFT JOIN clause to the query using the InputError relation
 * @method     ChildInputQuery rightJoinInputError($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InputError relation
 * @method     ChildInputQuery innerJoinInputError($relationAlias = null) Adds a INNER JOIN clause to the query using the InputError relation
 *
 * @method     ChildInputQuery joinWithInputError($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InputError relation
 *
 * @method     ChildInputQuery leftJoinWithInputError() Adds a LEFT JOIN clause and with to the query using the InputError relation
 * @method     ChildInputQuery rightJoinWithInputError() Adds a RIGHT JOIN clause and with to the query using the InputError relation
 * @method     ChildInputQuery innerJoinWithInputError() Adds a INNER JOIN clause and with to the query using the InputError relation
 *
 * @method     ChildInputQuery leftJoinSubscription($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subscription relation
 * @method     ChildInputQuery rightJoinSubscription($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subscription relation
 * @method     ChildInputQuery innerJoinSubscription($relationAlias = null) Adds a INNER JOIN clause to the query using the Subscription relation
 *
 * @method     ChildInputQuery joinWithSubscription($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Subscription relation
 *
 * @method     ChildInputQuery leftJoinWithSubscription() Adds a LEFT JOIN clause and with to the query using the Subscription relation
 * @method     ChildInputQuery rightJoinWithSubscription() Adds a RIGHT JOIN clause and with to the query using the Subscription relation
 * @method     ChildInputQuery innerJoinWithSubscription() Adds a INNER JOIN clause and with to the query using the Subscription relation
 *
 * @method     \Jalle19\StatusManager\Database\InstanceQuery|\Jalle19\StatusManager\Database\InputErrorQuery|\Jalle19\StatusManager\Database\SubscriptionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInput|null findOne(ConnectionInterface $con = null) Return the first ChildInput matching the query
 * @method     ChildInput findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInput matching the query, or a new ChildInput object populated from the query conditions when no match is found
 *
 * @method     ChildInput|null findOneByUuid(string $uuid) Return the first ChildInput filtered by the uuid column
 * @method     ChildInput|null findOneByInstanceName(string $instance_name) Return the first ChildInput filtered by the instance_name column
 * @method     ChildInput|null findOneByStarted(string $started) Return the first ChildInput filtered by the started column
 * @method     ChildInput|null findOneByInput(string $input) Return the first ChildInput filtered by the input column
 * @method     ChildInput|null findOneByNetwork(string $network) Return the first ChildInput filtered by the network column
 * @method     ChildInput|null findOneByMux(string $mux) Return the first ChildInput filtered by the mux column
 * @method     ChildInput|null findOneByWeight(int $weight) Return the first ChildInput filtered by the weight column *

 * @method     ChildInput requirePk($key, ConnectionInterface $con = null) Return the ChildInput by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInput requireOne(ConnectionInterface $con = null) Return the first ChildInput matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInput requireOneByUuid(string $uuid) Return the first ChildInput filtered by the uuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInput requireOneByInstanceName(string $instance_name) Return the first ChildInput filtered by the instance_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInput requireOneByStarted(string $started) Return the first ChildInput filtered by the started column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInput requireOneByInput(string $input) Return the first ChildInput filtered by the input column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInput requireOneByNetwork(string $network) Return the first ChildInput filtered by the network column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInput requireOneByMux(string $mux) Return the first ChildInput filtered by the mux column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInput requireOneByWeight(int $weight) Return the first ChildInput filtered by the weight column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInput[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInput objects based on current ModelCriteria
 * @method     ChildInput[]|ObjectCollection findByUuid(string $uuid) Return ChildInput objects filtered by the uuid column
 * @method     ChildInput[]|ObjectCollection findByInstanceName(string $instance_name) Return ChildInput objects filtered by the instance_name column
 * @method     ChildInput[]|ObjectCollection findByStarted(string $started) Return ChildInput objects filtered by the started column
 * @method     ChildInput[]|ObjectCollection findByInput(string $input) Return ChildInput objects filtered by the input column
 * @method     ChildInput[]|ObjectCollection findByNetwork(string $network) Return ChildInput objects filtered by the network column
 * @method     ChildInput[]|ObjectCollection findByMux(string $mux) Return ChildInput objects filtered by the mux column
 * @method     ChildInput[]|ObjectCollection findByWeight(int $weight) Return ChildInput objects filtered by the weight column
 * @method     ChildInput[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InputQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Jalle19\StatusManager\Database\Base\InputQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'tvheadend_status_manager', $modelName = '\\Jalle19\\StatusManager\\Database\\Input', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInputQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInputQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInputQuery) {
            return $criteria;
        }
        $query = new ChildInputQuery();
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
     * @return ChildInput|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InputTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InputTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInput A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT uuid, instance_name, started, input, network, mux, weight FROM input WHERE uuid = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildInput $obj */
            $obj = new ChildInput();
            $obj->hydrate($row);
            InputTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInput|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InputTableMap::COL_UUID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InputTableMap::COL_UUID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the uuid column
     *
     * Example usage:
     * <code>
     * $query->filterByUuid('fooValue');   // WHERE uuid = 'fooValue'
     * $query->filterByUuid('%fooValue%', Criteria::LIKE); // WHERE uuid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uuid The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByUuid($uuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uuid)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputTableMap::COL_UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the instance_name column
     *
     * Example usage:
     * <code>
     * $query->filterByInstanceName('fooValue');   // WHERE instance_name = 'fooValue'
     * $query->filterByInstanceName('%fooValue%', Criteria::LIKE); // WHERE instance_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $instanceName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByInstanceName($instanceName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($instanceName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputTableMap::COL_INSTANCE_NAME, $instanceName, $comparison);
    }

    /**
     * Filter the query on the started column
     *
     * Example usage:
     * <code>
     * $query->filterByStarted('2011-03-14'); // WHERE started = '2011-03-14'
     * $query->filterByStarted('now'); // WHERE started = '2011-03-14'
     * $query->filterByStarted(array('max' => 'yesterday')); // WHERE started > '2011-03-13'
     * </code>
     *
     * @param     mixed $started The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByStarted($started = null, $comparison = null)
    {
        if (is_array($started)) {
            $useMinMax = false;
            if (isset($started['min'])) {
                $this->addUsingAlias(InputTableMap::COL_STARTED, $started['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($started['max'])) {
                $this->addUsingAlias(InputTableMap::COL_STARTED, $started['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputTableMap::COL_STARTED, $started, $comparison);
    }

    /**
     * Filter the query on the input column
     *
     * Example usage:
     * <code>
     * $query->filterByInput('fooValue');   // WHERE input = 'fooValue'
     * $query->filterByInput('%fooValue%', Criteria::LIKE); // WHERE input LIKE '%fooValue%'
     * </code>
     *
     * @param     string $input The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByInput($input = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($input)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputTableMap::COL_INPUT, $input, $comparison);
    }

    /**
     * Filter the query on the network column
     *
     * Example usage:
     * <code>
     * $query->filterByNetwork('fooValue');   // WHERE network = 'fooValue'
     * $query->filterByNetwork('%fooValue%', Criteria::LIKE); // WHERE network LIKE '%fooValue%'
     * </code>
     *
     * @param     string $network The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByNetwork($network = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($network)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputTableMap::COL_NETWORK, $network, $comparison);
    }

    /**
     * Filter the query on the mux column
     *
     * Example usage:
     * <code>
     * $query->filterByMux('fooValue');   // WHERE mux = 'fooValue'
     * $query->filterByMux('%fooValue%', Criteria::LIKE); // WHERE mux LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mux The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByMux($mux = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mux)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputTableMap::COL_MUX, $mux, $comparison);
    }

    /**
     * Filter the query on the weight column
     *
     * Example usage:
     * <code>
     * $query->filterByWeight(1234); // WHERE weight = 1234
     * $query->filterByWeight(array(12, 34)); // WHERE weight IN (12, 34)
     * $query->filterByWeight(array('min' => 12)); // WHERE weight > 12
     * </code>
     *
     * @param     mixed $weight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function filterByWeight($weight = null, $comparison = null)
    {
        if (is_array($weight)) {
            $useMinMax = false;
            if (isset($weight['min'])) {
                $this->addUsingAlias(InputTableMap::COL_WEIGHT, $weight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($weight['max'])) {
                $this->addUsingAlias(InputTableMap::COL_WEIGHT, $weight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InputTableMap::COL_WEIGHT, $weight, $comparison);
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Instance object
     *
     * @param \Jalle19\StatusManager\Database\Instance|ObjectCollection $instance The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildInputQuery The current query, for fluid interface
     */
    public function filterByInstance($instance, $comparison = null)
    {
        if ($instance instanceof \Jalle19\StatusManager\Database\Instance) {
            return $this
                ->addUsingAlias(InputTableMap::COL_INSTANCE_NAME, $instance->getName(), $comparison);
        } elseif ($instance instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InputTableMap::COL_INSTANCE_NAME, $instance->toKeyValue('PrimaryKey', 'Name'), $comparison);
        } else {
            throw new PropelException('filterByInstance() only accepts arguments of type \Jalle19\StatusManager\Database\Instance or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Instance relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function joinInstance($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Instance');

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
            $this->addJoinObject($join, 'Instance');
        }

        return $this;
    }

    /**
     * Use the Instance relation Instance object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Jalle19\StatusManager\Database\InstanceQuery A secondary query class using the current class as primary query
     */
    public function useInstanceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInstance($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Instance', '\Jalle19\StatusManager\Database\InstanceQuery');
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\InputError object
     *
     * @param \Jalle19\StatusManager\Database\InputError|ObjectCollection $inputError the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInputQuery The current query, for fluid interface
     */
    public function filterByInputError($inputError, $comparison = null)
    {
        if ($inputError instanceof \Jalle19\StatusManager\Database\InputError) {
            return $this
                ->addUsingAlias(InputTableMap::COL_UUID, $inputError->getInputUuid(), $comparison);
        } elseif ($inputError instanceof ObjectCollection) {
            return $this
                ->useInputErrorQuery()
                ->filterByPrimaryKeys($inputError->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInputError() only accepts arguments of type \Jalle19\StatusManager\Database\InputError or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InputError relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function joinInputError($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InputError');

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
            $this->addJoinObject($join, 'InputError');
        }

        return $this;
    }

    /**
     * Use the InputError relation InputError object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Jalle19\StatusManager\Database\InputErrorQuery A secondary query class using the current class as primary query
     */
    public function useInputErrorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinInputError($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InputError', '\Jalle19\StatusManager\Database\InputErrorQuery');
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Subscription object
     *
     * @param \Jalle19\StatusManager\Database\Subscription|ObjectCollection $subscription the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInputQuery The current query, for fluid interface
     */
    public function filterBySubscription($subscription, $comparison = null)
    {
        if ($subscription instanceof \Jalle19\StatusManager\Database\Subscription) {
            return $this
                ->addUsingAlias(InputTableMap::COL_UUID, $subscription->getInputUuid(), $comparison);
        } elseif ($subscription instanceof ObjectCollection) {
            return $this
                ->useSubscriptionQuery()
                ->filterByPrimaryKeys($subscription->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySubscription() only accepts arguments of type \Jalle19\StatusManager\Database\Subscription or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subscription relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function joinSubscription($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subscription');

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
            $this->addJoinObject($join, 'Subscription');
        }

        return $this;
    }

    /**
     * Use the Subscription relation Subscription object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Jalle19\StatusManager\Database\SubscriptionQuery A secondary query class using the current class as primary query
     */
    public function useSubscriptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubscription($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subscription', '\Jalle19\StatusManager\Database\SubscriptionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildInput $input Object to remove from the list of results
     *
     * @return $this|ChildInputQuery The current query, for fluid interface
     */
    public function prune($input = null)
    {
        if ($input) {
            $this->addUsingAlias(InputTableMap::COL_UUID, $input->getUuid(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the input table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InputTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InputTableMap::clearInstancePool();
            InputTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InputTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InputTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InputTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InputTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InputQuery
