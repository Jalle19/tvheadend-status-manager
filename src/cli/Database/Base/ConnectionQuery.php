<?php

namespace Jalle19\StatusManager\Database\Base;

use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Connection as ChildConnection;
use Jalle19\StatusManager\Database\ConnectionQuery as ChildConnectionQuery;
use Jalle19\StatusManager\Database\Map\ConnectionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'connection' table.
 *
 *
 *
 * @method     ChildConnectionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildConnectionQuery orderByInstanceName($order = Criteria::ASC) Order by the instance_name column
 * @method     ChildConnectionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildConnectionQuery orderByPeer($order = Criteria::ASC) Order by the peer column
 * @method     ChildConnectionQuery orderByStarted($order = Criteria::ASC) Order by the started column
 * @method     ChildConnectionQuery orderByType($order = Criteria::ASC) Order by the type column
 *
 * @method     ChildConnectionQuery groupById() Group by the id column
 * @method     ChildConnectionQuery groupByInstanceName() Group by the instance_name column
 * @method     ChildConnectionQuery groupByUserId() Group by the user_id column
 * @method     ChildConnectionQuery groupByPeer() Group by the peer column
 * @method     ChildConnectionQuery groupByStarted() Group by the started column
 * @method     ChildConnectionQuery groupByType() Group by the type column
 *
 * @method     ChildConnectionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildConnectionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildConnectionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildConnectionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildConnectionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildConnectionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildConnectionQuery leftJoinInstance($relationAlias = null) Adds a LEFT JOIN clause to the query using the Instance relation
 * @method     ChildConnectionQuery rightJoinInstance($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Instance relation
 * @method     ChildConnectionQuery innerJoinInstance($relationAlias = null) Adds a INNER JOIN clause to the query using the Instance relation
 *
 * @method     ChildConnectionQuery joinWithInstance($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Instance relation
 *
 * @method     ChildConnectionQuery leftJoinWithInstance() Adds a LEFT JOIN clause and with to the query using the Instance relation
 * @method     ChildConnectionQuery rightJoinWithInstance() Adds a RIGHT JOIN clause and with to the query using the Instance relation
 * @method     ChildConnectionQuery innerJoinWithInstance() Adds a INNER JOIN clause and with to the query using the Instance relation
 *
 * @method     ChildConnectionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildConnectionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildConnectionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildConnectionQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildConnectionQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildConnectionQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildConnectionQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \Jalle19\StatusManager\Database\InstanceQuery|\Jalle19\StatusManager\Database\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildConnection|null findOne(ConnectionInterface $con = null) Return the first ChildConnection matching the query
 * @method     ChildConnection findOneOrCreate(ConnectionInterface $con = null) Return the first ChildConnection matching the query, or a new ChildConnection object populated from the query conditions when no match is found
 *
 * @method     ChildConnection|null findOneById(int $id) Return the first ChildConnection filtered by the id column
 * @method     ChildConnection|null findOneByInstanceName(string $instance_name) Return the first ChildConnection filtered by the instance_name column
 * @method     ChildConnection|null findOneByUserId(int $user_id) Return the first ChildConnection filtered by the user_id column
 * @method     ChildConnection|null findOneByPeer(string $peer) Return the first ChildConnection filtered by the peer column
 * @method     ChildConnection|null findOneByStarted(string $started) Return the first ChildConnection filtered by the started column
 * @method     ChildConnection|null findOneByType(string $type) Return the first ChildConnection filtered by the type column *

 * @method     ChildConnection requirePk($key, ConnectionInterface $con = null) Return the ChildConnection by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnection requireOne(ConnectionInterface $con = null) Return the first ChildConnection matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConnection requireOneById(int $id) Return the first ChildConnection filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnection requireOneByInstanceName(string $instance_name) Return the first ChildConnection filtered by the instance_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnection requireOneByUserId(int $user_id) Return the first ChildConnection filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnection requireOneByPeer(string $peer) Return the first ChildConnection filtered by the peer column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnection requireOneByStarted(string $started) Return the first ChildConnection filtered by the started column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildConnection requireOneByType(string $type) Return the first ChildConnection filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildConnection[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildConnection objects based on current ModelCriteria
 * @method     ChildConnection[]|ObjectCollection findById(int $id) Return ChildConnection objects filtered by the id column
 * @method     ChildConnection[]|ObjectCollection findByInstanceName(string $instance_name) Return ChildConnection objects filtered by the instance_name column
 * @method     ChildConnection[]|ObjectCollection findByUserId(int $user_id) Return ChildConnection objects filtered by the user_id column
 * @method     ChildConnection[]|ObjectCollection findByPeer(string $peer) Return ChildConnection objects filtered by the peer column
 * @method     ChildConnection[]|ObjectCollection findByStarted(string $started) Return ChildConnection objects filtered by the started column
 * @method     ChildConnection[]|ObjectCollection findByType(string $type) Return ChildConnection objects filtered by the type column
 * @method     ChildConnection[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ConnectionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Jalle19\StatusManager\Database\Base\ConnectionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'tvheadend_status_manager', $modelName = '\\Jalle19\\StatusManager\\Database\\Connection', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildConnectionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildConnectionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildConnectionQuery) {
            return $criteria;
        }
        $query = new ChildConnectionQuery();
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
     * @return ChildConnection|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ConnectionTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ConnectionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildConnection A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, instance_name, user_id, peer, started, type FROM connection WHERE id = :p0';
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
            /** @var ChildConnection $obj */
            $obj = new ChildConnection();
            $obj->hydrate($row);
            ConnectionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildConnection|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ConnectionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ConnectionTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ConnectionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ConnectionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectionTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByInstanceName($instanceName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($instanceName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectionTableMap::COL_INSTANCE_NAME, $instanceName, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(ConnectionTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(ConnectionTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectionTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the peer column
     *
     * Example usage:
     * <code>
     * $query->filterByPeer('fooValue');   // WHERE peer = 'fooValue'
     * $query->filterByPeer('%fooValue%', Criteria::LIKE); // WHERE peer LIKE '%fooValue%'
     * </code>
     *
     * @param     string $peer The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByPeer($peer = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($peer)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectionTableMap::COL_PEER, $peer, $comparison);
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
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByStarted($started = null, $comparison = null)
    {
        if (is_array($started)) {
            $useMinMax = false;
            if (isset($started['min'])) {
                $this->addUsingAlias(ConnectionTableMap::COL_STARTED, $started['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($started['max'])) {
                $this->addUsingAlias(ConnectionTableMap::COL_STARTED, $started['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectionTableMap::COL_STARTED, $started, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ConnectionTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Instance object
     *
     * @param \Jalle19\StatusManager\Database\Instance|ObjectCollection $instance The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByInstance($instance, $comparison = null)
    {
        if ($instance instanceof \Jalle19\StatusManager\Database\Instance) {
            return $this
                ->addUsingAlias(ConnectionTableMap::COL_INSTANCE_NAME, $instance->getName(), $comparison);
        } elseif ($instance instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ConnectionTableMap::COL_INSTANCE_NAME, $instance->toKeyValue('PrimaryKey', 'Name'), $comparison);
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
     * @return $this|ChildConnectionQuery The current query, for fluid interface
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
     * Filter the query by a related \Jalle19\StatusManager\Database\User object
     *
     * @param \Jalle19\StatusManager\Database\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildConnectionQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Jalle19\StatusManager\Database\User) {
            return $this
                ->addUsingAlias(ConnectionTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ConnectionTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Jalle19\StatusManager\Database\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Jalle19\StatusManager\Database\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Jalle19\StatusManager\Database\UserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildConnection $connection Object to remove from the list of results
     *
     * @return $this|ChildConnectionQuery The current query, for fluid interface
     */
    public function prune($connection = null)
    {
        if ($connection) {
            $this->addUsingAlias(ConnectionTableMap::COL_ID, $connection->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the connection table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ConnectionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ConnectionTableMap::clearInstancePool();
            ConnectionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ConnectionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ConnectionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ConnectionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ConnectionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ConnectionQuery
