<?php

namespace App\Http\Controllers;


use App\Model\DB\Mysql\PaymentChannel;

class PaymentChannelController extends Controller
{
    protected $mActionTitle = '支付渠道';

    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->mModel = $paymentChannel;
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
            ]
        ];

        $this->mRequestParamKeys = ['name'];

        $this->mUniqueEloquentFunc = function ($params) {
            return $this->mUniqueEloquent->where('name', $params['name']);
        };

        return parent::initPutValidation();
    }

    public function search()
    {
        $this->mValidation = [
            'name' => [
                'string',
                'min:1',
                'max:191'
            ]
        ];

        $params = $this->mRequest->only([
            'name'
        ]);
        if (count($params) > 0)
            $this->mValidator($params);
        $model = $this->mModel;
        foreach ($params as $key => $value) {
            if (in_array($key, ['name'])) {
                $model = $model->where(
                    $key
                    , 'like'
                    , '%' . $value . '%');
            } else {
                $model = $model->where($key, $value);
            }
        }
        return $model
            ->orderByDesc('updated_at')
            ->paginate();
    }
}
