<?php

namespace App\Core\Model\Base;

use \Exception;
use \PDO;
use App\Core\Model\MessageQueue as ChildMessageQueue;
use App\Core\Model\MessageQueueQuery as ChildMessageQueueQuery;
use App\Core\Model\Map\MessageQueueTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'message_queue' table.
 *
 *
 *
 * @method     ChildMessageQueueQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMessageQueueQuery orderByDestination($order = Criteria::ASC) Order by the destination column
 * @method     ChildMessageQueueQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     ChildMessageQueueQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 *
 * @method     ChildMessageQueueQuery groupById() Group by the id column
 * @method     ChildMessageQueueQuery groupByDestination() Group by the destination column
 * @method     ChildMessageQueueQuery groupByMessage() Group by the message column
 * @method     ChildMessageQueueQuery groupByTimestamp() Group by the timestamp column
 *
 * @method     ChildMessageQueueQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMessageQueueQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMessageQueueQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMessageQueueQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMessageQueueQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMessageQueueQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMessageQueue findOne(ConnectionInterface $con = null) Return the first ChildMessageQueue matching the query
 * @method     ChildMessageQueue findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMessageQueue matching the query, or a new ChildMessageQueue object populated from the query conditions when no match is found
 *
 * @method     ChildMessageQueue findOneById(int $id) Return the first ChildMessageQueue filtered by the id column
 * @method     ChildMessageQueue findOneByDestination(string $destination) Return the first ChildMessageQueue filtered by the destination column
 * @method     ChildMessageQueue findOneByMessage(string $message) Return the first ChildMessageQueue filtered by the message column
 * @method     ChildMessageQueue findOneByTimestamp(string $timestamp) Return the first ChildMessageQueue filtered by the timestamp column *

 * @method     ChildMessageQueue requirePk($key, ConnectionInterface $con = null) Return the ChildMessageQueue by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessageQueue requireOne(ConnectionInterface $con = null) Return the first ChildMessageQueue matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMessageQueue requireOneById(int $id) Return the first ChildMessageQueue filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessageQueue requireOneByDestination(string $destination) Return the first ChildMessageQueue filtered by the destination column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessageQueue requireOneByMessage(string $message) Return the first ChildMessageQueue filtered by the message column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessageQueue requireOneByTimestamp(string $timestamp) Return the first ChildMessageQueue filtered by the timestamp column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMessageQueue[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMessageQueue objects based on current ModelCriteria
 * @method     ChildMessageQueue[]|ObjectCollection findById(int $id) Return ChildMessageQueue objects filtered by the id column
 * @method     ChildMessageQueue[]|ObjectCollection findByDestination(string $destination) Return ChildMessageQueue objects filtered by the destination column
 * @method     ChildMessageQueue[]|ObjectCollection findByMessage(string $message) Return ChildMessageQueue objects filtered by the message column
 * @method     ChildMessageQueue[]|ObjectCollection findByTimestamp(string $timestamp) Return ChildMessageQueue objects filtered by the timestamp column
 * @method     ChildMessageQueue[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MessageQueueQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \App\Core\Model\Base\MessageQueueQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'freedom', $modelName = '\\App\\Core\\Model\\MessageQueue', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMessageQueueQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMessageQueueQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMessageQueueQuery) {
            return $criteria;
        }
        $query = new ChildMessageQueueQuery();
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
     * @return ChildMessageQueue|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MessageQueueTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MessageQueueTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMessageQueue A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `id`, `destination`, `message`, `timestamp` FROM `message_queue` WHERE `id` = :p0';
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
            /** @var ChildMessageQueue $obj */
            $obj = new ChildMessageQueue();
            $obj->hydrate($row);
            MessageQueueTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMessageQueue|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMessageQueueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MessageQueueTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMessageQueueQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MessageQueueTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMessageQueueQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MessageQueueTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MessageQueueTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MessageQueueTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the destination column
     *
     * Example usage:
     * <code>
     * $query->filterByDestination('fooValue');   // WHERE destination = 'fooValue'
     * $query->filterByDestination('%fooValue%', Criteria::LIKE); // WHERE destination LIKE '%fooValue%'
     * </code>
     *
     * @param     string $destination The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMessageQueueQuery The current query, for fluid interface
     */
    public function filterByDestination($destination = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($destination)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MessageQueueTableMap::COL_DESTINATION, $destination, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%', Criteria::LIKE); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMessageQueueQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MessageQueueTableMap::COL_MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the timestamp column
     *
     * Example usage:
     * <code>
     * $query->filterByTimestamp('2011-03-14'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp('now'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp(array('max' => 'yesterday')); // WHERE timestamp > '2011-03-13'
     * </code>
     *
     * @param     mixed $timestamp The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMessageQueueQuery The current query, for fluid interface
     */
    public function filterByTimestamp($timestamp = null, $comparison = null)
    {
        if (is_array($timestamp)) {
            $useMinMax = false;
            if (isset($timestamp['min'])) {
                $this->addUsingAlias(MessageQueueTableMap::COL_TIMESTAMP, $timestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timestamp['max'])) {
                $this->addUsingAlias(MessageQueueTableMap::COL_TIMESTAMP, $timestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MessageQueueTableMap::COL_TIMESTAMP, $timestamp, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMessageQueue $messageQueue Object to remove from the list of results
     *
     * @return $this|ChildMessageQueueQuery The current query, for fluid interface
     */
    public function prune($messageQueue = null)
    {
        if ($messageQueue) {
            $this->addUsingAlias(MessageQueueTableMap::COL_ID, $messageQueue->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the message_queue table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MessageQueueTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MessageQueueTableMap::clearInstancePool();
            MessageQueueTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MessageQueueTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MessageQueueTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MessageQueueTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MessageQueueTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MessageQueueQuery
