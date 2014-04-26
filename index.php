<?php

require_once 'lib/class-income-tax.php';
require_once 'lib/data/income-tax-rates.php';
require_once 'lib/data/national-insurance-rates.php';
require_once 'lib/utils.php';

if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

	$persona = array(
		'year'              => trim( $_POST['tax_year_is'] ),
		'income'            => get_annual_income( trim( $_POST['income_every_x'] ), trim( $_POST['gross_income_is'] ) ),
		'other_allowance'   => trim( $_POST['other_allowance_is'] ),
		'tax_code'          => strtoupper(trim( $_POST['tax_code_is'] )),
		'age'               => trim( $_POST['age_is'] ),
		'pension'           => trim( $_POST['pension_contribution_is'] ),
		'pension_every'     => trim( $_POST['pension_every_x'] ),
		'vouchers'          => get_annual_childcare( trim( $_POST['vouchers_every_x'] ), trim( $_POST['childcare_vouchers_are'] ) ),
		'childcare_pre2011' => get_other_allowance( 'is_childcare_pre2011' ),
		'blind'             => get_other_allowance( 'is_blind' ),
		'exclude_ni'        => get_other_allowance( 'exclude_ni' ),
		'student_loan'      => get_other_allowance( 'has_student_loan' ),
		'married'           => get_other_allowance( 'is_married' ),
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

</div> <!-- /.container -->

<?php include 'templates/footer-template.php'; ?>