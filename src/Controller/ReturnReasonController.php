<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\ReturnReason;

class ReturnReasonController extends Controller
{
    protected $mActionTitle = '退货原因';
    protected $mIsAutoSetNameFirstChar = true;

    public function __construct(ReturnReason $returnReason)
    {
        $this->mModel = $returnReason;
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
            'description' => [
                'nullable',
                'string',
                'max:191'
            ],
            'remark' => [
                'nullable',
                'string',
                'max:191'
            ]
        ];

        $this->mRequestParamKeys = [
            'name',
            'description',
            'remark',
        ];
        $this->mUniqueEloquentFunc = function ($params) {
            return $this->mUniqueEloquent
                ->where('name', $params['name']);
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
            ]
        ];
        $requestParams = $this->mRequest->only(['name']);
        if (count($requestParams) > 0) {
            $this->mValidator($requestParams);
        }
        if (isset($requestParams['name'])) {
            $this->mModel = $this->mModel
                ->where('name'
                    , 'like'
                    , '%' . $requestParams['name'] . '%');
        }
        return $this->mModel
            ->orderByDesc('updated_at')
            ->paginate();
    }
}
