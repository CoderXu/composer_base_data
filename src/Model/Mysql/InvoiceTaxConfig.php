<?php

namespace App\Model\DB\Mysql;

use App\Utils\AmountUtil;

class InvoiceTaxConfig extends SAASModel
{
    protected $table = 'invoice_tax_config';
    protected $guarded = [];

    public function setValues($params)
    {
        $this->attributes['id'] = $params['invoice_tax_config_id'];
        $this->attributes['tax_compute_mode'] = $params['tax_compute_mode'];
        $this->attributes['value'] = $params['tax_compute_value'];
        return $this;
    }

    /**
     * 根据金额计算税金
     * @param $amount
     * @return float
     */
    public function computeTax($amount)
    {
        $taxAmount = 0;
        switch ($this->attributes['tax_compute_mode']) {
            case 1:
                $taxAmount = $this->computeModeFixed($amount);
                break;
            case 2:
                $taxAmount = $this->computeModePercent($amount);
                break;
            case 3:
                $taxAmount = $this->computeModeDivision($amount);
                break;
            case 0:
                return 0;
        }
        // 不允许为负数
        if (bcsub($amount, $taxAmount, 8) <= 0)
            $this->throwMyException('税金不能大于或等于金额');
        return $taxAmount;
    }

    /**
     * 固定金额方式计算税金
     * @param $amount
     * @return float
     */
    public function computeModeFixed($amount)
    {
        if ($amount > $this->attributes['value'])
            return $this->attributes['value'];
        return $amount;
    }

    /**
     * 价格百分比(不含税)方式计算税金
     * @param $amount
     * @return float
     */
    public function computeModePercent($amount)
    {
        return AmountUtil::mul(
            $amount
            , AmountUtil::div($this->attributes['value'], 100)
        );
    }

    /**
     * 价格百分比(含税)方式计算税金
     * @param $amount
     * @return float
     */
    public function computeModeDivision($amount)
    {
        $rate = AmountUtil::add(
            1
            , AmountUtil::div($this->attributes['value'], 100)
        );
        return AmountUtil::sub(
            $amount
            , AmountUtil::div($amount, $rate)
        );
    }

    public function invoiceMode()
    {
        return $this->hasOne('App\Model\DB\Mysql\InvoiceMode',
            'id', 'invoice_mode_id');
    }
}
