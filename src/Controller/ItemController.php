<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\Item;
use App\Model\DB\Mysql\ItemCategory;

/**
 * 维修项目管理
 *
 *
 */
class ItemController extends Controller
{
    protected $mActionTitle = '项目';
    protected $mIsAutoSetNameFirstChar = true;

    public function __construct(Item $item)
    {
        $this->mModel = $item;
        $this->setAllWith(['itemCategory']);
        parent::__construct();
    }

    protected function initPutValidation()
    {
        $this->mValidation = [
            'item_category_id' => [
                'required',
                'integer',
                'min:1',
                'max:4294967295',
            ],
            'sn' => [
                'string',
                'min:1',
                'max:191'
            ],
            'name' => [
                'required',
                'string',
                'min:1',
                'max:191',
            ],
            'norm_man_hour' => [
                'required',
                'numeric',
                'min:0',
                'max:1000000000',
            ],
            'assess_man_hour' => [
                'required',
                'numeric',
                'min:0',
                'max:1000000000',
            ],
            'charge_man_hour' => [
                'required',
                'numeric',
                'min:0',
                'max:1000000000',
            ],
            'man_hour' => [
                'required',
                'numeric',
                'min:0',
                'max:1000000000',
            ]
        ];

        $this->mRequestParamKeys = [
            'item_category_id'
            , 'sn'
            , 'name'
            , 'norm_man_hour'
            , 'assess_man_hour'
            , 'charge_man_hour'
            , 'man_hour'
        ];

        $this->mPutActionParamKeys = $this->mRequestParamKeys;

        $this->mUniqueEloquentFunc = function ($params) {
            $eloquent = $this->mUniqueEloquent->where('name', $params['name']);
            if (isset($params['sn']))
                $eloquent = $eloquent->orWhere(function ($query) use ($params) {
                    $query->where('sn', $params['sn']);
                });
            return $eloquent;
        };
        $this->mUniqueEloquentQueryCallbackFunc = function ($params, $unique) {
            if ($params['name'] === $unique['name']) {
                $this->throwMyException($this->mActionTitle . '名称 ' . $params['name'] . ' 已存在');
            } else if ($params['sn'] === $unique['sn']) {
                $this->throwMyException($this->mActionTitle . '编号 ' . $params['sn'] . ' 已存在');
            }
        };
        $params = parent::initPutValidation();
        $params = $this->validateItemCategory($params);

        return $params;
    }

    protected function validateItemCategory($params)
    {
        $itemCategory = ItemCategory::find($params['item_category_id']);
        if (!$itemCategory)
            $this->throwMyException('项目分类不存在');
        $this->mPutActionParamKeys[] = 'item_category_pid_path';
        $params['item_category_pid_path'] = $itemCategory['pid_path'];
        return $params;
    }

    /**
     * 根据项目分类查找所有项目
     * @param int $itemCategoryId 项目分类ID
     * @return mixed
     */
    public function allByItemCategory($itemCategoryId)
    {
        if (!empty($this->getDefaultQueryAllWith()))
            $this->mModel = $this->mModel->with($this->getDefaultQueryAllWith());
        return $this->mModel
            ->where('item_category_id', $itemCategoryId)
            ->get();
    }

    /**
     * 搜索
     *
     */
    public function search()
    {
        $this->mValidation = [
            'item_category_id' => [
                'integer',
                'min:1',
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
            'item_category_id'
            , 'name'
            , 'name_first_char'
        ]);
        if (count($requestParams) > 0) {
            $this->mValidator($requestParams);
        }
        if (!empty($this->getDefaultSearchWith()))
            $this->mModel = $this->mModel->with($this->getDefaultSearchWith());
        foreach ($requestParams as $key => $value) {
            if (in_array($key, ['name','name_first_char'])) {
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
