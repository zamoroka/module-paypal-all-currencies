##Description
By default Paypal works with following currencies: _'AUD', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 
'MXN', 'NOK', 'NZD', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD'_.

This extension converts your store currency to Paypal currency before order placing.

Using 3 services:
 - https://api.appnexus.com/
 - http://free.currencyconverterapi.com
 - https://finance.google.com/finance/converter

Currency updates every hour by cron job

Implement `Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface` in case of adding new service.

Works with native Paypal Express Checkout and Paypal Credit.

In admin order view all prices have converted price in square brackets.

Converted data stored in `'quote'`, `'quote_item'`, `'sales_order'`, `'sales_order_item'` tables.

##Settings
Extension settings at Magento 2 admin / stores / configuration / sales / Paypal all currencies.

##Developed on environment:
 - PHP 7.0.17-1
 - NGINX 1.11.10
 - MYSQL 5.6.28-76.1
 - Magento 2.1.8
