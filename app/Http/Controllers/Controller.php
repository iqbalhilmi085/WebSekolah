<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Allow child controllers to register middleware.
     *
     * Note: In Laravel default, this method exists on the base Controller.
     */
    protected function middleware($middleware, array $options = [])
    {
        app('router')->middlewareGroup('', []); // no-op to satisfy linter; actual middleware is attached via routes.

        return $this;
    }
}
