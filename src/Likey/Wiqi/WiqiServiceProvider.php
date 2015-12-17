<?php
/**
 * Class and Function List:
 * Function list:
 * - boot()
 * - register()
 * - provides()
 * Classes list:
 * - WikipediaServiceProvider extends ServiceProvider
 */
namespace Likey\Wiqi;

use App;
use Illuminate\Support\ServiceProvider;

class WiqiServiceProvider extends ServiceProvider
{
    
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    
    public function boot()
    {
        
        //
        
        
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        
        //
        App::bind('wiqi', function()
        {
            return new \Likey\Wiqi\Wiqi;
        });
        
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['wiqi'];
    }
}
