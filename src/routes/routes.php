<?php

Route::group(['prefix' => 'api/seleksi', 'middleware' => ['web']], function() {
    $controllers = (object) [
        'index'     => 'Bantenprov\Seleksi\Http\Controllers\SeleksiController@index',
        'create'    => 'Bantenprov\Seleksi\Http\Controllers\SeleksiController@create',
        'show'      => 'Bantenprov\Seleksi\Http\Controllers\SeleksiController@show',
        'store'     => 'Bantenprov\Seleksi\Http\Controllers\SeleksiController@store',
        'edit'      => 'Bantenprov\Seleksi\Http\Controllers\SeleksiController@edit',
        'update'    => 'Bantenprov\Seleksi\Http\Controllers\SeleksiController@update',
        'destroy'   => 'Bantenprov\Seleksi\Http\Controllers\SeleksiController@destroy',
    ];

    Route::get('/',             $controllers->index)->name('seleksi.index');
    Route::get('/create',       $controllers->create)->name('seleksi.create');
    Route::get('/{id}',         $controllers->show)->name('seleksi.show');
    Route::post('/',            $controllers->store)->name('seleksi.store');
    Route::get('/{id}/edit',    $controllers->edit)->name('seleksi.edit');
    Route::put('/{id}',         $controllers->update)->name('seleksi.update');
    Route::delete('/{id}',      $controllers->destroy)->name('seleksi.destroy');
});
