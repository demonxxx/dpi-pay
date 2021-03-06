<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('shop', function ($user) {
            return $user->hasRole(['shop','admin']);

        });

        $gate->define('shipper', function ($user) {
            return $user->hasRole(['shipper','admin']);
        });

        $gate->define('admin', function ($user) {
            return $user->hasRole('admin');
        });

        $gate->define('order-owner', function ($order,$user ) {
            return $user->id === $order->user_id;
        });
    }
}
