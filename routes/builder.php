<?php

use HansSchouten\LaravelPageBuilder\LaravelPageBuilder;
use Illuminate\Support\Facades\Route;

if (config('pagebuilder.use_pagebuilder'))
    Route::prefix('builder')->middleware('admin.super')->group(function () {
        Route::any('/{any}', function () {
            $builder = new LaravelPageBuilder(config('pagebuilder'));
            $builder->handleRequest();
        });
    });

// pass all remaining requests to the LaravelPageBuilder router
if (config('pagebuilder.show_pagebuilder_pages')) {
    Route::any('pages/{any}', function () {

        $builder = new LaravelPageBuilder(config('pagebuilder'));
        $hasPageReturned = $builder->handlePublicRequest();

        if (! $hasPageReturned) {
            return redirect('/');
        }
    })->where('any', '.*');
}
