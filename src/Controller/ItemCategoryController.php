<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\ItemCategory;

class ItemCategoryController extends Controller
{
    protected $mActionTitle = '项目分类';
    protected $mIsInfiniteCategory = true;
    protected $mInfiniteCategoryParentName = 'pid';
    protected $mIsInfiniteCategoryLevel = true;
    protected $mIsAutoSetNameFirstChar = true;

    public function __construct(ItemCategory $itemCategory)
    {
        $this->mModel = $itemCategory;
        return parent::__construct();
    }

    public function initPutValidation()
    {
        $this->mValidation = [
            'pid' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295'
            ],
            'name' => [
                'required',
                'string',
                'min:1',
                'max:191',
            ]
        ];

        $this->mRequestParamKeys = [
            'name'
        ];
        if ($this->mIsUpdateAction) {
            unset($this->mValidation[$this->mInfiniteCategoryParentName]);
        } else {
            $this->mRequestParamKeys[] = 'pid';
        }

        $this->mPutActionParamKeys = $this->mRequestParamKeys;
        $this->mPutActionParamKeys[] = 'pid_path';
        $this->mPutActionParamKeys[] = 'level';
        $this->mUniqueEloquentFunc = function ($params) {
            if ($this->mIsUpdateAction) {
                $model = $this->mModel->find($params[$this->mModelPK]);
                if (!$model) $this->throwMyException($this->mActionTitle . '不存在');
                $params[$this->mInfiniteCategoryLevelName] = $model[$this->mInfiniteCategoryLevelName];
            }

            return $this->mUniqueEloquent
                ->where($this->mInfiniteCategoryLevelName, $params[$this->mInfiniteCategoryLevelName])
                ->where('name', $params['name']);
        };
        return parent::initPutValidation();
    }

    public function allByPid($pid)
    {
        return $this->mModel
            ->where('pid', $pid)
            ->get();
    }

    public function search()
    {
        $this->mValidation = [
            'pid' => [
                'integer',
                'min:0',
                'max:4294967295'
            ],
            'name' => [
                'nullable',
                'string',
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
            'pid'
            , 'name'
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
                        , '%' . $requestParams['name'] . '%');
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
