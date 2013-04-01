<?php
/**
 * This is a simple snippet that does date calculations 
 * 
 * [[Dates?
 *   &format=`Y-m-d` default: Y-m-d date: http://www.php.net/manual/en/function.date.php, strftime: http://www.php.net/manual/en/function.strftime.php 
 *   &function=`` default is date, can be: strftime or date
 *   &time=`` - Unix Timestamp default is time()
 *   &strtotime=`` default empty, if set it will override the time property Strings: http://www.php.net/manual/en/function.strtotime.php
 *   
 *   &month=``
 *   &day=``
 *   &quarter=``
 *   &year=``
 *   
 *   &past=`` default empty, this is the time to subtract from time/strtotime in seconds (Mixed) can be (Int) 604800, for 1 week or use basic math operators: 3600*24*7  
 *   &future=`` default empty, same as past
 *   &return=`` default string, can be time for Unix timestamp
 *   
 *   &toPlaceholder=``
 * ]]
 * 
 * 1st day of month
 * 1st day of quarter
 * 1st day of year
 * 
 */
 
$format =  $modx->getOption('format', $scriptProperties, 'Y-m-d' );
$function =  $modx->getOption('function', $scriptProperties, 'date' );
$return =  $modx->getOption('return', $scriptProperties, 'string' );
$time =  $modx->getOption('time', $scriptProperties, time() );
$strtotime =  $modx->getOption('strtotime', $scriptProperties, null );

$past =  $modx->getOption('past', $scriptProperties, null );
$future =  $modx->getOption('future', $scriptProperties, null );

/**
 * turn str into number, only excepts: *
 */
if ( !function_exists('getNumber') ) {
    function getNumber($str) {
        if ( is_numeric($str) ) {
            return $str;
        }
        $v = 1;
        // * 
        $numbers = explode('*', $str);
        foreach( $numbers as $n ) {
            $n = trim($n);
            if ( is_numeric($n) ) {
                $v *= $n;
            }
        }
        return $v;
    }
}

// build time:
if ( !empty($strtotime) ) {
    $time = strtotime($strtotime);
}

if ( !empty($past) ) {
    $time -= getNumber($past);
} elseif ( !empty($future) ) {
    $time += getNumber($future);
}
if ( $return == 'time' ) {
    $date = $time;
} else {
    $date = '';
    if ( $function == 'strftime' ) {
        $date = strftime($format, $time);
    } else {
        $date = date($format, $time);
    }
}

$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, '' );
if ( !empty($toPlaceholder) ) {
    $modx->setPlaceholder($toPlaceholder, $date);
    return '';
}
return $date;