<?php
/*
 * Determines whether a checkbox has been enabled and if so trims it.
 * If not, returns a null.
 *
 * @return string Returns "on" if the checkbox is enabled          
 */
function get_other_allowance( $name ) {
	return isset( $_POST[ $name ] ) ? trim( $_POST[ $name ] ) :

	null;
}

/*
 * Checks the income frequency and converts it to an annual amount.
 *
 * @return int Annual income          
 */
function get_annual_income( $frequency, $income ) {
	
	if ( 'day' === $frequency ) {
		return $income * 260;
	} elseif ( 'week' === $frequency ) {
		return $income * 52;
	} elseif ( 'month' === $frequency ) {
		return $income * 12;
	} else {
		return $income;
	}
}

/*
 * Checks the childcare frequency and converts it to an annual amount.
 * Note: It's 53 and not 52 for weekly because of the way the allowances
 * are rounded by HMRC.
 *
 * @return int Annual childcare vouchers        
 */
function get_annual_childcare( $frequency, $amount ) {
	if ( 'week' === $frequency ) {
		return $amount * 53;
	} elseif ( 'month' === $frequency ) {
		return $amount * 12;
	}
}

/*
 * Trims the POST values created on submission of the tax form.
 *
 * @return mixed Trimmed POST value
 */
function get_sanitized_tax_option( $post ) {
	return trim( $_POST[ $post ] );
}