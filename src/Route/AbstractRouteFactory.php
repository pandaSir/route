<?php
/**
 * Route (AbstractRouteFactory.php) - A fast & flexible router for PHP
 *
 * @author      Panda <187708133004@163.com>
 */

namespace Route;

/**
 * Class AbstractRouteFactory
 *
 * @package Route
 */

abstract class AbstractRouteFactory
{
    protected $namespace;

    protected $base_dir;

    public function __construct($base_dir = './', $namespace = null)
    {
        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = (string) $namespace;

        return $this;
    }

    abstract public function build($namespace);
}