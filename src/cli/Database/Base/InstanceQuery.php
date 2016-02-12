<?php

namespace Jalle19\StatusManager\Database\Base;

use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Instance as ChildInstance;
use Jalle19\StatusManager\Database\InstanceQuery as ChildInstanceQuery;
use Jalle19\StatusManager\Database\Map\InstanceTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'instance' table.
 *
 *
 *
 * @method     ChildInstanceQuery orderByName($order = Criteria::ASC) Order by the name column
 *
 * @method     ChildInstanceQuery groupByName() Group by the name column
 *
 * @method     ChildInstanceQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInstanceQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInstanceQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInstanceQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInstanceQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInstanceQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInstanceQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildInstanceQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildInstanceQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildInstanceQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildInstanceQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildInstanceQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildInstanceQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildInstanceQuery leftJoinConnection($relationAlias = null) Adds a LEFT JOIN clause to the query using the Connection relation
 * @method     ChildInstanceQuery rightJoinConnection($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Connection relation
 * @method     ChildInstanceQuery innerJoinConnection($relationAlias = null) Adds a INNER JOIN clause to the query using the Connection relation
 *
 * @method     ChildInstanceQuery joinWithConnection($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Connection relation
 *
 * @method     ChildInstanceQuery leftJoinWithConnection() Adds a LEFT JOIN clause and with to the query using the Connection relation
 * @method     ChildInstanceQuery rightJoinWithConnection() Adds a RIGHT JOIN clause and with to the query using the Connection relation
 * @method     ChildInstanceQuery innerJoinWithConnection() Adds a INNER JOIN clause and with to the query using the Connection relation
 *
 * @method     ChildInstanceQuery leftJoinInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the Input relation
 * @method     ChildInstanceQuery rightJoinInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Input relation
 * @method     ChildInstanceQuery innerJoinInput($relationAlias = null) Adds a INNER JOIN clause to the query using the Input relation
 *
 * @method     ChildInstanceQuery joinWithInput($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Input relation
 *
 * @method     ChildInstanceQuery leftJoinWithInput() Adds a LEFT JOIN clause and with to the query using the Input relation
 * @method     ChildInstanceQuery rightJoinWithInput() Adds a RIGHT JOIN clause and with to the query using the Input relation
 * @method     ChildInstanceQuery innerJoinWithInput() Adds a INNER JOIN clause and with to the query using the Input relation
 *
 * @method     ChildInstanceQuery leftJoinChannel($relationAlias = null) Adds a LEFT JOIN clause to the query using the Channel relation
 * @method     ChildInstanceQuery rightJoinChannel($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Channel relation
 * @method     ChildInstanceQuery innerJoinChannel($relationAlias = null) Adds a INNER JOIN clause to the query using the Channel relation
 *
 * @method     ChildInstanceQuery joinWithChannel($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Channel relation
 *
 * @method     ChildInstanceQuery leftJoinWithChannel() Adds a LEFT JOIN clause and with to the query using the Channel relation
 * @method     ChildInstanceQuery rightJoinWithChannel() Adds a RIGHT JOIN clause and with to the query using the Channel relation
 * @method     ChildInstanceQuery innerJoinWithChannel() Adds a INNER JOIN clause and with to the query using the Channel relation
 *
 * @method     ChildInstanceQuery leftJoinSubscription($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subscription relation
 * @method     ChildInstanceQuery rightJoinSubscription($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subscription relation
 * @method     ChildInstanceQuery innerJoinSubscription($relationAlias = null) Adds a INNER JOIN clause to the query using the Subscription relation
 *
 * @method     ChildInstanceQuery joinWithSubscription($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Subscription relation
 *
 * @method     ChildInstanceQuery leftJoinWithSubscription() Adds a LEFT JOIN clause and with to the query using the Subscription relation
 * @method     ChildInstanceQuery rightJoinWithSubscription() Adds a RIGHT JOIN clause and with to the query using the Subscription relation
 * @method     ChildInstanceQuery innerJoinWithSubscription() Adds a INNER JOIN clause and with to the query using the Subscription relation
 *
 * @method     \Jalle19\StatusManager\Database\UserQuery|\Jalle19\StatusManager\Database\ConnectionQuery|\Jalle19\StatusManager\Database\InputQuery|\Jalle19\StatusManager\Database\ChannelQuery|\Jalle19\StatusManager\Database\SubscriptionQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInstance findOne(ConnectionInterface $con = null) Return the first ChildInstance matching the query
 * @method     ChildInstance findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInstance matching the query, or a new ChildInstance object populated from the query conditions when no match is found
 *
 * @method     ChildInstance findOneByName(string $name) Return the first ChildInstance filtered by the name column *

 * @method     ChildInstance requirePk($key, ConnectionInterface $con = null) Return the ChildInstance by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInstance requireOne(ConnectionInterface $con = null) Return the first ChildInstance matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInstance requireOneByName(string $name) Return the first ChildInstance filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInstance[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInstance objects based on current ModelCriteria
 * @method     ChildInstance[]|ObjectCollection findByName(string $name) Return ChildInstance objects filtered by the name column
 * @method     ChildInstance[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InstanceQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Jalle19\StatusManager\Database\Base\InstanceQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'tvheadend_status_manager', $modelName = '\\Jalle19\\StatusManager\\Database\\Instance', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInstanceQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInstanceQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInstanceQuery) {
            return $criteria;
        }
        $query = new ChildInstanceQuery();
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
     * @return ChildInstance|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = InstanceTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InstanceTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
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
     * @return ChildInstance A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT name FROM instance WHERE name = :p0';
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
            /** @var ChildInstance $obj */
            $obj = new ChildInstance();
            $obj->hydrate($row);
            InstanceTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInstance|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInstanceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InstanceTableMap::COL_NAME, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInstanceQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InstanceTableMap::COL_NAME, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildInstanceQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(InstanceTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\User object
     *
     * @param \Jalle19\StatusManager\Database\User|ObjectCollection $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInstanceQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Jalle19\StatusManager\Database\User) {
            return $this
                ->addUsingAlias(InstanceTableMap::COL_NAME, $user->getInstanceName(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            return $this
                ->useUserQuery()
                ->filterByPrimaryKeys($user->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildInstanceQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Jalle19\StatusManager\Database\UserQuery');
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Connection object
     *
     * @param \Jalle19\StatusManager\Database\Connection|ObjectCollection $connection the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInstanceQuery The current query, for fluid interface
     */
    public function filterByConnection($connection, $comparison = null)
    {
        if ($connection instanceof \Jalle19\StatusManager\Database\Connection) {
            return $this
                ->addUsingAlias(InstanceTableMap::COL_NAME, $connection->getInstanceName(), $comparison);
        } elseif ($connection instanceof ObjectCollection) {
            return $this
                ->useConnectionQuery()
                ->filterByPrimaryKeys($connection->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByConnection() only accepts arguments of type \Jalle19\StatusManager\Database\Connection or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Connection relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInstanceQuery The current query, for fluid interface
     */
    public function joinConnection($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Connection');

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
            $this->addJoinObject($join, 'Connection');
        }

        return $this;
    }

    /**
     * Use the Connection relation Connection object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Jalle19\StatusManager\Database\ConnectionQuery A secondary query class using the current class as primary query
     */
    public function useConnectionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinConnection($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Connection', '\Jalle19\StatusManager\Database\ConnectionQuery');
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Input object
     *
     * @param \Jalle19\StatusManager\Database\Input|ObjectCollection $input the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInstanceQuery The current query, for fluid interface
     */
    public function filterByInput($input, $comparison = null)
    {
        if ($input instanceof \Jalle19\StatusManager\Database\Input) {
            return $this
                ->addUsingAlias(InstanceTableMap::COL_NAME, $input->getInstanceName(), $comparison);
        } elseif ($input instanceof ObjectCollection) {
            return $this
                ->useInputQuery()
                ->filterByPrimaryKeys($input->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildInstanceQuery The current query, for fluid interface
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
     * Filter the query by a related \Jalle19\StatusManager\Database\Channel object
     *
     * @param \Jalle19\StatusManager\Database\Channel|ObjectCollection $channel the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInstanceQuery The current query, for fluid interface
     */
    public function filterByChannel($channel, $comparison = null)
    {
        if ($channel instanceof \Jalle19\StatusManager\Database\Channel) {
            return $this
                ->addUsingAlias(InstanceTableMap::COL_NAME, $channel->getInstanceName(), $comparison);
        } elseif ($channel instanceof ObjectCollection) {
            return $this
                ->useChannelQuery()
                ->filterByPrimaryKeys($channel->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByChannel() only accepts arguments of type \Jalle19\StatusManager\Database\Channel or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Channel relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildInstanceQuery The current query, for fluid interface
     */
    public function joinChannel($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Channel');

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
            $this->addJoinObject($join, 'Channel');
        }

        return $this;
    }

    /**
     * Use the Channel relation Channel object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Jalle19\StatusManager\Database\ChannelQuery A secondary query class using the current class as primary query
     */
    public function useChannelQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinChannel($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Channel', '\Jalle19\StatusManager\Database\ChannelQuery');
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Subscription object
     *
     * @param \Jalle19\StatusManager\Database\Subscription|ObjectCollection $subscription the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInstanceQuery The current query, for fluid interface
     */
    public function filterBySubscription($subscription, $comparison = null)
    {
        if ($subscription instanceof \Jalle19\StatusManager\Database\Subscription) {
            return $this
                ->addUsingAlias(InstanceTableMap::COL_NAME, $subscription->getInstanceName(), $comparison);
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
     * @return $this|ChildInstanceQuery The current query, for fluid interface
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
     * @param   ChildInstance $instance Object to remove from the list of results
     *
     * @return $this|ChildInstanceQuery The current query, for fluid interface
     */
    public function prune($instance = null)
    {
        if ($instance) {
            $this->addUsingAlias(InstanceTableMap::COL_NAME, $instance->getName(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the instance table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InstanceTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InstanceTableMap::clearInstancePool();
            InstanceTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InstanceTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InstanceTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InstanceTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InstanceTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InstanceQuery
