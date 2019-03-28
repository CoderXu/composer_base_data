<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\MeasureUnit;

class MeasureUnitController extends Controller
{
    protected $mActionTitle = '计量单位';

    public function __construct(MeasureUnit $measureUnit)
    {
        $this->mModel = $measureUnit;
        parent::__construct();
    }

    protected function initPutValidation()
    {
        $this->mValidation = [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:191'
            ]
        ];

        $this->mRequestParamKeys = ['name'];
        $this->mUniqueEloquentFunc = function ($params) {
            return $this->mUniqueEloquent->where('name', $params['name']);
        };
        return parent::initPutValidation();
    }

    /**
     * 搜索
     *
     *
     */
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
