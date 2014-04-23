<?php

function get_other_allowance( $name ) {
	return isset( $_POST[ $name ] ) ? trim( $_POST[ $name ] ) :

	null;
}

function get_annual_income( $frequency, $income ) {
	
	if ( 'day' == $frequency ) {
		return $income * 260;
	} elseif ( 'week' == $frequency ) {
		return $income * 52;
	} elseif ( 'month' == $frequency ) {
		return $income * 12;
	} else {
		return $income;
	}
}

function get_annual_childcare( $frequency, $amount ) {
	if ( 'week' == $frequency ) {
		return $amount * 53; // It's 53 and not 52 due to weird rounding of the numbers
	} elseif ( 'month' == $frequency ) {
		return $amount * 12;
	}
}