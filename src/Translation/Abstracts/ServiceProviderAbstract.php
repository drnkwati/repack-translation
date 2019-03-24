<?php

namespace Repack\Translation\Abstracts;

if (!interface_exists('\Illuminate\Contracts\Translation\Loader') &&
    class_exists('\Illuminate\Translation\ServiceProvider')) {
    abstract class ServiceProviderAbstract extends \Illuminate\Translation\ServiceProvider
    {
        //
    }
}
//
elseif (class_exists('\Illuminate\Support\ServiceProvider')) {
    abstract class ServiceProviderAbstract extends \Illuminate\Support\ServiceProvider
    {
        //
    }
}
//
else {
    abstract class ServiceProviderAbstract
    {
        //
    }
}
