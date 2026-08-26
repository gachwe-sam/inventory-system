<?php

namespace App\Support;

final class StockPermissions
{
    const RECEIVE = 'stock.receive';
    const ISSUE = 'stock.issue';
    const TRANSFER = 'stock.transfer';
    const CREATE = 'stock.create';

    const ALL = [self::RECEIVE, self::ISSUE, self::TRANSFER, self::CREATE];

    const MANAGER_ASSIGNABLE = [self::RECEIVE, self::ISSUE];
}
