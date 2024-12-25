<?php

/**
 * Converts a given date string into a timestamp in milliseconds.
 *
 * @param string $date The date string in 'YYYY-MM-DD' format.
 * @return int The timestamp in milliseconds.
 */
function getTimestampFromDate($date)
{
    $timestampInSeconds = strtotime($date);

    $timestampInMilliseconds = $timestampInSeconds * 1000;

    return $timestampInMilliseconds;
}

/**
 * Converts a timestamp in milliseconds into a date string.
 *
 * @param int $timestamp The timestamp in milliseconds.
 * @return string The date string in 'YYYY-MM-DD' format.
 */
function getDateFromTimestamp($timestamp)
{
    $timestampInSeconds = $timestamp / 1000;

    $date = date('Y-m-d', $timestampInSeconds);

    return $date;
}
