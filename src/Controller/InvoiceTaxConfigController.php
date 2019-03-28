<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\Dictionary;
use App\Model\DB\Mysql\InvoiceMode;
use App\Model\DB\Mysql\InvoiceTaxConfig;

class InvoiceTaxConfigController extends Controller
{
    protected $mActionTitle = '发票税配置';

    public function __construct(InvoiceTaxConfig $invoiceTaxConfig)
    {
        $this->mModel = $invoiceTaxConfig;
        $this->setAllWith(['invoiceMode']);
        parent::__construct();
    }

    public function initPutValidation()
    {
        $this->mValidation = [
            'invoice_mode_id' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
            'tax_use_type' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
            'tax_compute_mode' => [
                'required',
                'integer',
                'min:1',
                'max:3',
            ],
            'value' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999999'
            ],
            'name' => [
                'required',
                'string',
                'min:2',
                'max:191'
            ]
        ];
        $this->mRequestParamKeys = [
            'invoice_mode_id'
            , 'tax_use_type'
            , 'tax_compute_mode'
            , 'value'
            , 'name'
        ];

        $this->mUniqueEloquentFunc = function ($params) {
            return $this->mUniqueEloquent
                ->where('name', $params['name']);
        };

        $params = parent::initPutValidation();

        $this->validateExists($params);

        return $params;
    }

    private function validateExists($params)
    {
        if ($params['invoice_mode_id'] > 0)
            $this->getInvoiceMode($params['invoice_mode_id']);

        if ($params['tax_use_type'] > 0)
            $this->getDictTaxUseType($params['tax_use_type']);
    }

    private function getInvoiceMode($id)
    {
        $invoiceMode = InvoiceMode::find($id);
        if (!$invoiceMode) $this->throwMyException('发票类型不存在');
        return $invoiceMode;
    }

    private function getDictTaxUseType($value)
    {
        $taxUseType = Dictionary::where('value', $value)
            ->where('key', 'TAX_USE_TYPE')
            ->first();
        if (!$taxUseType) $this->throwMyException('发票税使用范围不存在');
        return $taxUseType;
    }

    public function search()
    {
        $this->mValidation = [
            'invoice_mode_id' => [
//                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
            'tax_use_type' => [
//                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
            'name' => [
                'string',
                'min:1',
                'max:191'
            ]
        ];

        $params = $this->mRequest->only([
            'invoice_mode_id'
            , 'tax_use_type'
            , 'name'
        ]);

        if (count($params) > 0)
            $this->mValidator($params);
        $builder = $this->mModel;
        if (!empty($this->getDefaultSearchWith()))
            $builder = $builder->with($this->getDefaultSearchWith());
        if (isset($params['invoice_mode_id'])) {
            $invoiceModeRow = 'invoice_mode_id = 0';
            if ($params['invoice_mode_id'] > 0) {
                $invoiceModeRow = '('
                    . $invoiceModeRow
                    . ' or invoice_mode_id = '
                    . $params['invoice_mode_id']
                    . ')';
            }
            $builder = $builder->whereRaw($invoiceModeRow);
        }
        if (isset($params['tax_use_type'])) {
            $taxUseTypeRaw = 'tax_use_type = 0';
            if ($params['tax_use_type'] > 0) {
                $taxUseTypeRaw = '('
                    . $taxUseTypeRaw
                    . ' or tax_use_type = '
                    . $params['tax_use_type']
                    . ')';
            }
            $builder = $builder->whereRaw($taxUseTypeRaw);
        }

        if (isset($params['name']))
            $builder = $builder->where('name', 'like', '%' . $params['name'] . '%');

        return $builder->get();
    }
}
