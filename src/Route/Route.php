<?php
/**
 * Route (Route.php) - A fast & flexible router for PHP
 *
 * @author      Panda <187708133004@163.com>
 */

namespace Route;

/**
 * Class Route
 *
 * @package Route
 */

class Route
{
    /**
     * @var null namespace
     */
    public $namespace;

    /**
     * The is Route Factory
     *
     * @var RouteFactory
     */
    public $route_factory;

    /**
     * This is All Method in this Array
     *
     * @var array
     */
    protected $method = [];

    protected $path = [];

    protected $class = [];

    protected $action = [];

    protected $this_path;

    public $action_str = '@';

    public $request_url;

    public $request_method;

    public function __construct( $namespace = null )
    {
        $this->namespace = $namespace;
        $this->route_factory = new RouteFactory();
        $this->request_url = strlen($_SERVER['REQUEST_URI']) > 2?rtrim($_SERVER['REQUEST_URI'],'/'):$_SERVER['REQUEST_URI'];
        $this->request_method = strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function addRoute($method, $path, $action)
    {
        $this->this_path = $path;
        $this->method = $this->setMethod($method);
        $this->path = $this->setPath($path);
        $this->action = $this->setAction($action);
    }

    public function setMethod($method)
    {
        $this->method[$this->this_path] = (array) $method;
        return $this->method;
    }

    public function setPath($path)
    {
        if( strlen($path) > 2 ){
            $path = rtrim($path,'/');
        }
        $this->path[$path] = $path;
        return $this->path;
    }

    public function setAction($action)
    {
        if( $action instanceof \Closure ) {
            $this->class[$this->this_path] = '';
            $this->action[$this->this_path] = $action;
        }elseif ( strstr($action,$this->action_str) ){
            list($this->class[$this->this_path],$this->action[$this->this_path]) = explode($this->action_str,$action);
        }
        return $this->action;
    }

   public function __get( $name )
   {
       return $this->$name;
   }

    public function execute()
    {
        if( $this->request_url == $this->path[$this->request_url] ){
            if( in_array($this->request_method,$this->method[$this->request_url]) ){
                $class = $this->class[$this->request_url];
                if( !$class ){
                    $action = $this->action[$this->request_url];
                    $action();
                }else{
                    $class = str_replace('/','\\',$this->namespace.$this->class[$this->request_url]);
                    $action = $this->action[$this->request_url];
                    $this->__autoload($class);
                    return ( new $class() )->$action();
                }
            }else{
                trigger_error('not found method '.$this->request_method.' in this server',E_USER_WARNING);
            }

        }else{
            return http_response_code(404);
        }
    }

    public function __autoload($class_name)
    {
        require_once './'.$class_name.'.php';
    }

}