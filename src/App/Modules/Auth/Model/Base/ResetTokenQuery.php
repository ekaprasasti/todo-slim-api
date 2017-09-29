<?php

namespace App\Modules\Auth\Model\Base;

use \Exception;
use \PDO;
use App\Modules\Auth\Model\ResetToken as ChildResetToken;
use App\Modules\Auth\Model\ResetTokenQuery as ChildResetTokenQuery;
use App\Modules\Auth\Model\Map\ResetTokenTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'reset_tokens' table.
 *
 *
 *
 * @method     ChildResetTokenQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildResetTokenQuery orderByToken($order = Criteria::ASC) Order by the token column
 * @method     ChildResetTokenQuery orderByExpiredAt($order = Criteria::ASC) Order by the expired_at column
 *
 * @method     ChildResetTokenQuery groupByEmail() Group by the email column
 * @method     ChildResetTokenQuery groupByToken() Group by the token column
 * @method     ChildResetTokenQuery groupByExpiredAt() Group by the expired_at column
 *
 * @method     ChildResetTokenQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildResetTokenQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildResetTokenQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildResetTokenQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildResetTokenQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildResetTokenQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildResetToken findOne(ConnectionInterface $con = null) Return the first ChildResetToken matching the query
 * @method     ChildResetToken findOneOrCreate(ConnectionInterface $con = null) Return the first ChildResetToken matching the query, or a new ChildResetToken object populated from the query conditions when no match is found
 *
 * @method     ChildResetToken findOneByEmail(string $email) Return the first ChildResetToken filtered by the email column
 * @method     ChildResetToken findOneByToken(string $token) Return the first ChildResetToken filtered by the token column
 * @method     ChildResetToken findOneByExpiredAt(string $expired_at) Return the first ChildResetToken filtered by the expired_at column *

 * @method     ChildResetToken requirePk($key, ConnectionInterface $con = null) Return the ChildResetToken by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResetToken requireOne(ConnectionInterface $con = null) Return the first ChildResetToken matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildResetToken requireOneByEmail(string $email) Return the first ChildResetToken filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResetToken requireOneByToken(string $token) Return the first ChildResetToken filtered by the token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildResetToken requireOneByExpiredAt(string $expired_at) Return the first ChildResetToken filtered by the expired_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildResetToken[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildResetToken objects based on current ModelCriteria
 * @method     ChildResetToken[]|ObjectCollection findByEmail(string $email) Return ChildResetToken objects filtered by the email column
 * @method     ChildResetToken[]|ObjectCollection findByToken(string $token) Return ChildResetToken objects filtered by the token column
 * @method     ChildResetToken[]|ObjectCollection findByExpiredAt(string $expired_at) Return ChildResetToken objects filtered by the expired_at column
 * @method     ChildResetToken[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ResetTokenQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \App\Modules\Auth\Model\Base\ResetTokenQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'freedom', $modelName = '\\App\\Modules\\Auth\\Model\\ResetToken', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildResetTokenQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildResetTokenQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildResetTokenQuery) {
            return $criteria;
        }
        $query = new ChildResetTokenQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$email, $token] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildResetToken|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ResetTokenTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ResetTokenTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildResetToken A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT `email`, `token`, `expired_at` FROM `reset_tokens` WHERE `email` = :p0 AND `token` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_STR);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildResetToken $obj */
            $obj = new ChildResetToken();
            $obj->hydrate($row);
            ResetTokenTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildResetToken|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildResetTokenQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(ResetTokenTableMap::COL_EMAIL, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(ResetTokenTableMap::COL_TOKEN, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildResetTokenQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(ResetTokenTableMap::COL_EMAIL, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(ResetTokenTableMap::COL_TOKEN, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResetTokenQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResetTokenTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the token column
     *
     * Example usage:
     * <code>
     * $query->filterByToken('fooValue');   // WHERE token = 'fooValue'
     * $query->filterByToken('%fooValue%', Criteria::LIKE); // WHERE token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $token The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResetTokenQuery The current query, for fluid interface
     */
    public function filterByToken($token = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($token)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResetTokenTableMap::COL_TOKEN, $token, $comparison);
    }

    /**
     * Filter the query on the expired_at column
     *
     * Example usage:
     * <code>
     * $query->filterByExpiredAt('2011-03-14'); // WHERE expired_at = '2011-03-14'
     * $query->filterByExpiredAt('now'); // WHERE expired_at = '2011-03-14'
     * $query->filterByExpiredAt(array('max' => 'yesterday')); // WHERE expired_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $expiredAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildResetTokenQuery The current query, for fluid interface
     */
    public function filterByExpiredAt($expiredAt = null, $comparison = null)
    {
        if (is_array($expiredAt)) {
            $useMinMax = false;
            if (isset($expiredAt['min'])) {
                $this->addUsingAlias(ResetTokenTableMap::COL_EXPIRED_AT, $expiredAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($expiredAt['max'])) {
                $this->addUsingAlias(ResetTokenTableMap::COL_EXPIRED_AT, $expiredAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ResetTokenTableMap::COL_EXPIRED_AT, $expiredAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildResetToken $resetToken Object to remove from the list of results
     *
     * @return $this|ChildResetTokenQuery The current query, for fluid interface
     */
    public function prune($resetToken = null)
    {
        if ($resetToken) {
            $this->addCond('pruneCond0', $this->getAliasedColName(ResetTokenTableMap::COL_EMAIL), $resetToken->getEmail(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(ResetTokenTableMap::COL_TOKEN), $resetToken->getToken(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the reset_tokens table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ResetTokenTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ResetTokenTableMap::clearInstancePool();
            ResetTokenTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ResetTokenTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ResetTokenTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ResetTokenTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ResetTokenTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ResetTokenQuery
