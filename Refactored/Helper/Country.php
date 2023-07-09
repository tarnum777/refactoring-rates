<?php

namespace TransactionCommission\Helper;

class Country {
    /**
     * @const
     */
    const EUROPE_COUNTRY_CODES = [
        'AT','BE','BG','CY','CZ','DE','DK','EE','ES','FI','FR','GR','HR','HU','IE',
        'IT','LT','LU','LV','MT','NL','PO','PT','RO','SE','SI','SK'
    ];

    /**
     * @param string $countryCode
     * @return bool
     */
    public static function isEuropeanCountry(string $countryCode): bool
    {
        return in_array($countryCode, self::EUROPE_COUNTRY_CODES);
    }
}