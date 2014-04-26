<div class="col-md-7">
		<table id="results" class="table table-bordered table-condensed">

			<tr style="text-transform:none;" class="results-header">
				<th class="row-label">Salary Summary</th>
				<th class="yr">Yearly</th>
				<th class="mth">Monthly</th>
				<th class="wk">Weekly</th>
				<th class="day col-day">Daily</th>
			</tr>

			<tr class="gross-row">
				<td class="row-label">Gross Income</td>
				<td class="yr">
				<?php 
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_gross_income, 2 ); 
						} else { 
							echo '&pound;' . number_format( 0, 2 );
						} 
				?>
				</td>
				<td class="mth">
				<?php 
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_gross_income / 12, 2 ); 
						} else {
							echo '&pound;' . number_format( 0, 2 );
						} 
				?>
				</td>
				<td class="wk">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_gross_income / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_gross_income / 260, 2 ); 
						} else {
							echo '&pound;' . number_format( 0, 2 );
							}
				?>
				</td>
			</tr>

			<tr class="childcare-row" <?php if ( !isset( $taxcalc->show_childcare_vouchers ) || $taxcalc->show_childcare_vouchers == 0 ) { echo 'style="display:none"'; } ?>>
				<td class="row-label">Childcare Vouchers</td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_childcare_vouchers, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( round( $taxcalc->show_childcare_vouchers / 12 ), 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( round( $taxcalc->show_childcare_vouchers / 53 ), 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( round( $taxcalc->show_childcare_vouchers / 260 ), 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="tfa-row">
				<td class="row-label">Tax free Allowance</td>
				<td class="yr">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_tax_free_allowance, 2 );
						} else {
							echo '&pound;' . number_format( 10000, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_tax_free_allowance / 12, 2 );
						} else {
							echo '&pound;' . number_format( 10000 / 12, 2 );
						}
				?>
				</td>
				<td class="wk">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_tax_free_allowance / 52, 2 );
						} else {
							echo '&pound;' . number_format( 10000 / 52, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_tax_free_allowance / 260, 2 );
						} else {
							echo '&pound;' . number_format( 10000 / 260, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="taxable-row">
				<td class="row-label">Total taxable</td>
				<td class="yr">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_taxable_amount, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_taxable_amount / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_taxable_amount / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_taxable_amount / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="taxbands-row">
				<td class="row-label"><a id="tax-expand" href="javascript:"><span class="glyphicon glyphicon-chevron-right"></span></a>&nbsp;Tax Due</td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_tax_due, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_tax_due / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php 
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_tax_due / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->total_tax_due / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?></td>
			</tr>

			<tr  id="taxband1-row" class="tax-bands" style="display:none">
				<td class="row-label">&nbsp;&nbsp;&nbsp;<em>
				<?php
					if ( isset( $taxcalc ) ) {
							echo $taxcalc->bands["additional"]["rate"];
						} else { 
							echo 45;
						}
				?>% tax rate</em>
				</td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["additional"], 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["additional"] / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["additional"] / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["additional"] / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr id="taxband2-row" class="tax-bands" style="display:none">
				<td class="row-label">&nbsp;&nbsp;&nbsp;&nbsp;<em>40% tax rate</em></td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["higher"], 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["higher"] / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["higher"] / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["higher"] / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr id="taxband3-row" class="tax-bands" style="display:none">
				<td class="row-label">&nbsp;&nbsp;&nbsp;&nbsp;<em>20% tax rate</em></td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["basic"], 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["basic"] / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["basic"] / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc->deduction ) ) {
							echo '&pound;' . number_format( $taxcalc->deduction["basic"] / 260,2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="student-row" <?php if ( !isset( $taxcalc->show_student_loan_amount ) ) { echo 'style="display:none"'; } ?>>
				<td class="row-label">Student Loan</td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc ) && isset( $taxcalc->show_student_loan_amount ) ) {
							echo '&pound;' . number_format( $taxcalc->show_student_loan_amount, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) && isset( $taxcalc->show_student_loan_amount ) ) {
							echo '&pound;' . number_format( floor( $taxcalc->show_student_loan_amount / 12 ), 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php
					if ( isset( $taxcalc ) && isset( $taxcalc->show_student_loan_amount ) ) {
							echo '&pound;' . number_format( floor( $taxcalc->show_student_loan_amount / 52 ), 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) && isset( $taxcalc->show_student_loan_amount ) ) {
							echo '&pound;' . number_format( floor( $taxcalc->show_student_loan_amount / 260), 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="ni-row">
				<td class="row-label">National Insurance</td>
				<td class="yr">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_ni_contribution, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_ni_contribution / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_ni_contribution / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_ni_contribution / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="pension-row" <?php if ( !isset( $taxcalc->show_employer_pension ) || $taxcalc->show_employer_pension == 0 ) { echo 'style="display:none"'; } ?>>
				<td class="row-label"><a id="pension-expand" href="javascript:"><span class="glyphicon glyphicon-chevron-right"></span></a>&nbsp;Pension [You]</td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_employer_pension, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_employer_pension / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_employer_pension / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_employer_pension / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="hmrc-pension-row" style="display:none">
				<td class="row-label">Pension [HMRC]</td>
				<td class="yr odd">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_pension_hmrc, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_pension_hmrc / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="wk odd">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_pension_hmrc / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_pension_hmrc / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="total-deductions-row">
				<td class="row-label">Total Deductions</td>
				<td class="yr">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_total_deduction, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?></td>
				<td class="mth">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_total_deduction / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?></td>
				<td class="wk">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_total_deduction / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
				<td class="day">
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_total_deduction / 260,2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</td>
			</tr>

			<tr class="net-row">
				<td class="row-label">Net Income</td>
				<td class="yr odd"><span>
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_net_income, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</span></td>
				<td class="mth"><span>
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_net_income / 12, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</span></td>
				<td class="wk odd"><span>
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_net_income / 52, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</span></td>
				<td class="day"><span>
				<?php
					if ( isset( $taxcalc ) ) {
							echo '&pound;' . number_format( $taxcalc->show_net_income / 260, 2 );
						} else {
							echo '&pound;' . number_format( 0, 2 );
						}
				?>
				</span></td>
			</tr>	
			
		</table>
	</div> <!-- end .col-md-6 -->