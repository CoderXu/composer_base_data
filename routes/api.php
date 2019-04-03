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

        // 物料分类管理
        Route::prefix('material_category')
            ->group(function () {
                Route::/*middleware(['permission:material_category_create'])->*/
                post('create', 'MaterialCategoryController@create')
                    ->name('创建物料分类');
                Route::/*middleware(['permission:material_category_update'])->*/
                post('update', 'MaterialCategoryController@update')
                    ->name('更新物料分类');
                Route::/*middleware(['permission:material_category_query'])->*/
                get('query/{id}', 'MaterialCategoryController@query')
                    ->name('根据ID查询物料分类');
                Route::/*middleware(['permission:material_category_delete'])->*/
                get('delete/{id}', 'MaterialCategoryController@delete')
                    ->name('删除物料分类');
                Route::/*middleware(['permission:material_category_all'])->*/
                get('all', 'MaterialCategoryController@all')
                    ->name('查询所有物料分类');
                Route::/*middleware(['permission:material_category_search'])->*/
                get('search', 'MaterialCategoryController@search')
                    ->name('物料分类搜索');
                Route::/*middleware(['permission:material_category_move'])->*/
                get('move/{newPid}/{id}', 'MaterialCategoryController@move')
                    ->name('移动物料分类');
                Route::/*middleware(['permission:material_category_query_child'])->*/
                get('query_child/{level}/{pId}', 'MaterialCategoryController@queryChild')
                    ->name('根据等级和上级分类ID查询子类');
                Route::/*middleware(['permission:material_category_query_batch'])->*/
                get('queryBatch/{idStr}', 'MaterialCategoryController@queryBatch')
                    ->name('根据ID批量查询分类');
            });

        // 物料品牌管理
        Route::prefix('material_brand')
            ->group(function () {
                Route::/*middleware(['permission:material_brand_create'])->*/
                post('create', 'MaterialBrandController@create')
                    ->name('创建物料品牌');
                Route::/*middleware(['permission:material_brand_update'])->*/
                post('update', 'MaterialBrandController@update')
                    ->name('更新物料品牌');
                Route::/*middleware(['permission:material_brand_query'])->*/
                get('query/{id}', 'MaterialBrandController@query')
                    ->name('根据ID查询物料品牌');
                Route::/*middleware(['permission:material_brand_delete'])->*/
                get('delete/{id}', 'MaterialBrandController@delete')
                    ->name('删除物料品牌');
                Route::/*middleware(['permission:material_brand_all'])->*/
                get('all', 'MaterialBrandController@all')
                    ->name('查询所有物料品牌');
                Route::/*middleware(['permission:material_brand_search'])->*/
                get('search', 'MaterialBrandController@search')
                    ->name('物料品牌搜索');
                Route::/*middleware(['permission:material_brand_query_batch'])->*/
                get('queryBatch/{idStr}', 'MaterialBrandController@queryBatch')
                    ->name('根据ID批量查询分类');
            });

        // 计量单位管理
        Route::prefix('measure_unit')
            ->group(function () {
                Route::/*middleware(['permission:measure_unit_create'])->*/
                post('create', 'MeasureUnitController@create')
                    ->name('创建计量单位');
                Route::/*middleware(['permission:measure_unit_update'])->*/
                post('update', 'MeasureUnitController@update')
                    ->name('更新计量单位');
                Route::/*middleware(['permission:measure_unit_query'])->*/
                get('query/{id}', 'MeasureUnitController@query')
                    ->name('根据ID查询计量单位');
                Route::/*middleware(['permission:measure_unit_delete'])->*/
                get('delete/{id}', 'MeasureUnitController@delete')
                    ->name('删除计量单位');
                Route::/*middleware(['permission:measure_unit_all'])->*/
                get('all', 'MeasureUnitController@all')
                    ->name('查询所有计量单位');
                Route::/*middleware(['permission:measure_unit_search'])->*/
                get('search', 'MeasureUnitController@search')
                    ->name('计量单位搜索');
            });

        // 支付渠道管理
        Route::prefix('payment_channel')
            ->group(function () {
                Route::/*middleware(['permission:payment_channel_create'])->*/
                post('create', 'PaymentChannelController@create')
                    ->name('创建支付渠道');
                Route::/*middleware(['permission:payment_channel_update'])->*/
                post('update', 'PaymentChannelController@update')
                    ->name('更新支付渠道');
                Route::/*middleware(['permission:payment_channel_delete'])->*/
                get('delete/{id}', 'PaymentChannelController@delete')
                    ->name('删除支付渠道');
                Route::/*middleware(['permission:payment_channel_query'])->*/
                get('query/{id}', 'PaymentChannelController@query')
                    ->name('根据ID查询支付渠道');
                Route::/*middleware(['permission:payment_channel_all'])->*/
                get('all', 'PaymentChannelController@all')
                    ->name('查询所有支付渠道');
                Route::/*middleware(['permission:payment_channel_search'])->*/
                get('search', 'PaymentChannelController@search')
                    ->name('搜索支付渠道');
            });

        // 付款条款管理
        Route::prefix('payment_term')
            ->group(function () {
                Route::/*middleware(['permission:payment_term_create'])->*/
                post('create', 'PaymentTermController@create')
                    ->name('创建付款条款');
                Route::/*middleware(['permission:payment_term_update'])->*/
                post('update', 'PaymentTermController@update')
                    ->name('更新付款条款');
                Route::/*middleware(['permission:payment_term_query'])->*/
                get('query/{id}', 'PaymentTermController@query')
                    ->name('根据ID查询付款条款');
                Route::/*middleware(['permission:payment_term_delete'])->*/
                get('delete/{id}', 'PaymentTermController@delete')
                    ->name('删除付款条款');
                Route::/*middleware(['permission:payment_term_all'])->*/
                get('all', 'PaymentTermController@all')
                    ->name('查询所有付款条款');
                Route::/*middleware(['permission:payment_term_search'])->*/
                get('search', 'PaymentTermController@search')
                    ->name('付款条款搜索');
            });

        // 发票方式管理
        Route::prefix('invoice_mode')
            ->group(function () {
                Route::/*middleware(['permission:invoice_mode_create'])->*/
                post('create', 'InvoiceModeController@create')
                    ->name('创建发票方式');
                Route::/*middleware(['permission:invoice_mode_update'])->*/
                post('update', 'InvoiceModeController@update')
                    ->name('更新发票方式');
                Route::/*middleware(['permission:invoice_mode_query'])->*/
                get('query/{id}', 'InvoiceModeController@query')
                    ->name('根据ID查询发票方式');
                Route::/*middleware(['permission:invoice_mode_delete'])->*/
                get('delete/{id}', 'InvoiceModeController@delete')
                    ->name('删除发票方式');
                Route::/*middleware(['permission:invoice_mode_all'])->*/
                get('all', 'InvoiceModeController@all')
                    ->name('查询所有发票方式');
                Route::/*middleware(['permission:invoice_mode_search'])->*/
                get('search', 'InvoiceModeController@search')
                    ->name('搜索放票方式');
            });

        // 发票税配置管理
        Route::prefix('invoice_tax_config')
            ->group(function () {
                Route::/*middleware(['permission:invoice_tax_config_create'])->*/
                post('create', 'InvoiceTaxConfigController@create')
                    ->name('创建发票税配置');
                Route::/*middleware(['permission:invoice_tax_config_update'])->*/
                post('update', 'InvoiceTaxConfigController@update')
                    ->name('更新发票税配置');
                Route::/*middleware(['permission:invoice_tax_config_query'])->*/
                get('query/{id}', 'InvoiceTaxConfigController@query')
                    ->name('根据ID查询发票税配置');
                Route::/*middleware(['permission:invoice_tax_config_delete'])->*/
                get('delete/{id}', 'InvoiceTaxConfigController@delete')
                    ->name('删除发票税配置');
                Route::/*middleware(['permission:invoice_tax_config_all'])->*/
                get('all', 'InvoiceTaxConfigController@all')
                    ->name('查询所有发票税配置');
                Route::/*middleware(['permission:invoice_tax_config_search'])->*/
                get('search', 'InvoiceTaxConfigController@search')
                    ->name('搜索发票税配置');
            });

        // 业务分类
        Route::prefix('business_category')
            ->group(function () {
                Route::/*middleware(['permission:business_category_create'])->*/
                post('create', 'BusinessCategoryController@create')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:business_category_update'])->*/
                post('update', 'BusinessCategoryController@update')
                    ->name('修改业务分类');
                Route::/*middleware(['permission:business_category_query'])->*/
                get('query/{id}', 'BusinessCategoryController@query')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:business_category_delete'])->*/
                get('delete/{id}', 'BusinessCategoryController@delete')
                    ->name('删除业务分类');
                Route::/*middleware(['permission:business_category_all'])->*/
                get('all', 'BusinessCategoryController@all')
                    ->name('查询所有业务分类');
                Route::/*middleware(['permission:business_category_search'])->*/
                get('search', 'BusinessCategoryController@search')
                    ->name('搜索业务分类');
                Route::/*middleware(['permission:business_category_copy_all'])->*/
                get('copyAll', 'BusinessCategoryController@copyBaseDataAll')
                    ->name('复制所有基础数据');
            });

        // 项目性质
        Route::prefix('item_property')
            ->group(function () {
                Route::/*middleware(['permission:item_property_create'])->*/
                post('create', 'ItemPropertyController@create')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:item_property_update'])->*/
                post('update', 'ItemPropertyController@update')
                    ->name('修改业务分类');
                Route::/*middleware(['permission:item_property_query'])->*/
                get('query/{id}', 'ItemPropertyController@query')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:item_property_delete'])->*/
                get('delete/{id}', 'ItemPropertyController@delete')
                    ->name('删除业务分类');
                Route::/*middleware(['permission:item_property_all'])->*/
                get('all', 'ItemPropertyController@all')
                    ->name('查询所有业务分类');
                Route::/*middleware(['permission:item_property_search'])->*/
                get('search', 'ItemPropertyController@search')
                    ->name('搜索业务分类');
            });

        // 部门
        Route::prefix('department')
            ->group(function () {
                Route::/*middleware(['permission:department_create'])->*/
                post('create', 'DepartmentController@create')
                    ->name('创建部门');
                Route::/*middleware(['permission:department_update'])->*/
                post('update', 'DepartmentController@update')
                    ->name('修改部门');
                Route::/*middleware(['permission:department_query'])->*/
                get('query/{id}', 'DepartmentController@query')
                    ->name('创建部门');
                Route::/*middleware(['permission:department_delete'])->*/
                get('delete/{id}', 'DepartmentController@delete')
                    ->name('删除部门');
                Route::/*middleware(['permission:department_all'])->*/
                get('all', 'DepartmentController@all')
                    ->name('查询所有部门');
                Route::/*middleware(['permission:department_search'])->*/
                get('search', 'DepartmentController@search')
                    ->name('搜索部门');
                Route::/*middleware(['permission:department_move'])->*/
                get('move/{newPid}/{id}', 'DepartmentController@move')
                    ->name('移动部门');
            });

        // 保险类型
        Route::prefix('insurance_category')
            ->group(function () {
                Route::/*middleware(['permission:insurance_category_create'])->*/
                post('create', 'InsuranceCategoryController@create')
                    ->name('创建保险分类');
                Route::/*middleware(['permission:insurance_category_update'])->*/
                post('update', 'InsuranceCategoryController@update')
                    ->name('修改保险分类');
                Route::/*middleware(['permission:insurance_category_query'])->*/
                get('query/{id}', 'InsuranceCategoryController@query')
                    ->name('创建保险分类');
                Route::/*middleware(['permission:insurance_category_delete'])->*/
                get('delete/{id}', 'InsuranceCategoryController@delete')
                    ->name('删除保险分类');
                Route::/*middleware(['permission:insurance_category_all'])->*/
                get('all', 'InsuranceCategoryController@all')
                    ->name('查询所有保险分类');
                Route::/*middleware(['permission:insurance_category_search'])->*/
                get('search', 'InsuranceCategoryController@search')
                    ->name('搜索保险分类');
                Route::/*middleware(['permission:insurance_category_move'])->*/
                get('move/{newPid}/{id}', 'InsuranceCategoryController@move')
                    ->name('移动保险分类');
            });

        // 欠款类型
        Route::prefix('debt_category')
            ->group(function () {
                Route::/*middleware(['permission:debt_category_create'])->*/
                post('create', 'DebtCategoryController@create')
                    ->name('创建欠款分类');
                Route::/*middleware(['permission:debt_category_update'])->*/
                post('update', 'DebtCategoryController@update')
                    ->name('修改欠款分类');
                Route::/*middleware(['permission:debt_category_query'])->*/
                get('query/{id}', 'DebtCategoryController@query')
                    ->name('创建欠款分类');
                Route::/*middleware(['permission:debt_category_delete'])->*/
                get('delete/{id}', 'DebtCategoryController@delete')
                    ->name('删除欠款分类');
                Route::/*middleware(['permission:debt_category_all'])->*/
                get('all', 'DebtCategoryController@all')
                    ->name('查询所有欠款分类');
                Route::/*middleware(['permission:debt_category_search'])->*/
                get('search', 'DebtCategoryController@search')
                    ->name('搜索欠款分类');
            });

        // 工单类型
        Route::prefix('work_order_category')
            ->group(function () {
                Route::/*middleware(['permission:work_order_category_create'])->*/
                post('create', 'WorkOrderCategoryController@create')
                    ->name('创建工单分类');
                Route::/*middleware(['permission:work_order_category_update'])->*/
                post('update', 'WorkOrderCategoryController@update')
                    ->name('修改工单分类');
                Route::/*middleware(['permission:work_order_category_query'])->*/
                get('query/{id}', 'WorkOrderCategoryController@query')
                    ->name('创建工单分类');
                Route::/*middleware(['permission:work_order_category_delete'])->*/
                get('delete/{id}', 'WorkOrderCategoryController@delete')
                    ->name('删除工单分类');
                Route::/*middleware(['permission:work_order_category_all'])->*/
                get('all', 'WorkOrderCategoryController@all')
                    ->name('查询所有工单分类');
                Route::/*middleware(['permission:work_order_category_search'])->*/
                get('search', 'WorkOrderCategoryController@search')
                    ->name('搜索工单分类');
            });

        // 出库类型
        Route::prefix('out_picking_category')
            ->group(function () {
                Route::/*middleware(['permission:out_picking_category_create'])->*/
                post('create', 'OutPickingCategoryController@create')
                    ->name('创建出库分类');
                Route::/*middleware(['permission:out_picking_category_update'])->*/
                post('update', 'OutPickingCategoryController@update')
                    ->name('修改出库分类');
                Route::/*middleware(['permission:out_picking_category_query'])->*/
                get('query/{id}', 'OutPickingCategoryController@query')
                    ->name('创建出库分类');
                Route::/*middleware(['permission:out_picking_category_delete'])->*/
                get('delete/{id}', 'OutPickingCategoryController@delete')
                    ->name('删除出库分类');
                Route::/*middleware(['permission:out_picking_category_all'])->*/
                get('all', 'OutPickingCategoryController@all')
                    ->name('查询所有出库分类');
                Route::/*middleware(['permission:out_picking_category_search'])->*/
                get('search', 'OutPickingCategoryController@search')
                    ->name('搜索出库分类');
            });

        // 退货原因
        Route::prefix('return_reason')
            ->group(function () {
                Route::/*middleware(['permission:return_reason_create'])->*/
                post('create', 'ReturnReasonController@create')
                    ->name('创建退货原因');
                Route::/*middleware(['permission:return_reason_update'])->*/
                post('update', 'ReturnReasonController@update')
                    ->name('修改退货原因');
                Route::/*middleware(['permission:return_reason_query'])->*/
                get('query/{id}', 'ReturnReasonController@query')
                    ->name('创建退货原因');
                Route::/*middleware(['permission:return_reason_delete'])->*/
                get('delete/{id}', 'ReturnReasonController@delete')
                    ->name('删除退货原因');
                Route::/*middleware(['permission:return_reason_all'])->*/
                get('all', 'ReturnReasonController@all')
                    ->name('查询所有退货原因');
                Route::/*middleware(['permission:return_reason_search'])->*/
                get('search', 'ReturnReasonController@search')
                    ->name('搜索退货原因');
            });

        // 卡类型
        Route::prefix('card_category')
            ->group(function () {
                Route::/*middleware(['permission:card_category_create'])->*/
                post('create', 'CardCategoryController@create')
                    ->name('创建卡分类');
                Route::/*middleware(['permission:card_category_update'])->*/
                post('update', 'CardCategoryController@update')
                    ->name('修改卡分类');
                Route::/*middleware(['permission:card_category_query'])->*/
                get('query/{id}', 'CardCategoryController@query')
                    ->name('创建卡分类');
                Route::/*middleware(['permission:card_category_delete'])->*/
                get('delete/{id}', 'CardCategoryController@delete')
                    ->name('删除卡分类');
                Route::/*middleware(['permission:card_category_all'])->*/
                get('all', 'CardCategoryController@all')
                    ->name('查询所有卡分类');
                Route::/*middleware(['permission:card_category_search'])->*/
                get('search', 'CardCategoryController@search')
                    ->name('搜索卡分类');
            });

        // 身份类型
        Route::prefix('id_category')
            ->group(function () {
                Route::/*middleware(['permission:id_category_create'])->*/
                post('create', 'IdCategoryController@create')
                    ->name('创建证件类型');
                Route::/*middleware(['permission:id_category_update'])->*/
                post('update', 'IdCategoryController@update')
                    ->name('修改证件类型');
                Route::/*middleware(['permission:id_category_query'])->*/
                get('query/{id}', 'IdCategoryController@query')
                    ->name('创建证件类型');
                Route::/*middleware(['permission:id_category_delete'])->*/
                get('delete/{id}', 'IdCategoryController@delete')
                    ->name('删除证件类型');
                Route::/*middleware(['permission:id_category_all'])->*/
                get('all', 'IdCategoryController@all')
                    ->name('查询所有证件类型');
                Route::/*middleware(['permission:id_category_search'])->*/
                get('search', 'IdCategoryController@search')
                    ->name('搜索证件类型');
            });

        // 门店等级
        Route::prefix('store_level')
            ->group(function () {
                Route::/*middleware(['permission:store_level_create'])->*/
                post('create', 'StoreLevelController@create')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:store_level_update'])->*/
                post('update', 'StoreLevelController@update')
                    ->name('修改业务分类');
                Route::/*middleware(['permission:store_level_query'])->*/
                get('query/{id}', 'StoreLevelController@query')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:store_level_delete'])->*/
                get('delete/{id}', 'StoreLevelController@delete')
                    ->name('删除业务分类');
                Route::/*middleware(['permission:store_level_all'])->*/
                get('all', 'StoreLevelController@all')
                    ->name('查询所有业务分类');
                Route::/*middleware(['permission:store_level_search'])->*/
                get('search', 'StoreLevelController@search')
                    ->name('搜索业务分类');
            });

        // 保险公司
        Route::prefix('insurance_company')
            ->group(function () {
                Route::/*middleware(['permission:insurance_company_create'])->*/
                post('create', 'InsuranceCompanyController@create')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:insurance_company_update'])->*/
                post('update', 'InsuranceCompanyController@update')
                    ->name('修改业务分类');
                Route::/*middleware(['permission:insurance_company_query'])->*/
                get('query/{id}', 'InsuranceCompanyController@query')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:insurance_company_delete'])->*/
                get('delete/{id}', 'InsuranceCompanyController@delete')
                    ->name('删除业务分类');
                Route::/*middleware(['permission:insurance_company_all'])->*/
                get('all', 'InsuranceCompanyController@all')
                    ->name('查询所有业务分类');
                Route::/*middleware(['permission:insurance_company_search'])->*/
                get('search', 'InsuranceCompanyController@search')
                    ->name('搜索业务分类');
            });

        // 包装方式
        Route::prefix('packaging_category')
            ->group(function () {
                Route::/*middleware(['permission:packaging_category_create'])->*/
                post('create', 'PackagingCategoryController@create')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:packaging_category_update'])->*/
                post('update', 'PackagingCategoryController@update')
                    ->name('修改业务分类');
                Route::/*middleware(['permission:packaging_category_query'])->*/
                get('query/{id}', 'PackagingCategoryController@query')
                    ->name('创建业务分类');
                Route::/*middleware(['permission:packaging_category_delete'])->*/
                get('delete/{id}', 'PackagingCategoryController@delete')
                    ->name('删除业务分类');
                Route::/*middleware(['permission:packaging_category_all'])->*/
                get('all', 'PackagingCategoryController@all')
                    ->name('查询所有业务分类');
                Route::/*middleware(['permission:packaging_category_search'])->*/
                get('search', 'PackagingCategoryController@search')
                    ->name('搜索业务分类');
            });
    });