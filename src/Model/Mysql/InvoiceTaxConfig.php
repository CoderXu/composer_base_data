<?php

namespace App\Model\DB\Mysql;

use App\Utils\AmountUtil;

class InvoiceTaxConfig extends SAASModel
{
    protected $table = 'invoice_tax_config';
    protected $guarded = [];
    public static $TAX_COMPUTE_MODE_FIXED = 1;
    public static $TAX_COMPUTE_MODE_PERCENT = 2;
    public static $TAX_COMPUTE_MODE_DIVISION = 3;
    private $mAmount = 0;

    public function queryById($id)
    {
        $result = $this->find($id);
        if (!$result)
            $this->throwMyException('发票税配置不存在');
        return $result;
    }

    public function setValues($params)
    {
        $this->attributes['id'] = $params['invoice_tax_config_id'];
        $this->attributes['tax_compute_mode'] = $params['tax_compute_mode'];
        $this->attributes['value'] = $params['tax_compute_value'];
        return $this;
    }

    /**
     * 根据金额计算税金
     * @param bool $isFixedIfReturn 主要用于固定税金计算时，是否返回固定税金
     * @return float
     */
    public function computeTax($isFixedIfReturn = true)
    {
        $amount = $this->getAmount();
        $originAmount = $amount;
        if ($amount < 0) $amount = 0 - $amount;
        $taxAmount = 0;
        switch ($this->attributes['tax_compute_mode']) {
            case self::$TAX_COMPUTE_MODE_FIXED:
                $taxAmount = $this->computeModeFixed($amount, $isFixedIfReturn);
                break;
            case self::$TAX_COMPUTE_MODE_PERCENT:
                $taxAmount = $this->computeModePercent($amount);
                break;
            case self::$TAX_COMPUTE_MODE_DIVISION:
                $taxAmount = $this->computeModeDivision($amount);
                break;
            case 0:
                return 0;
        }
        // 不允许为负数
        if (bcsub($amount, $taxAmount, 8) <= 0)
            $this->throwMyException('税金不能大于或等于金额');
        if ($originAmount < 0) $taxAmount = 0 - $taxAmount;
        return $taxAmount;
    }

    /**
     * 固定金额方式计算税金
     * @param bool $isFixedIfReturn
     * @return float
     */
    public function computeModeFixed($isFixedIfReturn = false)
    {
        if ($isFixedIfReturn) return 0;
        //if ($amount > $this->attributes['value'])
        return $this->attributes['value'];
        //return $amount;
    }

    /**
     * 价格百分比(不含税)方式计算税金
     * @return float
     */
    public function computeModePercent()
    {
        return AmountUtil::mul(
            $this->getAmount()
            , AmountUtil::div($this->attributes['value'], 100)
        );
    }

    /**
     * 价格百分比(含税)方式计算税金
     * @return float
     */
    public function computeModeDivision()
    {
        $rate = AmountUtil::add(
            1
            , AmountUtil::div($this->attributes['value'], 100)
        );
        return AmountUtil::sub(
            $this->getAmount()
            , AmountUtil::div($this->getAmount(), $rate)
        );
    }

    /**
     *
     */
    public function getAmount()
    {
        return $this->mAmount;
    }

    /**
     * @param $mAmount
     * @return InvoiceTaxConfig
     */
    public function setAmount($mAmount)
    {
        $this->mAmount = $mAmount;
        return $this;
    }

    public function invoiceMode()
    {
        return $this->hasOne('App\Model\DB\Mysql\InvoiceMode',
            'id', 'invoice_mode_id');
    }
}
