<?php

/* ===========================================================================
 * Copyright (c) 2018-2021 Zindex Software
 *
 * Licensed under the MIT License
 * =========================================================================== */
namespace EcomailDeps\Opis\Closure;

/**
 * Serialize
 *
 * @param mixed $data
 * @return string
 */
function serialize($data)
{
    \EcomailDeps\Opis\Closure\SerializableClosure::enterContext();
    \EcomailDeps\Opis\Closure\SerializableClosure::wrapClosures($data);
    $data = \serialize($data);
    \EcomailDeps\Opis\Closure\SerializableClosure::exitContext();
    return $data;
}
/**
 * Unserialize
 *
 * @param string $data
 * @param array|null $options
 * @return mixed
 */
function unserialize($data, array $options = null)
{
    \EcomailDeps\Opis\Closure\SerializableClosure::enterContext();
    $data = $options === null || \PHP_MAJOR_VERSION < 7 ? \unserialize($data) : \unserialize($data, $options);
    \EcomailDeps\Opis\Closure\SerializableClosure::unwrapClosures($data);
    \EcomailDeps\Opis\Closure\SerializableClosure::exitContext();
    return $data;
}
