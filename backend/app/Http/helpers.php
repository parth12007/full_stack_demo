<?php

/**
 * Method to get current time for log
 *
 * @return int
 */
function getCurrentTimeForLog()
{
    $timeStampBeforeCall = round(microtime(true));
    return $timeStampBeforeCall;
}