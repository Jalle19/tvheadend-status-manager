<?php

namespace Jalle19\StatusManager\Database\Base;

use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Channel as ChildChannel;
use Jalle19\StatusManager\Database\ChannelQuery as ChildChannelQuery;
use Jalle19\StatusManager\Database\Map\ChannelTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'channel' table.
 *
 *
 *
 * @method     ChildChannelQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildChannelQuery orderByInstanceName($order = Criteria::ASC) Order by the instance_name column
 * @method     ChildChannelQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildChannelQuery groupById() Group by the id column
 * @method     ChildChannelQuery groupByInstanceName() Group by the instance_name column
 * @method     ChildChannelQuery groupByName() Group by the name column
 *
 * @method     ChildChannelQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChannelQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChannelQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChannelQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildChannelQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildChannelQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildChannelQuery leftJoinInstance($relationAlias = null) Adds a LEFT JOIN clause to the query using the Instance relation
 * @method     ChildChannelQuery rightJoinInstance($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Instance relation
 * @method     ChildChannelQuery innerJoinInstance($relationAlias = null) Adds a INNER JOIN clause to the query using the Instance relation
 *
 * @method     ChildChannelQuery joinWithInstance($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Instance relation
 *
 * @method     ChildChannelQuery leftJoinWithInstance() Adds a LEFT JOIN clause and with to the query using the Instance relation
 * @method     ChildChannelQuery rightJoinWithInstance() Adds a RIGHT JOIN clause and with to the query using the Instance relation
 * @method     ChildChannelQuery innerJoinWithInstance() Adds a INNER JOIN clause and with to the query using the Instance relation
 *
 * @method     ChildChannelQuery leftJoinSubscription($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subscription relation
 * @method     ChildChannelQuery rightJoinSubscription($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subscription relation
 * @method     ChildChannelQuery innerJoinSubscription($relationAlias = null) Adds a INNER JOIN clause to the query using the Subscription relation
 *
 * @method     ChildChannelQuery joinWithSubscription($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Subscription relation
 *
 * @method     ChildChannelQuery leftJoinWithSubscription() Adds a LEFT JOIN clause and with to the query using the Subscription relation
 * @method     ChildChannelQuery rightJoinWithSubscription() Adds a RIGHT JOIN clause and with to the query using the Subscription relation
 * @method     ChildChannelQuery innerJoinWithSubscription() Adds a INNER JOIN clause and with to the query using the Subscription relation
 *
 * @method     \Jalle19\StatusManager\Database\InstanceQuery|\Jalle19\StatusManager\Database\SubscriptionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildChannel|null findOne(ConnectionInterface $con = null) Return the first ChildChannel matching the query
 * @method     ChildChannel findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChannel matching the query, or a new ChildChannel object populated from the query conditions when no match is found
 *
 * @method     ChildChannel|null findOneById(int $id) Return the first ChildChannel filtered by the id column
 * @method     ChildChannel|null findOneByInstanceName(string $instance_name) Return the first ChildChannel filtered by the instance_name column
 * @method     ChildChannel|null findOneByName(string $name) Return the first ChildChannel filtered by the name column *

 * @method     ChildChannel requirePk($key, ConnectionInterface $con = null) Return the ChildChannel by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOne(ConnectionInterface $con = null) Return the first ChildChannel matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChannel requireOneById(int $id) Return the first ChildChannel filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOneByInstanceName(string $instance_name) Return the first ChildChannel filtered by the instance_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildChannel requireOneByName(string $name) Return the first ChildChannel filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildChannel[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildChannel objects based on current ModelCriteria
 * @method     ChildChannel[]|ObjectCollection findById(int $id) Return ChildChannel objects filtered by the id column
 * @method     ChildChannel[]|ObjectCollection findByInstanceName(string $instance_name) Return ChildChannel objects filtered by the instance_name column
 * @method     ChildChannel[]|ObjectCollection findByName(string $name) Return ChildChannel objects filtered by the name column
 * @method     ChildChannel[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ChannelQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Jalle19\StatusManager\Database\Base\ChannelQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'tvheadend_status_manager', $modelName = '\\Jalle19\\StatusManager\\Database\\Channel', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChannelQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChannelQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildChannelQuery) {
            return $criteria;
        }
        $query = new ChildChannelQuery();
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
     * @return ChildChannel|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChannelTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ChannelTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildChannel A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, instance_name, name FROM channel WHERE id = :p0';
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
            /** @var ChildChannel $obj */
            $obj = new ChildChannel();
            $obj->hydrate($row);
            ChannelTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildChannel|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ChannelTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ChannelTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ChannelTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ChannelTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByInstanceName($instanceName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($instanceName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_INSTANCE_NAME, $instanceName, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChannelTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Instance object
     *
     * @param \Jalle19\StatusManager\Database\Instance|ObjectCollection $instance The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildChannelQuery The current query, for fluid interface
     */
    public function filterByInstance($instance, $comparison = null)
    {
        if ($instance instanceof \Jalle19\StatusManager\Database\Instance) {
            return $this
                ->addUsingAlias(ChannelTableMap::COL_INSTANCE_NAME, $instance->getName(), $comparison);
        } elseif ($instance instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChannelTableMap::COL_INSTANCE_NAME, $instance->toKeyValue('PrimaryKey', 'Name'), $comparison);
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
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
     * Filter the query by a related \Jalle19\StatusManager\Database\Subscription object
     *
     * @param \Jalle19\StatusManager\Database\Subscription|ObjectCollection $subscription the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChannelQuery The current query, for fluid interface
     */
    public function filterBySubscription($subscription, $comparison = null)
    {
        if ($subscription instanceof \Jalle19\StatusManager\Database\Subscription) {
            return $this
                ->addUsingAlias(ChannelTableMap::COL_ID, $subscription->getChannelId(), $comparison);
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
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function joinSubscription($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useSubscriptionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSubscription($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subscription', '\Jalle19\StatusManager\Database\SubscriptionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildChannel $channel Object to remove from the list of results
     *
     * @return $this|ChildChannelQuery The current query, for fluid interface
     */
    public function prune($channel = null)
    {
        if ($channel) {
            $this->addUsingAlias(ChannelTableMap::COL_ID, $channel->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the channel table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChannelTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ChannelTableMap::clearInstancePool();
            ChannelTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ChannelTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChannelTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ChannelTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ChannelTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ChannelQuery
