<?php

const DAY = 'day';
const WEEK = 'week';
const MONTH = 'month';
const NUMBER_OF_MONTHS = 12;
const NUMBER_OF_WEEKS = 52;
const NUMBER_OF_WEEKS_ODD = 53;
const NUMBER_OF_DAYS = 260;

/*
 * Determines whether a checkbox has been enabled and if so trims it.
 * If not, returns a null.
 *
 * @return string Returns "on" if the checkbox is enabled          
 */
function get_other_allowance( $name ) {
	return isset( $_POST[ $name ] ) ? trim( $_POST[ $name ] ) : null;
}

/*
 * Checks the income frequency and converts it to an annual amount.
 *
 * @return int Annual income          
 */
function get_annual_income( $frequency, $income ) {
	if ( DAY === $frequency ) {
		return $income * NUMBER_OF_DAYS;
	} elseif ( WEEK === $frequency ) {
		return $income * NUMBER_OF_WEEKS;
	} elseif ( MONTH === $frequency ) {
		return $income * NUMBER_OF_MONTHS;
	} else {
		return $income;
	}
}

/*
 * Checks the childcare frequency and converts it to an annual amount.
 *
 * @note It's 53 and not 52 for weekly because of the way the allowances
 * are rounded by HMRC.
 * @return int Annual childcare vouchers        
 */
function get_annual_childcare( $frequency, $amount ) {
	if ( WEEK === $frequency ) {
		return $amount * NUMBER_OF_WEEKS_ODD;
	} elseif ( MONTH === $frequency ) {
		return $amount * 12;
	}
}

/*
 * Trims the POST values created on submission of the tax form.
 *
 * @return string Trimmed POST value
 */
function get_sanitized_tax_option( $post ) {
	return trim( $_POST[ $post ] );
}