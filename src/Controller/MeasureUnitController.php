<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\MeasureUnit;

class MeasureUnitController extends Controller
{
    protected $mActionTitle = '计量单位';
    protected $mIsAutoSetNameFirstChar = true;

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
