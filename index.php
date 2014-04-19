<?php

require_once 'lib/class-income-tax.php';
require_once 'lib/data/income-tax-rates.php';
require_once 'lib/data/national-insurance-rates.php';

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	$year = trim( $_POST['tax_year_is'] );
	$income_every_x = trim( $_POST['income_every_x'] );
	$gross_income_is = trim( $_POST['gross_income_is'] );
	$other_allowance = trim( $_POST['other_allowance_is'] );
	$tax_code = trim( $_POST['tax_code_is'] );
	$age = trim( $_POST['age_is'] );
	$pension = trim( $_POST['pension_contribution_is'] );
	$pension_every = trim( $_POST['pension_every_x'] );
	$childcare_vouchers = trim( $_POST['childcare_vouchers_are'] );
	$vouchers_every = trim( $_POST['vouchers_every_x'] );
	$childcare_pre2011 = NULL;
	$blind = NULL;
	$exclude_ni = NULL;
	$student_loan = NULL;
	$married = NULL;

	if ( 'day' == $income_every_x ) {
		$income = $gross_income_is * 260;
	} elseif ( 'week' == $income_every_x ) {
		$income = $gross_income_is * 52;
	} elseif ( 'month' == $income_every_x ) {
		$income = $gross_income_is * 12;
	} elseif ( 'year' == $income_every_x ) {
		$income = $gross_income_is;
	}

	if ( 'week' == $vouchers_every ) {
		$vouchers = $childcare_vouchers * 53; // It's 53 and not 52 due to rounding of the numbers
	} elseif ( 'month' == $vouchers_every ) {
		$vouchers = $childcare_vouchers * 12;
	}

	if ( isset( $_POST['is_childcare_pre2011'] ) ) {
		$childcare_pre2011 = trim( $_POST['is_childcare_pre2011'] );
	} 

	if ( isset( $_POST['is_blind'] ) ) { 
		$blind = trim( $_POST['is_blind'] );
	} 

	if ( isset( $_POST['exclude_ni'] ) ) { 
		$exclude_ni = trim( $_POST['exclude_ni'] );
	} 

	if ( isset( $_POST['has_student_loan'] ) ) { 
		$student_loan = trim( $_POST['has_student_loan'] );
	} 

	if ( isset( $_POST['is_married'] ) ) { 
		$married = trim( $_POST['is_married'] );
	} 

	$persona = array(
		'year'              => filter_var( $year, FILTER_SANITIZE_STRING ),
		'income'            => filter_var( $income, FILTER_SANITIZE_STRING ),
		'other_allowance'   => filter_var( $other_allowance, FILTER_SANITIZE_STRING ),
		'tax_code'          => strtoupper( filter_var( $tax_code, FILTER_SANITIZE_STRING ) ),
		'age'               => filter_var( $age, FILTER_SANITIZE_STRING ),
		'pension'           => filter_var( $pension, FILTER_SANITIZE_STRING ),
		'pension_every'     => filter_var( $pension_every, FILTER_SANITIZE_STRING ),
		'vouchers'          => filter_var( $vouchers, FILTER_SANITIZE_STRING ),
		'childcare_pre2011' => filter_var( $childcare_pre2011, FILTER_SANITIZE_STRING ),
		'blind'             => filter_var( $blind, FILTER_SANITIZE_STRING ),
		'exclude_ni'        => filter_var( $exclude_ni, FILTER_SANITIZE_STRING ),
		'student_loan'      => filter_var( $student_loan, FILTER_SANITIZE_STRING ),
		'married'           => filter_var( $married, FILTER_SANITIZE_STRING ),
		);

	$taxcalc = new Tax_Calculator( $persona );
	
	$taxcalc->calculate_taxes();

}

?>


<?php include 'templates/header-template.php'; ?>

<?php include 'templates/calculator-template.php'; ?>
                        
<?php include 'templates/results-template.php'; ?>

</div>

</div>
</div>

<?php include 'templates/footer-template.php'; ?>