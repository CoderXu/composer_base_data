<?php
/**
 * Created by PhpStorm.
 * User: coderxu
 * Date: 2019/3/27
 * Time: 6:14 PM
 */

use Illuminate\Support\Facades\Route;

Route::middleware(\App\Http\Middleware\TokenValidate::class)
    ->prefix('manager')
    ->group(function () {
        // 维修项目分类
        Route::prefix('item_category')
            ->group(function () {
                Route::/*middleware(['permission:item_category_create'])->*/
                post('create', 'ItemCategoryController@create')
                    ->name('创建项目分类');
                Route::/*middleware(['permission:item_category_update'])->*/
                post('update', 'ItemCategoryController@update')
                    ->name('更新项目分类');
                Route::/*middleware(['permission:item_category_query'])->*/
                get('query/{id}', 'ItemCategoryController@query')
                    ->name('根据ID查询项目分类');
                Route::/*middleware(['permission:item_category_all'])->*/
                get('all', 'ItemCategoryController@all')
                    ->name('查询所有项目分类');
                Route::/*middleware(['permission:item_category_search'])->*/
                get('search', 'ItemCategoryController@search')
                    ->name('搜索项目分类');
                Route::/*middleware(['permission:item_category_all_by_pid'])->*/
                get('all/{pid}', 'ItemCategoryController@allByPid')
                    ->name('根据PID查询所有子类');
                Route::/*middleware(['permission:item_category_delete'])->*/
                get('delete/{id}', 'ItemCategoryController@delete')
                    ->name('删除项目分类');
                Route::/*middleware(['permission:item_category_move'])->*/
                get('move/{newPid}/{id}', 'ItemCategoryController@move')
                    ->name('移动项目分类');
            });
        //  维修项目管理
        Route::prefix('item')
            ->group(function () {
                Route::/*middleware(['permission:item_create'])->*/
                post('create', 'ItemController@create')
                    ->name('创建项目');
                Route::/*middleware(['permission:item_update'])->*/
                post('update', 'ItemController@update')
                    ->name('更新项目');
                Route::/*middleware(['permission:item_query'])->*/
                get('query/{id}', 'ItemController@query')
                    ->name('根据ID查询项目');
                Route::/*middleware(['permission:item_all'])->*/
                get('all', 'ItemController@all')
                    ->name('查询所有项目');
                Route::/*middleware(['permission:item_all_by_category'])->*/
                get('all/category/{itemCategoryId}', 'ItemController@allByItemCategory')
                    ->name('查询所有项目');
                Route::/*middleware(['permission:item_search'])->*/
                get('search', 'ItemController@search')
                    ->name('搜索项目');
                Route::/*middleware(['permission:item_delete'])->*/
                get('delete/{id}', 'ItemController@delete')
                    ->name('删除项目');
            });
    });