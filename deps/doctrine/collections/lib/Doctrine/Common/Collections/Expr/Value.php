<?php

namespace EcomailDeps\Doctrine\Common\Collections\Expr;

class Value implements \EcomailDeps\Doctrine\Common\Collections\Expr\Expression
{
    /** @var mixed */
    private $value;
    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * {@inheritDoc}
     */
    public function visit(\EcomailDeps\Doctrine\Common\Collections\Expr\ExpressionVisitor $visitor)
    {
        return $visitor->walkValue($this);
    }
}
