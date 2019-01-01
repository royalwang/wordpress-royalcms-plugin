<?php

/*
|--------------------------------------------------------------------------
| Create The Royalcms
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$royalcms = new Royalcms\Component\Foundation\Royalcms(
    SITE_ROOT
);

/*
|--------------------------------------------------------------------------
| Return The Royalcms
|--------------------------------------------------------------------------
|
| This script returns the royalcms instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $royalcms;
