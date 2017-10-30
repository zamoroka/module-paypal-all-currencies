<?php

namespace Zamoroka\PayPalAllCurrencies\Preference\Quote\Model;

use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;
use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;

/**
 * Class Item
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\Preference\Quote\Quote
 */
class Quote extends \Magento\Quote\Model\Quote
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory */
    protected $currencyServiceFactory;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface|null $currencyService */
    protected $currencyService = null;

    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;

    /** @var \Magento\Framework\Message\ManagerInterface */
    protected $messageManager;

    /**
     * Quote constructor.
     *
     * @param \Magento\Framework\Model\Context                                   $context
     * @param \Magento\Framework\Registry                                        $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory                  $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory                       $customAttributeFactory
     * @param \Magento\Quote\Model\QuoteValidator                                $quoteValidator
     * @param \Magento\Catalog\Helper\Product                                    $catalogProduct
     * @param \Magento\Framework\App\Config\ScopeConfigInterface                 $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface                         $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface                 $config
     * @param \Magento\Quote\Model\Quote\AddressFactory                          $quoteAddressFactory
     * @param \Magento\Customer\Model\CustomerFactory                            $customerFactory
     * @param \Magento\Customer\Api\GroupRepositoryInterface                     $groupRepository
     * @param \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory    $quoteItemCollectionFactory
     * @param \Magento\Quote\Model\Quote\ItemFactory                             $quoteItemFactory
     * @param \Magento\Framework\Message\Factory                                 $messageFactory
     * @param \Magento\Sales\Model\Status\ListFactory                            $statusListFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface                    $productRepository
     * @param \Magento\Quote\Model\Quote\PaymentFactory                          $quotePaymentFactory
     * @param \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory $quotePaymentCollectionFactory
     * @param \Magento\Framework\DataObject\Copy                                 $objectCopyService
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface               $stockRegistry
     * @param \Magento\Quote\Model\Quote\Item\Processor                          $itemProcessor
     * @param \Magento\Framework\DataObject\Factory                              $objectFactory
     * @param \Magento\Customer\Api\AddressRepositoryInterface                   $addressRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder                       $criteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder                               $filterBuilder
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory                 $addressDataFactory
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory                $customerDataFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface                  $customerRepository
     * @param \Magento\Framework\Api\DataObjectHelper                            $dataObjectHelper
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter               $extensibleDataObjectConverter
     * @param \Magento\Quote\Model\Cart\CurrencyFactory                          $currencyFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface   $extensionAttributesJoinProcessor
     * @param \Magento\Quote\Model\Quote\TotalsCollector                         $totalsCollector
     * @param \Magento\Quote\Model\Quote\TotalsReader                            $totalsReader
     * @param \Magento\Quote\Model\ShippingFactory                               $shippingFactory
     * @param \Magento\Quote\Model\ShippingAssignmentFactory                     $shippingAssignmentFactory
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data                          $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory         $currencyServiceFactory
     * @param \Psr\Log\LoggerInterface                                           $logger
     * @param \Magento\Framework\Message\ManagerInterface                        $messageManager
     * @param null                                                               $resource
     * @param null                                                               $resourceCollection
     * @param array                                                              $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Quote\Model\Quote\AddressFactory $quoteAddressFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Quote\Model\ResourceModel\Quote\Item\CollectionFactory $quoteItemCollectionFactory,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Framework\Message\Factory $messageFactory,
        \Magento\Sales\Model\Status\ListFactory $statusListFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Quote\Model\Quote\PaymentFactory $quotePaymentFactory,
        \Magento\Quote\Model\ResourceModel\Quote\Payment\CollectionFactory $quotePaymentCollectionFactory,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Quote\Model\Quote\Item\Processor $itemProcessor,
        \Magento\Framework\DataObject\Factory $objectFactory,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $criteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressDataFactory,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        \Magento\Quote\Model\Cart\CurrencyFactory $currencyFactory,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector,
        \Magento\Quote\Model\Quote\TotalsReader $totalsReader,
        \Magento\Quote\Model\ShippingFactory $shippingFactory,
        \Magento\Quote\Model\ShippingAssignmentFactory $shippingAssignmentFactory,
        Data $helper,
        CurrencyServiceFactory $currencyServiceFactory,
        LoggerInterface $logger,
        ManagerInterface $messageManager,
        $resource = null,
        $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context, $registry, $extensionFactory, $customAttributeFactory, $quoteValidator, $catalogProduct,
            $scopeConfig, $storeManager, $config, $quoteAddressFactory, $customerFactory, $groupRepository,
            $quoteItemCollectionFactory, $quoteItemFactory, $messageFactory, $statusListFactory, $productRepository,
            $quotePaymentFactory, $quotePaymentCollectionFactory, $objectCopyService, $stockRegistry, $itemProcessor,
            $objectFactory, $addressRepository, $criteriaBuilder, $filterBuilder, $addressDataFactory,
            $customerDataFactory, $customerRepository, $dataObjectHelper, $extensibleDataObjectConverter,
            $currencyFactory, $extensionAttributesJoinProcessor, $totalsCollector, $totalsReader, $shippingFactory,
            $shippingAssignmentFactory, $resource, $resourceCollection, $data
        );
        $this->helper = $helper;
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->currencyServiceFactory = $currencyServiceFactory;
    }

    /**
     * Quote Item Before Save prepare data process
     *
     * @return $this
     */
    public function beforeSave()
    {
        if ($this->helper->isModuleEnabled()) {

            foreach ($this->getAllItems() as $item) {
                $amt = $this->getAmountForPaypal('item_price', $item);
                $amtTotal = $this->getAmountForPaypal('item_row_total', $item);
                $item->setPaypalPrice($amt);
                $item->setPaypalRowTotal($amtTotal);
            }

            $this->setPaypalSubtotal($this->getAmountForPaypal('subtotal'));
            $this->setPaypalGrandTotal($this->getAmountForPaypal('grand_total'));

            $this->setPaypalShippingAmount($this->getAmountForPaypal('shipping'));
            $this->setPaypalDiscountAmount($this->getAmountForPaypal('discount'));
            $this->setPaypalTaxAmount($this->getAmountForPaypal('tax'));

            $this->setPaypalRate($this->getCurrencyService()->exchange(1, 4));
            $this->setPaypalCurrencyCode('USD');
        }

        parent::beforeSave();
    }

    /**
     * Get object data by key with calling getter method
     * used in Magento\Paypal\Model\Cart, function _validate()
     *
     * @param string $key
     * @param mixed  $args
     * @return mixed
     */
    public function getDataUsingMethod($key, $args = null)
    {
        if ($key == 'base_grand_total') {
            return $this->getCurrencyService()->exchange(parent::getDataUsingMethod($key, $args));
        }

        return parent::getDataUsingMethod($key, $args);
    }

    /**
     * @param string                               $code
     * @param \Magento\Quote\Model\Quote\Item|null $item
     * @return float
     */
    public function getAmountForPaypal($code, \Magento\Quote\Model\Quote\Item $item = null)
    {
        $amt = 0;

        /** @var \Magento\Quote\Model\Quote\Address $address */
        $address = $this->isVirtual() ? $this->getBillingAddress() : $this->getShippingAddress();

        try {
            switch ($code) {
                case 'subtotal':
                    $amt = $this->getBaseSubtotal();
                    break;
                case 'grand_total':
                    $amt = $this->getBaseGrandTotal();
                    break;
                case 'shipping':
                    $amt = $address->getBaseShippingAmount();
                    break;
                case 'discount':
                    $amt = $address->getBaseDiscountAmount();
                    break;
                case 'tax':
                    $amt = $address->getBaseTaxAmount();
                    break;
                case 'item_price':
                    $amt = $item->getBasePrice();
                    break;
                case 'item_row_total':
                    $amt = $item->getBaseRowTotal();
                    break;
                default:
                    break;
            }
        } catch (\Throwable $t) {
            $amt = 0;
            $this->logger->critical($t->getMessage());
            $this->messageManager->addErrorMessage(__('Can`t calculate paypal amount'));
        }

        return $this->getCurrencyService()->exchange($amt);
    }

    /**
     * @return false|null|\Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface
     */
    public function getCurrencyService()
    {
        if (!$this->currencyService) {
            $this->currencyService = $this->currencyServiceFactory->load($this->helper->getCurrencyServiceId());
        }

        return $this->currencyService;
    }
}
