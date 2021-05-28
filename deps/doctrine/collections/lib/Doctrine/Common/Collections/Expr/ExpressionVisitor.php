<?php

namespace EcomailDeps\Doctrine\Common\Collections\Expr;

use RuntimeException;
use function get_class;
/**
 * An Expression visitor walks a graph of expressions and turns them into a
 * query for the underlying implementation.
 */
abstract class ExpressionVisitor
{
    /**
     * Converts a comparison expression into the target query language output.
     *
     * @return mixed
     */
    public abstract function walkComparison(\EcomailDeps\Doctrine\Common\Collections\Expr\Comparison $comparison);
    /**
     * Converts a value expression into the target query language part.
     *
     * @return mixed
     */
    public abstract function walkValue(\EcomailDeps\Doctrine\Common\Collections\Expr\Value $value);
    /**
     * Converts a composite expression into the target query language output.
     *
     * @return mixed
     */
    public abstract function walkCompositeExpression(\EcomailDeps\Doctrine\Common\Collections\Expr\CompositeExpression $expr);
    /**
     * Dispatches walking an expression to the appropriate handler.
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public function dispatch(\EcomailDeps\Doctrine\Common\Collections\Expr\Expression $expr)
    {
        switch (\true) {
            case $expr instanceof \EcomailDeps\Doctrine\Common\Collections\Expr\Comparison:
                return $this->walkComparison($expr);
            case $expr instanceof \EcomailDeps\Doctrine\Common\Collections\Expr\Value:
                return $this->walkValue($expr);
            case $expr instanceof \EcomailDeps\Doctrine\Common\Collections\Expr\CompositeExpression:
                return $this->walkCompositeExpression($expr);
            default:
                throw new \RuntimeException('Unknown Expression ' . \get_class($expr));
        }
    }
}
