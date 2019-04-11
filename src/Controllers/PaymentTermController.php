<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Extend\CopyNormalBaseData;
use App\Model\DB\Mysql\PaymentTerm;

class PaymentTermController extends Controller
{
    use CopyNormalBaseData;

    protected $mActionTitle = '付款条款';
    protected $mIsAutoSetNameFirstChar = true;

    public function __construct(PaymentTerm $paymentTerm)
    {
        $this->mModel = $paymentTerm;
        parent::__construct();
    }

    public function initPutValidation()
    {
        $this->mValidation = [
            'normal_id' => [
                'integer',
                'min:1',
                'max:4294967295',
                $this->saasNormalDataRuleExists(null, 'id')
            ],
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
                "nullable",
                'string',
                'min:1',
                'max:191'
            ]
        ];
        $this->mRequestParamKeys = [
            'name'
            , 'description'
            , 'remark'
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
