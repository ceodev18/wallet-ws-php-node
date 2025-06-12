<?php

namespace App\Soap;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Soap\Handlers\WalletService;

class SoapServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {        
        Route::get('/soap/server', function () {
            return response()->file(resource_path('wsdl/wallet.wsdl'));
        });
        Route::post('/soap/server', function () {
            ini_set("soap.wsdl_cache_enabled", "0");

            $options = [
                'uri' => 'http://localhost:8000/soap/server',
                'location' => 'http://localhost:8000/soap/server',
            ];

            $server = new \SoapServer(resource_path('wsdl/wallet.wsdl'), $options);
            $server->setObject(new WalletService());
            $server->handle();
        });
    }

}
