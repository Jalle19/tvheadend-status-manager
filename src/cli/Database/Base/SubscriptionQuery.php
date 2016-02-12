<?php

namespace Jalle19\StatusManager\Database\Base;

use \Exception;
use \PDO;
use Jalle19\StatusManager\Database\Subscription as ChildSubscription;
use Jalle19\StatusManager\Database\SubscriptionQuery as ChildSubscriptionQuery;
use Jalle19\StatusManager\Database\Map\SubscriptionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'subscription' table.
 *
 *
 *
 * @method     ChildSubscriptionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSubscriptionQuery orderByInstanceName($order = Criteria::ASC) Order by the instance_name column
 * @method     ChildSubscriptionQuery orderByInputUuid($order = Criteria::ASC) Order by the input_uuid column
 * @method     ChildSubscriptionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildSubscriptionQuery orderByChannelId($order = Criteria::ASC) Order by the channel_id column
 * @method     ChildSubscriptionQuery orderBySubscriptionId($order = Criteria::ASC) Order by the subscription_id column
 * @method     ChildSubscriptionQuery orderByStarted($order = Criteria::ASC) Order by the started column
 * @method     ChildSubscriptionQuery orderByStopped($order = Criteria::ASC) Order by the stopped column
 * @method     ChildSubscriptionQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildSubscriptionQuery orderByService($order = Criteria::ASC) Order by the service column
 *
 * @method     ChildSubscriptionQuery groupById() Group by the id column
 * @method     ChildSubscriptionQuery groupByInstanceName() Group by the instance_name column
 * @method     ChildSubscriptionQuery groupByInputUuid() Group by the input_uuid column
 * @method     ChildSubscriptionQuery groupByUserId() Group by the user_id column
 * @method     ChildSubscriptionQuery groupByChannelId() Group by the channel_id column
 * @method     ChildSubscriptionQuery groupBySubscriptionId() Group by the subscription_id column
 * @method     ChildSubscriptionQuery groupByStarted() Group by the started column
 * @method     ChildSubscriptionQuery groupByStopped() Group by the stopped column
 * @method     ChildSubscriptionQuery groupByTitle() Group by the title column
 * @method     ChildSubscriptionQuery groupByService() Group by the service column
 *
 * @method     ChildSubscriptionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSubscriptionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSubscriptionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSubscriptionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildSubscriptionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildSubscriptionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildSubscriptionQuery leftJoinInstance($relationAlias = null) Adds a LEFT JOIN clause to the query using the Instance relation
 * @method     ChildSubscriptionQuery rightJoinInstance($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Instance relation
 * @method     ChildSubscriptionQuery innerJoinInstance($relationAlias = null) Adds a INNER JOIN clause to the query using the Instance relation
 *
 * @method     ChildSubscriptionQuery joinWithInstance($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Instance relation
 *
 * @method     ChildSubscriptionQuery leftJoinWithInstance() Adds a LEFT JOIN clause and with to the query using the Instance relation
 * @method     ChildSubscriptionQuery rightJoinWithInstance() Adds a RIGHT JOIN clause and with to the query using the Instance relation
 * @method     ChildSubscriptionQuery innerJoinWithInstance() Adds a INNER JOIN clause and with to the query using the Instance relation
 *
 * @method     ChildSubscriptionQuery leftJoinInput($relationAlias = null) Adds a LEFT JOIN clause to the query using the Input relation
 * @method     ChildSubscriptionQuery rightJoinInput($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Input relation
 * @method     ChildSubscriptionQuery innerJoinInput($relationAlias = null) Adds a INNER JOIN clause to the query using the Input relation
 *
 * @method     ChildSubscriptionQuery joinWithInput($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Input relation
 *
 * @method     ChildSubscriptionQuery leftJoinWithInput() Adds a LEFT JOIN clause and with to the query using the Input relation
 * @method     ChildSubscriptionQuery rightJoinWithInput() Adds a RIGHT JOIN clause and with to the query using the Input relation
 * @method     ChildSubscriptionQuery innerJoinWithInput() Adds a INNER JOIN clause and with to the query using the Input relation
 *
 * @method     ChildSubscriptionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildSubscriptionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildSubscriptionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildSubscriptionQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildSubscriptionQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildSubscriptionQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildSubscriptionQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildSubscriptionQuery leftJoinChannel($relationAlias = null) Adds a LEFT JOIN clause to the query using the Channel relation
 * @method     ChildSubscriptionQuery rightJoinChannel($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Channel relation
 * @method     ChildSubscriptionQuery innerJoinChannel($relationAlias = null) Adds a INNER JOIN clause to the query using the Channel relation
 *
 * @method     ChildSubscriptionQuery joinWithChannel($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Channel relation
 *
 * @method     ChildSubscriptionQuery leftJoinWithChannel() Adds a LEFT JOIN clause and with to the query using the Channel relation
 * @method     ChildSubscriptionQuery rightJoinWithChannel() Adds a RIGHT JOIN clause and with to the query using the Channel relation
 * @method     ChildSubscriptionQuery innerJoinWithChannel() Adds a INNER JOIN clause and with to the query using the Channel relation
 *
 * @method     \Jalle19\StatusManager\Database\InstanceQuery|\Jalle19\StatusManager\Database\InputQuery|\Jalle19\StatusManager\Database\UserQuery|\Jalle19\StatusManager\Database\ChannelQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildSubscription findOne(ConnectionInterface $con = null) Return the first ChildSubscription matching the query
 * @method     ChildSubscription findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSubscription matching the query, or a new ChildSubscription object populated from the query conditions when no match is found
 *
 * @method     ChildSubscription findOneById(int $id) Return the first ChildSubscription filtered by the id column
 * @method     ChildSubscription findOneByInstanceName(string $instance_name) Return the first ChildSubscription filtered by the instance_name column
 * @method     ChildSubscription findOneByInputUuid(string $input_uuid) Return the first ChildSubscription filtered by the input_uuid column
 * @method     ChildSubscription findOneByUserId(int $user_id) Return the first ChildSubscription filtered by the user_id column
 * @method     ChildSubscription findOneByChannelId(int $channel_id) Return the first ChildSubscription filtered by the channel_id column
 * @method     ChildSubscription findOneBySubscriptionId(int $subscription_id) Return the first ChildSubscription filtered by the subscription_id column
 * @method     ChildSubscription findOneByStarted(string $started) Return the first ChildSubscription filtered by the started column
 * @method     ChildSubscription findOneByStopped(string $stopped) Return the first ChildSubscription filtered by the stopped column
 * @method     ChildSubscription findOneByTitle(string $title) Return the first ChildSubscription filtered by the title column
 * @method     ChildSubscription findOneByService(string $service) Return the first ChildSubscription filtered by the service column *

 * @method     ChildSubscription requirePk($key, ConnectionInterface $con = null) Return the ChildSubscription by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOne(ConnectionInterface $con = null) Return the first ChildSubscription matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubscription requireOneById(int $id) Return the first ChildSubscription filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByInstanceName(string $instance_name) Return the first ChildSubscription filtered by the instance_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByInputUuid(string $input_uuid) Return the first ChildSubscription filtered by the input_uuid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByUserId(int $user_id) Return the first ChildSubscription filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByChannelId(int $channel_id) Return the first ChildSubscription filtered by the channel_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneBySubscriptionId(int $subscription_id) Return the first ChildSubscription filtered by the subscription_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByStarted(string $started) Return the first ChildSubscription filtered by the started column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByStopped(string $stopped) Return the first ChildSubscription filtered by the stopped column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByTitle(string $title) Return the first ChildSubscription filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildSubscription requireOneByService(string $service) Return the first ChildSubscription filtered by the service column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildSubscription[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildSubscription objects based on current ModelCriteria
 * @method     ChildSubscription[]|ObjectCollection findById(int $id) Return ChildSubscription objects filtered by the id column
 * @method     ChildSubscription[]|ObjectCollection findByInstanceName(string $instance_name) Return ChildSubscription objects filtered by the instance_name column
 * @method     ChildSubscription[]|ObjectCollection findByInputUuid(string $input_uuid) Return ChildSubscription objects filtered by the input_uuid column
 * @method     ChildSubscription[]|ObjectCollection findByUserId(int $user_id) Return ChildSubscription objects filtered by the user_id column
 * @method     ChildSubscription[]|ObjectCollection findByChannelId(int $channel_id) Return ChildSubscription objects filtered by the channel_id column
 * @method     ChildSubscription[]|ObjectCollection findBySubscriptionId(int $subscription_id) Return ChildSubscription objects filtered by the subscription_id column
 * @method     ChildSubscription[]|ObjectCollection findByStarted(string $started) Return ChildSubscription objects filtered by the started column
 * @method     ChildSubscription[]|ObjectCollection findByStopped(string $stopped) Return ChildSubscription objects filtered by the stopped column
 * @method     ChildSubscription[]|ObjectCollection findByTitle(string $title) Return ChildSubscription objects filtered by the title column
 * @method     ChildSubscription[]|ObjectCollection findByService(string $service) Return ChildSubscription objects filtered by the service column
 * @method     ChildSubscription[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class SubscriptionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Jalle19\StatusManager\Database\Base\SubscriptionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'tvheadend_status_manager', $modelName = '\\Jalle19\\StatusManager\\Database\\Subscription', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSubscriptionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSubscriptionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildSubscriptionQuery) {
            return $criteria;
        }
        $query = new ChildSubscriptionQuery();
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
     * @return ChildSubscription|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SubscriptionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SubscriptionTableMap::DATABASE_NAME);
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
     * @return ChildSubscription A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, instance_name, input_uuid, user_id, channel_id, subscription_id, started, stopped, title, service FROM subscription WHERE id = :p0';
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
            /** @var ChildSubscription $obj */
            $obj = new ChildSubscription();
            $obj->hydrate($row);
            SubscriptionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildSubscription|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SubscriptionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SubscriptionTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the instance_name column
     *
     * Example usage:
     * <code>
     * $query->filterByInstanceName('fooValue');   // WHERE instance_name = 'fooValue'
     * $query->filterByInstanceName('%fooValue%'); // WHERE instance_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $instanceName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByInstanceName($instanceName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($instanceName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $instanceName)) {
                $instanceName = str_replace('*', '%', $instanceName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_INSTANCE_NAME, $instanceName, $comparison);
    }

    /**
     * Filter the query on the input_uuid column
     *
     * Example usage:
     * <code>
     * $query->filterByInputUuid('fooValue');   // WHERE input_uuid = 'fooValue'
     * $query->filterByInputUuid('%fooValue%'); // WHERE input_uuid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $inputUuid The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByInputUuid($inputUuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($inputUuid)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $inputUuid)) {
                $inputUuid = str_replace('*', '%', $inputUuid);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_INPUT_UUID, $inputUuid, $comparison);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the channel_id column
     *
     * Example usage:
     * <code>
     * $query->filterByChannelId(1234); // WHERE channel_id = 1234
     * $query->filterByChannelId(array(12, 34)); // WHERE channel_id IN (12, 34)
     * $query->filterByChannelId(array('min' => 12)); // WHERE channel_id > 12
     * </code>
     *
     * @see       filterByChannel()
     *
     * @param     mixed $channelId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByChannelId($channelId = null, $comparison = null)
    {
        if (is_array($channelId)) {
            $useMinMax = false;
            if (isset($channelId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_CHANNEL_ID, $channelId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($channelId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_CHANNEL_ID, $channelId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_CHANNEL_ID, $channelId, $comparison);
    }

    /**
     * Filter the query on the subscription_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubscriptionId(1234); // WHERE subscription_id = 1234
     * $query->filterBySubscriptionId(array(12, 34)); // WHERE subscription_id IN (12, 34)
     * $query->filterBySubscriptionId(array('min' => 12)); // WHERE subscription_id > 12
     * </code>
     *
     * @param     mixed $subscriptionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterBySubscriptionId($subscriptionId = null, $comparison = null)
    {
        if (is_array($subscriptionId)) {
            $useMinMax = false;
            if (isset($subscriptionId['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $subscriptionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subscriptionId['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $subscriptionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SUBSCRIPTION_ID, $subscriptionId, $comparison);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByStarted($started = null, $comparison = null)
    {
        if (is_array($started)) {
            $useMinMax = false;
            if (isset($started['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_STARTED, $started['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($started['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_STARTED, $started['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_STARTED, $started, $comparison);
    }

    /**
     * Filter the query on the stopped column
     *
     * Example usage:
     * <code>
     * $query->filterByStopped('2011-03-14'); // WHERE stopped = '2011-03-14'
     * $query->filterByStopped('now'); // WHERE stopped = '2011-03-14'
     * $query->filterByStopped(array('max' => 'yesterday')); // WHERE stopped > '2011-03-13'
     * </code>
     *
     * @param     mixed $stopped The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByStopped($stopped = null, $comparison = null)
    {
        if (is_array($stopped)) {
            $useMinMax = false;
            if (isset($stopped['min'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_STOPPED, $stopped['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stopped['max'])) {
                $this->addUsingAlias(SubscriptionTableMap::COL_STOPPED, $stopped['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_STOPPED, $stopped, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the service column
     *
     * Example usage:
     * <code>
     * $query->filterByService('fooValue');   // WHERE service = 'fooValue'
     * $query->filterByService('%fooValue%'); // WHERE service LIKE '%fooValue%'
     * </code>
     *
     * @param     string $service The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByService($service = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($service)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $service)) {
                $service = str_replace('*', '%', $service);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SubscriptionTableMap::COL_SERVICE, $service, $comparison);
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\Instance object
     *
     * @param \Jalle19\StatusManager\Database\Instance|ObjectCollection $instance The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByInstance($instance, $comparison = null)
    {
        if ($instance instanceof \Jalle19\StatusManager\Database\Instance) {
            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_INSTANCE_NAME, $instance->getName(), $comparison);
        } elseif ($instance instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_INSTANCE_NAME, $instance->toKeyValue('PrimaryKey', 'Name'), $comparison);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
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
     * Filter the query by a related \Jalle19\StatusManager\Database\Input object
     *
     * @param \Jalle19\StatusManager\Database\Input|ObjectCollection $input The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByInput($input, $comparison = null)
    {
        if ($input instanceof \Jalle19\StatusManager\Database\Input) {
            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_INPUT_UUID, $input->getUuid(), $comparison);
        } elseif ($input instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_INPUT_UUID, $input->toKeyValue('PrimaryKey', 'Uuid'), $comparison);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function joinInput($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useInputQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInput($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Input', '\Jalle19\StatusManager\Database\InputQuery');
    }

    /**
     * Filter the query by a related \Jalle19\StatusManager\Database\User object
     *
     * @param \Jalle19\StatusManager\Database\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Jalle19\StatusManager\Database\User) {
            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
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
     * Filter the query by a related \Jalle19\StatusManager\Database\Channel object
     *
     * @param \Jalle19\StatusManager\Database\Channel|ObjectCollection $channel The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildSubscriptionQuery The current query, for fluid interface
     */
    public function filterByChannel($channel, $comparison = null)
    {
        if ($channel instanceof \Jalle19\StatusManager\Database\Channel) {
            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_CHANNEL_ID, $channel->getId(), $comparison);
        } elseif ($channel instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SubscriptionTableMap::COL_CHANNEL_ID, $channel->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildSubscription $subscription Object to remove from the list of results
     *
     * @return $this|ChildSubscriptionQuery The current query, for fluid interface
     */
    public function prune($subscription = null)
    {
        if ($subscription) {
            $this->addUsingAlias(SubscriptionTableMap::COL_ID, $subscription->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the subscription table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SubscriptionTableMap::clearInstancePool();
            SubscriptionTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(SubscriptionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SubscriptionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            SubscriptionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SubscriptionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // SubscriptionQuery
