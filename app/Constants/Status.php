<?php

namespace App\Constants;

class Status
{

    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const VERIFIED = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_PENDING = 2;
    const PAYMENT_REJECT = 3;

    const TICKET_OPEN = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY = 2;
    const TICKET_CLOSE = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;


    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM  = 3;

    const ORDER_PENDING    = 0;
    const ORDER_PROCESSING = 1;
    const ORDER_DISPATCHED = 2;
    const ORDER_DELIVERED  = 3;
    const ORDER_CANCELED   = 4;
    const ORDER_INITIATED  = 0;
    const ORDER_RETURNED   = 9;

    const ORDER_TYPE_SELF = 1;
    const ORDER_TYPE_GIFT = 2;

    const DISCOUNT_FIXED = 1;
    const DISCOUNT_PERCENT = 2;

    const DOWNLOAD_INSTANT = 1;
    const DOWNLOAD_AFTER_SALE = 2;

    const PRODUCT_TYPE_SIMPLE = 1;
    const PRODUCT_TYPE_VARIABLE = 2;

    const ATTRIBUTE_TYPE_TEXT = 1;
    const ATTRIBUTE_TYPE_COLOR = 2;
    const ATTRIBUTE_TYPE_IMAGE = 3;

    const SINGLE_IMAGE_BANNER = 1;
    const DOUBLE_IMAGE_BANNER = 2;
    const TRIPLE_IMAGE_BANNER = 3;
}
