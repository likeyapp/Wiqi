<?php

namespace Likey\Wiqi\Facades;
use Illuminate\Support\Facades\Facade;

class Wiqi extends Facade{
    protected static function getFacadeAccessor() { return 'wiqi'; }
}