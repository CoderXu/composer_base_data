<?php

namespace App\Http\Controllers;

use App\Model\DB\Mysql\MaterialBrand;
use App\Utils\ElasticSearch\MaterialBrandUtil;
use Illuminate\Database\Eloquent\Model;

class MaterialBrandController extends Controller
{
    protected $mActionTitle = '物料品牌';
    protected $mIsTransactionPut = true;
    protected $mIsAutoSetNameFirstChar = true;

    public function __construct(MaterialBrand $materialBrand)
    {
        $this->mModel = $materialBrand;
        parent::__construct();
    }

    public function initPutValidation()
    {
        $this->mValidation = [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:191',
            ],
            'brand_pn' => [
                'nullable',
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
            'name'
            , 'brand_pn'
            , 'description'
            , 'remark'
        ];

        $this->mUniqueEloquentFunc = function ($params) {
            $eloquent = $this->mUniqueEloquent->where('name', $params['name']);
            if (isset($params['brand_pn']) && !empty($params['brand_pn'])) {
                $eloquent->orWhere(function ($query) use ($params) {
                    $query->where('brand_pn', $params['brand_pn'])
                        ->whereNotNull('brand_pn');
                });
            }
            return $eloquent;
        };

        $this->mUniqueEloquentQueryCallbackFunc = function ($params, $unique) {
            if ($params['name'] === $unique['name']) {
                $this->throwMyException($this->mActionTitle . ' ' . $params['name'] . ' 已存在');
            } else if ($unique != null && $params['brand_pn'] === $unique['brand_pn']) {
                $this->throwMyException($this->mActionTitle . '产品编码 ' . $params['brand_pn'] . ' 已存在');
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
            $materialBrandUtil = new MaterialBrandUtil();
            $materialBrandUtil->indexDocument($result->getAttributes());
            return $result;
        };
    }

    protected function initUpdateAfterFunc()
    {
        $this->mUpdateAfterFunc = function ($params, $result) {
            $materialBrandUtil = new MaterialBrandUtil();
            $materialBrandUtil->updateDocument($params);
        };
    }

    public function search()
    {
        $materialBrandUtil = new MaterialBrandUtil();
        return $materialBrandUtil->searchByName();
    }

    public function delete($id)
    {
        $this->initUpdateAfterFunc();
        return parent::delete($id);
    }
}
