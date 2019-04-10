<?php

namespace App\Service\Companion;

class CompanionConfiguration
{
    // If we hit this number of errors, the system will stop.
    const ERROR_COUNT_THRESHOLD = 5;

    // the total number of items to process per cronjob
    const MAX_ITEMS_PER_CRONJOB = 30;

    // the total number of items to process per request
    const MAX_ITEMS_PER_REQUEST = 2;

    // the total time a cronjob should stay active
    const CRONJOB_TIMEOUT_SECONDS  = 60;

    // the delay between sending requests and asking for request response data
    const CRONJOB_ASYNC_DELAY_MS = 3500;

    // the delay between requests
    const DELAY_BETWEEN_REQUESTS_MS = 25;

    // how long a time has to be between sales to count against avg
    const ITEM_HISTORY_THRESHOLD = 0;

    // Any items with a lower history value than this, are put in their own queue.
    const ITEM_HISTORY_AVG_REQUIREMENT = 3;

    // Item has less than 5 sales
    const PRIORITY_ITEM_LOW_SALES = 7;

    // default queue
    const PRIORITY_TIMES_DEFAULT = 7;

    // Item was added to the database within the past 7 days
    const PRIORITY_ITEM_IS_NEW = 10;

    // How long until exceptions are ignored.
    const EXCEPTION_TIMEOUT_SECONDS = (60 * 60);
    
    const QUEUE_INFO = [
        // name, consumers
        1  => '< 1 hour',
        2  => '< 3 hours',
        3  => '< 6 hours',
        4  => '< 12 hours',
        5  => '< 24 hours',
        6  => '< 48 hours',
        7  => '> 48+ hours',

        8  => '(not used)',
        9  => '(not used)',
        10 => 'Item is new'
    ];
    
    const QUEUE_CONSUMERS = [
        1  => 2,
        2  => 3,
        3  => 3,
        4  => 4,
        5  => 4,
        6  => 3,
        7  => 5,
        
        8  => 0,
        9  => 0,
        10 => 0,
    ];
    
    // Priority values against a slot of time
    const PRIORITY_TIMES = [
        (60 * 60 * 1)   => 1,
        (60 * 60 * 3)   => 2,
        (60 * 60 * 6)   => 3,
        (60 * 60 * 12)  => 4,
        (60 * 60 * 24)  => 5,
        (60 * 60 * 48)  => 6,
    ];
}
