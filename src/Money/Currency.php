<?php
namespace Rmx351\Commons\Money;

abstract class Currency
{
    const CURRENCY_USD = 'USD';
    const CURRENCY_CNY = 'CNY';
    const CURRENCY_AUD = 'AUD';
    const CURRENCY_CAD = 'CAD';
    const CURRENCY_EGP = 'EGP';
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_GBP = 'GBP';
    const CURRENCY_JPY = 'JPY';
    const CURRENCY_KRW = 'KRW';
    const CURRENCY_PNT = 'PNT';
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_NZD = 'NZD';

    public static function getCurrencies()
    {
        return [
            static::CURRENCY_USD,
            static::CURRENCY_CNY,
            static::CURRENCY_AUD,
            static::CURRENCY_CAD,
            static::CURRENCY_EGP,
            static::CURRENCY_EUR,
            static::CURRENCY_GBP,
            static::CURRENCY_JPY,
            static::CURRENCY_KRW,
            static::CURRENCY_PNT,
            static::CURRENCY_RUB,
            static::CURRENCY_NZD,
        ];
    }
}
