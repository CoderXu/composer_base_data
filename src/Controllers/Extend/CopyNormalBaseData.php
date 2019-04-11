<?php
/**
 * Created by PhpStorm.
 * User: coderxu
 * Date: 2019/4/2
 * Time: 4:44 PM
 */

namespace App\Http\Controllers\Extend;

use Illuminate\Http\Request;
use App\Utils\GRpc\JwtAuthClientUtil;
use Proto\UserData;

trait CopyNormalBaseData
{
    protected $mIsTransactionCopyBaseData = false;

    /**
     * 验证是否可复制标准数据
     *
     *
     */
    protected function isAllowCopyBaseData()
    {
        $this->initTestCopyMode();
        if ($this->getTenantId() == 0)
            $this->throwMyException("不允许复制");
    }

    private function initTestCopyMode()
    {
        if (!config('base_data.test_copy_mode')) return;
        $userData = new UserData();
        $userData->setSysId('0');
        $userData->setTenantId('1');
        $userData->setStoreId('1');
        $userData->setUserId('1');
        JwtAuthClientUtil::getInstance()->setPayload($userData);
    }

    /**
     * 复制基础数据
     *
     * @param $eloquent
     * @return array
     * @throws \Throwable
     */
    public function copyBaseDataAll($eloquent = null)
    {
        set_time_limit(600);
        $this->isAllowCopyBaseData();
        if ($eloquent == null)
            $eloquent = $this->mModel;
        // 查询标准数据
        $normalBaseData = $eloquent
            ->withoutGlobalScope('saas')
            ->where('tenant_id', 0)
            ->where('store_id', 0)
            ->get()
            ->toArray();
        // 写入当前登陆用户所在租户, 并加入标准数据ID
        $transactionFunc = function () use ($normalBaseData) {
            $results = [
                'success' => [],
                'failure' => [],
            ];
            foreach ($normalBaseData as $key => $data) {
                try {
                    $this->doCopyBaseData($data);
                    $results['success'][] = $data;
                } catch (\Exception $e) {
                    if ($this->mIsTransactionCopyBaseData) {
                        $this->throwMyException($e->getMessage());
                    }
                    $data['err_msg'] = $e->getMessage();
                    $results['failure'][] = $data;
                }
            }
            return $results;
        };

        if ($this->mIsTransactionCopyBaseData)
            return \DB::transaction($transactionFunc);

        return $transactionFunc();
    }

    /**
     * 执行数据复制
     *
     * @param array $data 要复制的数据
     * @return mixed
     */
    public function doCopyBaseData($data)
    {
//        dd($data);
        // 初始化request
        $request = new Request();
        $request->offsetSet('normal_id', $data['id']);
        foreach ($data as $key => $value) {
            if (in_array(
                $key
                , [
                    'id'
                    , 'normal_id'
                    , 'name_first_char'
                    , 'created_at'
                    , 'updated_at'
                    , 'deleted_at'
                ]
            )) continue;
            $request->offsetSet($key, $value);
        }
        $this->setRequest($request);
        return $this->create();
    }
}