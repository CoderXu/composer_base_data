<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\MaterialCategory;
use App\Utils\ElasticSearch\MaterialCategoryUtil;
use Illuminate\Database\Eloquent\Model;

class MaterialCategoryController extends Controller
{
    protected $mActionTitle = '物料分类';
    protected $mIsInfiniteCategory = true;
    protected $mIsInfiniteCategoryLevel = true;
    protected $mIsAutoSetNameFirstChar = true;

    public function __construct(MaterialCategory $materialCategory)
    {
        $this->mModel = $materialCategory;
        parent::__construct();
    }

    public function initPutValidation()
    {
        $this->mValidation = [
            'p_id' => [
                'required',
                'integer',
                'min:0',
                'max:4294967295',
            ],
            'name' => [
                'string',
                'required',
                'min:1',
                'max:191'
            ],
            'product_type' => [
                'integer',
                'required',
                'min:0',
                'max:1'
            ],
            'hq_pn_category' => [
                'string',
                'min:1',
                'max:191',
            ]
        ];

        $this->mRequestParamKeys = ['name', 'product_type', 'hq_pn_category'];
        if ($this->mIsUpdateAction) {
            unset($this->mValidation[$this->mInfiniteCategoryParentName]);
        } else {
            $this->mRequestParamKeys[] = 'p_id';
            $this->mPutActionParamKeys = $this->mRequestParamKeys;
            $this->mPutActionParamKeys[] = 'pid_path';
            $this->mPutActionParamKeys[] = 'level';
        }
        $this->mUniqueEloquentFunc = function ($params) {
            if ($this->mIsUpdateAction) {
                $model = $this->mModel->find($params[$this->mModelPK]);
                if (!$model) $this->throwMyException($this->mActionTitle . '不存在');
                $params[$this->mInfiniteCategoryLevelName] = $model[$this->mInfiniteCategoryLevelName];
            }

            return $this->mUniqueEloquent
                ->where($this->mInfiniteCategoryLevelName, $params[$this->mInfiniteCategoryLevelName])
                ->where('name', $params['name'])
                ->orWhere(function ($query) use ($params) {
                    if (isset($params['hq_pn_category'])) {
                        $query->where('hq_pn_category', $params['hq_pn_category'])
                            ->whereNotNull('hq_pn_category');
                    }
                });
        };
        $this->mUniqueEloquentQueryCallbackFunc = function ($params, $unique) {
            if (!$unique) return;
            if ($params['name'] === $unique['name']) {
                $this->throwMyException($this->mActionTitle . '名称在当前层级已存在');
            } else if (isset($params['hq_pn_category']) && $params['hq_pn_category'] === $unique['hq_pn_category']) {
                $this->throwMyException($this->mActionTitle . '产品编号已存在');
            }
        };
        $this->initCallbacks();
        $params = parent::initPutValidation();
        return $params;
    }

    protected function initCallbacks()
    {
        if ($this->mIsUpdateAction) {
            $this->initUpdateAfterFunc();
            return;
        }
        $this->mCreateAfterFunc = function ($params, $result) {
            $materialCategoryUtil = new MaterialCategoryUtil();
            $materialCategoryUtil->indexDocument($result->getAttributes());
            return $result;
        };
    }

    protected function initUpdateAfterFunc()
    {
        $this->mUpdateAfterFunc = function ($params, $result) {
            $materialCategoryUtil = new MaterialCategoryUtil();
            $materialCategoryUtil->updateDocument($params);
        };
    }

    /**
     * 根据关键字搜索
     *
     */
    public function search()
    {
        $this->mValidation = [
            'level' => [
                'integer',
                'min:-1',
                'max:2',
            ],
            'p_id' => [
                'integer',
                'min:0',
                'max:4294967295'
            ]
        ];
        $requestParams = $this->mRequest->only(['level', 'p_id']);
        if (count($requestParams) > 0)
            $this->mValidator($requestParams);
        $materialCategoryUtil = new MaterialCategoryUtil();
        // 默认搜三级分类
        $level = isset($requestParams['level']) ? (int)$requestParams['level'] : 2;
        $pid = isset($requestParams['p_id']) ? (int)$requestParams['p_id'] : null;
        return $materialCategoryUtil->search($level, $pid);
    }

    /**
     * 根据level和p_id查询所有子类
     *
     * @param int $level 等级
     * @param int $pId 上一级id
     * @return mixed
     */
    public function queryChild($level, $pId)
    {
        return $this->mModel->where($this->mInfiniteCategoryLevelName, $level)
            ->where($this->mInfiniteCategoryParentName, $pId)
            ->get();
    }

    public function delete($id)
    {
        $this->initUpdateAfterFunc();
        return parent::delete($id);
    }
}
