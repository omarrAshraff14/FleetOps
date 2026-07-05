<?php
if (!function_exists('currentTenant')) {
    function currentTenant()
    {
        return app('currentTenant');
    }
}