<?php

namespace Package\XCalendar\Models;

use Package\XLimit\Models\TradeLimit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trade extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'sqlite_user';

    protected $fillable = [
        'timestamp', 'curruncy_pair', 'buy_value', 'buy_price',
        'sell_value', 'sell_price', 'bought_amount', 'position', 'pnl', 'timestamp'
    ];

    /**
     * Check if the trade limit is exceeded for a given date.
     *
     * @param string $date
     * @param float $newBuyPrice
     * @return bool|string
     */
    public static function isLimitExceeded($timeStamp, $newBuyPrice)
    {

        $date = getDateFromTimestamp($timeStamp);

        $tradeLimit = TradeLimit::where('date', $date)->first();

        if (!$tradeLimit) {
            return false;
        }

        $totalBuyPriceForTheDay = self::where('timestamp', $timeStamp)->sum('buy_price');

        $totalWithNewTrade = $totalBuyPriceForTheDay + $newBuyPrice;

        if ($totalWithNewTrade > $tradeLimit->limit) {
            return 'Trade limit exceeded for the day.';
        }

        return false;
    }
}
