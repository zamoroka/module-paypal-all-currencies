<?php

namespace Zamoroka\PayPalAllCurrencies\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface;

/**
 * Class Rates
 *
 * @method int getEntityId()
 * @method int getServiceId()
 * @method int setServiceId($serviceId)
 * @method string getBaseCurrencyCode()
 * @method string setBaseCurrencyCode($baseCurrencyCode)
 * @method string getPaypalCurrencyCode()
 * @method string setPaypalCurrencyCode($paypalCurrencyCode)
 * @method float getRate()
 * @method float setRate($rate)
 * @method string getUpdatedAt()
 * @method string setUpdatedAt($gmtDate)
 * @package Zamoroka\PayPalAllCurrencies\Model
 */
class Rates extends AbstractModel
{
    /** @var \Magento\Framework\Stdlib\DateTime\DateTime|null $dateTime */
    protected $dateTime = null;

    /**
     * @param \Magento\Framework\Stdlib\DateTime\DateTime             $dateTime
     * @param \Magento\Framework\Model\Context                        $context
     * @param \Magento\Framework\Registry                             $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection
     * @param array                                                   $data
     */
    public function __construct(
        DateTime $dateTime,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dateTime = $dateTime;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('Zamoroka\PayPalAllCurrencies\Model\ResourceModel\Rates');
    }

    /**
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface $currencyService
     * @return $this
     */
    public function updateRateFromService(CurrencyServiceInterface $currencyService)
    {
        $baseCurrencyCode = $currencyService->getStoreCurrencyCode();
        $paypalCurrencyCode = $currencyService->getPayPalCurrencyCode();
        $rate = $currencyService->exchangeFromService(1);
        $serviceId = $currencyService->getServiceId();

        /** @var \Zamoroka\PayPalAllCurrencies\Model\Rates $rateItem */
        $rateItem = $this->getExistingRate($serviceId, $baseCurrencyCode, $paypalCurrencyCode);

        if (!$rateItem->getEntityId()) {
            $this->insertRate($serviceId, $baseCurrencyCode, $paypalCurrencyCode, $rate);
        } else {
            $rateItem->setRate($this->roundRate($rate));
            $rateItem->setUpdatedAt($this->dateTime->gmtDate());
            $rateItem->save();
        }

        return $this;
    }

    /**
     * @param int    $serviceId
     * @param string $baseCurrencyCode
     * @param string $paypalCurrencyCode
     * @param float  $rate
     * @return $this
     */
    public function insertRate($serviceId, $baseCurrencyCode, $paypalCurrencyCode, $rate)
    {
        $data = [
            'service_id'           => $serviceId,
            'base_currency_code'   => $baseCurrencyCode,
            'paypal_currency_code' => $paypalCurrencyCode,
            'rate'                 => $this->roundRate($rate),
            'updated_at'           => $this->dateTime->gmtDate()
        ];
        $this->setData($data);

        return $this;
    }

    /**
     * @param int    $serviceId
     * @param string $baseCurrency
     * @param string $paypalCurrency
     * @return $this|null|\Magento\Framework\DataObject
     */
    public function getExistingRate($serviceId, $baseCurrency, $paypalCurrency)
    {
        return $this->getCollection()
                    ->addFieldToFilter('service_id', $serviceId)
                    ->addFieldToFilter('base_currency_code', $baseCurrency)
                    ->addFieldToFilter('paypal_currency_code', $paypalCurrency)
                    ->getFirstItem();
    }

    /**
     * @param $rate
     * @return float
     */
    public function roundRate($rate)
    {
        return round($rate, 4);
    }
}
