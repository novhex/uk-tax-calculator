<?php

require_once 'lib/class-income-tax.php';
require_once 'lib/data/income-tax-rates.php';
require_once 'lib/data/national-insurance-rates.php';
require_once 'lib/utils.php';

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
	$childcare_pre2011 = get_other_allowance( 'is_childcare_pre2011' );
	$blind = get_other_allowance( 'is_blind' );
	$exclude_ni = get_other_allowance( 'exclude_ni' );
	$student_loan = get_other_allowance( 'has_student_loan' );
	$married = get_other_allowance( 'is_married' );
	$income = get_annual_income( $income_every_x, $gross_income_is );
	$vouchers = get_annual_childcare( $vouchers_every, $childcare_vouchers );


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

<div class="container">


<h1>Tax Calculator</h1>

<div class="row">

<?php include 'templates/calculator-template.php'; ?>
                        
<?php include 'templates/results-template.php'; ?>

</div> <!-- end .row -->

</div> <!-- /.container-fluid -->

<?php include 'templates/footer-template.php'; ?>