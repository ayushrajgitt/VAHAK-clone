<?php
// Sanitize input
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Format currency
function format_currency($amount) {
    return '₹' . number_format($amount, 2);
}

// Format date
function format_date($date) {
    return date('d M, Y', strtotime($date));
}

// Calculate time ago
function time_ago($datetime) {
    $time = strtotime($datetime);
    $time_difference = time() - $time;

    if ($time_difference < 1) { return 'less than 1 second ago'; }
    $condition = array( 
        12 * 30 * 24 * 60 * 60 =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
    );

    foreach( $condition as $secs => $str ) {
        $d = $time_difference / $secs;
        if( $d >= 1 ) {
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
}
?>
