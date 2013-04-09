Slim-Handlers
=============

I work in a lot in PHP, Python, and Javascript. In PHP, I really like [the Slim micro-framework](/codeguy/Slim), because I write a lot of APIs. Slim gives me just enough facile syntax for writing a great ReSTful API without making me work too hard or use some full stack of components that maybe I don't need or care about.

In Python, I get the same enjoyment from using [the webapp micro-framework for Google App Engine](https://developers.google.com/appengine/docs/python/gettingstarted/usingwebapp) (and [webapp2](https://developers.google.com/appengine/docs/python/gettingstartedpython27/usingwebapp), yes). Primarily, the `webapp.RequestHandler` pattern is a well encapsulated mechanism for defining ReSTful APIs.

I want that in PHP.

## Here's how it works...

```php
<?php // index.php

require_once 'vendor/autoload.php';

\Slim\Handlers::app($app = new \Slim\Slim());

$app->map('/some/route', \Slim\Handlers::handler('SomeHandler'));

$app->run();

// elsewhere, perhaps SomeHandler.php
class SomeHandler extends \Slim\Handlers\Handler
{
    function get()
    {
        $this->app->render('someTemplate.phtml');
    }
    
    function post()
    {
        $this->app->redirect('/some/route');
    }
}
```

In our super-simple example, the Slim application will respond to `GET /some/route` and `POST /some/route` but not `PUT`, `DELETE` or other requests. Note that the application instance is available as a property of the Handler for convenience. The Handler methods correspond to the HTTP verb they serve, and the `\Slim\Handlers::handler()` static method acts as a factory for instantiating a callable instance of `\Slim\Handlers\Handler`.
