<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\InvoiceMode;

class InvoiceModeController extends Controller
{
    protected $mActionTitle = '发票方式';
    protected $mIsAutoSetNameFirstChar = true;

    public function __construct(InvoiceMode $invoiceMode)
    {
        $this->mModel = $invoiceMode;
        parent::__construct();
    }

    public function initPutValidation()
    {
        $this->mValidation = [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:191'
            ],
            'code' => [
                'required',
                'integer',
                'min:0',
                'max:100000',
            ],
            'is_tax' => [
                'required',
                'boolean',
            ]
        ];

        $this->mRequestParamKeys = [
            'name',
            'code',
            'is_tax'
        ];
        $this->mUniqueEloquentFunc = function ($params) {
            return $this->mUniqueEloquent->where('name', $params['name']);
        };
        return parent::initPutValidation();
    }

    public function search()
    {
        $this->mValidation = [
            'name' => [
                'nullable',
                'string',
//                'required',
                'min:1',
                'max:191'
            ],
            'name_first_char' => [
                'nullable',
                'string',
                'min:1',
                'max:191'
            ]
        ];
        $requestParams = $this->mRequest->only([
            'name'
            , 'name_first_char'
        ]);
        if (count($requestParams) > 0) {
            $this->mValidator($requestParams);
        }
        foreach ($requestParams as $key => $value) {
            if (in_array($key, ['name', 'name_first_char'])) {
                $this->mModel = $this->mModel
                    ->where($key
                        , 'like'
                        , '%' . $value . '%');
            } else {
                $this->mModel = $this->mModel
                    ->where($key, $value);
            }
        }
        return $this->mModel
            ->orderByDesc('updated_at')
            ->paginate();
    }
}
