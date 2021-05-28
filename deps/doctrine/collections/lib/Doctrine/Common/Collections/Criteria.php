<?php

namespace EcomailDeps\Doctrine\Common\Collections;

use EcomailDeps\Doctrine\Common\Collections\Expr\CompositeExpression;
use EcomailDeps\Doctrine\Common\Collections\Expr\Expression;
use function array_map;
use function strtoupper;
/**
 * Criteria for filtering Selectable collections.
 */
class Criteria
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    /** @var ExpressionBuilder|null */
    private static $expressionBuilder;
    /** @var Expression|null */
    private $expression;
    /** @var string[] */
    private $orderings = [];
    /** @var int|null */
    private $firstResult;
    /** @var int|null */
    private $maxResults;
    /**
     * Creates an instance of the class.
     *
     * @return Criteria
     */
    public static function create()
    {
        return new static();
    }
    /**
     * Returns the expression builder.
     *
     * @return ExpressionBuilder
     */
    public static function expr()
    {
        if (self::$expressionBuilder === null) {
            self::$expressionBuilder = new \EcomailDeps\Doctrine\Common\Collections\ExpressionBuilder();
        }
        return self::$expressionBuilder;
    }
    /**
     * Construct a new Criteria.
     *
     * @param string[]|null $orderings
     * @param int|null      $firstResult
     * @param int|null      $maxResults
     */
    public function __construct(?\EcomailDeps\Doctrine\Common\Collections\Expr\Expression $expression = null, ?array $orderings = null, $firstResult = null, $maxResults = null)
    {
        $this->expression = $expression;
        $this->setFirstResult($firstResult);
        $this->setMaxResults($maxResults);
        if ($orderings === null) {
            return;
        }
        $this->orderBy($orderings);
    }
    /**
     * Sets the where expression to evaluate when this Criteria is searched for.
     *
     * @return Criteria
     */
    public function where(\EcomailDeps\Doctrine\Common\Collections\Expr\Expression $expression)
    {
        $this->expression = $expression;
        return $this;
    }
    /**
     * Appends the where expression to evaluate when this Criteria is searched for
     * using an AND with previous expression.
     *
     * @return Criteria
     */
    public function andWhere(\EcomailDeps\Doctrine\Common\Collections\Expr\Expression $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }
        $this->expression = new \EcomailDeps\Doctrine\Common\Collections\Expr\CompositeExpression(\EcomailDeps\Doctrine\Common\Collections\Expr\CompositeExpression::TYPE_AND, [$this->expression, $expression]);
        return $this;
    }
    /**
     * Appends the where expression to evaluate when this Criteria is searched for
     * using an OR with previous expression.
     *
     * @return Criteria
     */
    public function orWhere(\EcomailDeps\Doctrine\Common\Collections\Expr\Expression $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }
        $this->expression = new \EcomailDeps\Doctrine\Common\Collections\Expr\CompositeExpression(\EcomailDeps\Doctrine\Common\Collections\Expr\CompositeExpression::TYPE_OR, [$this->expression, $expression]);
        return $this;
    }
    /**
     * Gets the expression attached to this Criteria.
     *
     * @return Expression|null
     */
    public function getWhereExpression()
    {
        return $this->expression;
    }
    /**
     * Gets the current orderings of this Criteria.
     *
     * @return string[]
     */
    public function getOrderings()
    {
        return $this->orderings;
    }
    /**
     * Sets the ordering of the result of this Criteria.
     *
     * Keys are field and values are the order, being either ASC or DESC.
     *
     * @see Criteria::ASC
     * @see Criteria::DESC
     *
     * @param string[] $orderings
     *
     * @return Criteria
     */
    public function orderBy(array $orderings)
    {
        $this->orderings = \array_map(static function (string $ordering) : string {
            return \strtoupper($ordering) === \EcomailDeps\Doctrine\Common\Collections\Criteria::ASC ? \EcomailDeps\Doctrine\Common\Collections\Criteria::ASC : \EcomailDeps\Doctrine\Common\Collections\Criteria::DESC;
        }, $orderings);
        return $this;
    }
    /**
     * Gets the current first result option of this Criteria.
     *
     * @return int|null
     */
    public function getFirstResult()
    {
        return $this->firstResult;
    }
    /**
     * Set the number of first result that this Criteria should return.
     *
     * @param int|null $firstResult The value to set.
     *
     * @return Criteria
     */
    public function setFirstResult($firstResult)
    {
        $this->firstResult = $firstResult === null ? null : (int) $firstResult;
        return $this;
    }
    /**
     * Gets maxResults.
     *
     * @return int|null
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }
    /**
     * Sets maxResults.
     *
     * @param int|null $maxResults The value to set.
     *
     * @return Criteria
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults === null ? null : (int) $maxResults;
        return $this;
    }
}
