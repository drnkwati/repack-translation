<?php

namespace Closet\Translation\Abstracts;

if (!interface_exists('\Illuminate\Contracts\Translation\Loader') &&
    class_exists('\Illuminate\Translation\ServiceProvider')) {
    class ServiceProviderAbstract extends \Illuminate\Translation\ServiceProvider
    {
        //
    }
}
//
elseif (class_exists('\Illuminate\Support\ServiceProvider')) {
    class ServiceProviderAbstract extends \Illuminate\Support\ServiceProvider
    {
        //
    }
}
//
else {
    class ServiceProviderAbstract
    {
        //
    }
}
