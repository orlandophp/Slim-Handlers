<?php

namespace Slim\Handlers;

/**
 * The abstract Handler class handles HTTP requests according to their HTTP verb.
 *
 * Extend this class in your own Handlers and define methods corresponding to the HTTP verbs that it needs to handle:
 *
 * ```php
 * <?php // SomeHandler.php
 * class SomeHandler extends \Slim\Handlers\Handler
 * {
 *     function get()
 *     {
 *         $this->app->render('someTemplate.phtml');
 *     }
 * }
 * ```
 */
abstract class Handler
{
    /**
     * @var \Slim\Slim application instance
     */
    protected static $_app;

    /**
     * @param null|\Slim\Slim $app instance to use for Handler instances by default
     */
    static function app ( \Slim\Slim $app = null )
    {
        static::$_app = $app ?: static::$_app;

        return static::$_app;
    }

    /**
     * @param string|\Slim\Handlers\Handler $handler to initialize
     * @param null|\Slim\Slim $app instance to provide to the $handler
     * @return callable for use by \Slim\Slim::map()
     */
    static function factory ( $handler, \Slim\Slim $app = null )
    {
        return function(){
            $args = func_get_args();

            $app = $app ?: static::app();

            if ( !is_object($handler) ) $handler = new $handler($app);

            $handler->handle($args);
        }
    }
    
    /**
     * @param \Slim\Slim $app instance to use with this Handler
     */
    function __construct ( \Slim\Slim $app )
    {
        $this->app = $app;
    }

    /**
     * @param array $args provided by the route
     * @return string|null as expected by \Slim\Slim::map()
     */
    function handle ( array $args = array() )
    {
        $method = $this->app->request()->getMethod();

        $nArgs = count($args);

        if ( !$nArgs ) return $this->$method();

        if ( $nArgs > 5 ) return call_user_func_array(
            array($this, $method), $args
        );

        // Totally okay, just unpacking the $args array; no PHP_WARNING necessary.
        @list( $one, $two, $three, $four, $five ) = $args;

        // Please set your parameter defaults to NULL until I feel like typing more...
        return $this->$method($one, $two, $three, $four, $five);
    }
} // END Handler
