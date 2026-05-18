<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Archive;
use App\Policies\ArchivePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Archive::class => ArchivePolicy::class,
    ];

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerPolicies();
    }

    protected function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
}
