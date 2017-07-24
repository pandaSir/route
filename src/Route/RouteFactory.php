<?php
/**
 * Route (RouteFactory.php) - A fast & flexible router for PHP
 *
 * @author      Panda <187708133004@163.com>
 */

namespace Route;

/**
 * Class RouteFactory
 *
 * @package Route
 */

class RouteFactory extends AbstractRouteFactory
{

    const PATH_VALUE = '*';

    protected function initPath($path)
    {
        return !$path?self::PATH_VALUE:$path;
    }

    public function build($base_dir = './' , $namespace = null )
    {
        $this->namespace = $namespace;
        $this->base_dir = $base_dir;
        return $this;
    }
}