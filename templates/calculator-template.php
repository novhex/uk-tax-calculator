<div class="col-md-5 calculator_form">

	<form method="post" action="./index.php">

		<div class="row">
			<p>Enter gross salary, choose options and click <span>Calculate</span></p>
		</div>

		<div class="row">
	
			<div class="col-md-8">
				<label for="tax_year_is">Select tax year</label>
			</div>

			<div class="col-md-4 text-right last-col">
				<select id="tax_year_is" name="tax_year_is">
					<option value="year2014_15" name="year2014_15" id="year2014_15" <?php if ( isset( $_POST['tax_year_is'] ) && 'year2014_15' == $_POST['tax_year_is'] ) { echo "selected"; } elseif ( ! isset( $_POST['tax_year_is']) ) { echo "selected"; } ?>>2014/15</option>
					<option value="year2013_14" name="year2013_14" id="year2013_14" <?php if ( isset( $_POST['tax_year_is'] ) && 'year2013_14' == $_POST['tax_year_is'] ) { echo "selected"; } ?>>2013/14</option>
					<option value="year2012_13" name="year2012_13" id="year2012_13" <?php if ( isset( $_POST['tax_year_is'] ) && 'year2012_13' == $_POST['tax_year_is'] ) { echo "selected"; } ?>>2012/13</option>
					<option value="year2011_12" name="year2011_12" id="year2011_12" <?php if ( isset( $_POST['tax_year_is'] ) && 'year2011_12' == $_POST['tax_year_is'] ) { echo "selected"; } ?>>2011/12</option>
					<option value="year2010_11" name="year2010_11" id="year2010_11" <?php if ( isset( $_POST['tax_year_is'] ) && 'year2010_11' == $_POST['tax_year_is'] ) { echo "selected"; } ?>>2010/11</option>
					<option value="year2009_10" name="year2009_10" id="year2009_10" <?php if ( isset( $_POST['tax_year_is'] ) && 'year2009_10' == $_POST['tax_year_is'] ) { echo "selected"; } ?>>2009/10</option>
				</select>
			</div>
		</div>

		<div class="row">
	
			<div class="col-md-7">
				<label for="income_every_x">Gross&nbsp;</label>

				<select id="income_every_x" name="income_every_x">
					<option value="year" <?php  if ( isset( $_POST['income_every_x'] ) && 'year'  == $_POST['income_every_x'] ) { echo "selected"; } ?>>Yearly</option>
					<option value="month" <?php if ( isset( $_POST['income_every_x'] ) && 'month' == $_POST['income_every_x'] ) { echo "selected"; } ?>>Monthly</option>
					<option value="week" <?php  if ( isset( $_POST['income_every_x'] ) && 'week'  == $_POST['income_every_x'] ) { echo "selected"; } ?>>Weekly</option>
					<option value="day" <?php   if ( isset( $_POST['income_every_x'] ) && 'day'   == $_POST['income_every_x'] ) { echo "selected"; } ?>>Daily</option>
				</select>

				<label for="gross_income_is">income is&nbsp;</label>
			</div>

			<div class="col-md-5">
				<input type="text" id="gross_income_is" name="gross_income_is" class="form-control input-sm" value="<?php if ( isset( $_POST['gross_income_is'] ) ) { echo $_POST['gross_income_is']; } ?>"</td>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<label for="other_allowance_is">Other allowances</label>
			</div>

			<div class="col-md-6">
				<input type="text" id="other_allowance_is" class="form-control input-sm" name="other_allowance_is" value="<?php if ( isset( $_POST['other_allowance_is'] ) ) { echo $_POST['other_allowance_is']; } ?>"></td>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<label for="tax_code_is">Tax code (if known)</label>
			</div>

			<div class="col-md-6">
				<input type="text" id="tax_code_is" class="form-control input-sm" name="tax_code_is" value="<?php if ( isset( $_POST['tax_code_is'] ) ) { echo $_POST['tax_code_is']; } ?>"></td>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8">
				<label for="age_is">Age</label>
			</div>

			<div class="col-md-4 last-col text-right">

				<select id="age_is" name="age_is">
				  	<option value="under_65"  <?php if ( isset( $_POST['age_is']) && 'under_65' == $_POST['age_is'] ) { echo "selected"; } ?>>Under 65</option>
				  	<option value="65_74"  <?php    if ( isset( $_POST['age_is']) && '65_74'    == $_POST['age_is'] ) { echo "selected"; } ?>>65-74</option>
				  	<option value="over_75"  <?php  if ( isset( $_POST['age_is']) && 'over_75'  == $_POST['age_is'] ) { echo "selected"; } ?>>Over 75</option>
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8">
				<select id="pension_every_x" name="pension_every_x">
					<option value="month" <?php if ( isset( $_POST['pension_every_x'] ) && 'month' == $_POST['pension_every_x'] ) { echo "selected"; } elseif ( ! isset( $_POST['pension_every_x'] ) ) { echo "selected"; } ?>>Monthly</option>
					<option value="year" <?php  if ( isset( $_POST['pension_every_x'] ) && 'year'  == $_POST['pension_every_x'] ) { echo "selected"; } ?>>Yearly</option>
				</select>
				<label for="pension_contribution_is">pension contribution is</label>
			</div>

			<div class="col-md-4">
				<input type="text" id="pension_contribution_is" class="form-control input-sm" name="pension_contribution_is" value="<?php if ( isset( $_POST['pension_contribution_is'] ) ) { echo $_POST['pension_contribution_is']; } ?>">
			</div>
		</div>

		<div class="row">
			<div class="col-md-5">
				<label for="childcare_vouchers_are">Childcare vouchers</label>
			</div>

			<div class="col-md-3">
				<input type="text" id="childcare_vouchers_are" class="form-control input-sm" name="childcare_vouchers_are" value="<?php if ( isset( $_POST['childcare_vouchers_are'] ) ) { echo $_POST['childcare_vouchers_are']; } ?>">
			</div>

			<div class="col-md-4 text-right">
				<label for="vouchers_every_x">per&nbsp;</label>
				<select id="vouchers_every_x" name="vouchers_every_x">
					<option value="week" <?php  if ( isset( $_POST['vouchers_every_x']) && 'week'  == $_POST['vouchers_every_x'] ) { echo "selected";} ?>>Week</option>
					<option value="month" <?php if ( isset( $_POST['vouchers_every_x']) && 'month' == $_POST['vouchers_every_x'] ) { echo "selected";} elseif ( ! isset( $_POST['vouchers_every_x'] ) ) { echo "selected"; } ?>>Month</option>
				</select>
			</div>
		</div>

		<div class="row" id="voucher_extra" <?php if ( ! isset( $taxcalc->show_childcare_vouchers ) || 0 == $taxcalc->show_childcare_vouchers ) { echo 'style="display:none"'; } ?>>
			<div class="col-md-10">
				<label for="is_childcare_pre2011">Did you sign up before April 6 2011?</label>
			</div>

			<div class="col-md-2 text-right">
				<input type="checkbox" id="is_childcare_pre2011" name="is_childcare_pre2011" 
				<?php if ( isset( $_POST['is_childcare_pre2011'] ) && 'on' == $_POST['is_childcare_pre2011'] ) { echo "checked"; } ?> >
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<label for="is_blind" class="checkbox-inline">
				<input type="checkbox" id="is_blind" name="is_blind" 
				<?php if ( isset( $_POST['is_blind'] ) && 'on' == $_POST['is_blind'] ) { echo "checked"; } ?> >I am blind
				</label>

				<label for="exclude_ni" class="checkbox-inline">
				<input type="checkbox" id="exclude_ni" name="exclude_ni"
				<?php if ( isset( $_POST['exclude_ni'] ) && 'on' == $_POST['exclude_ni'] ) { echo "checked"; } ?> >I do not pay NI
				</label>

				<label for="is_married" class="checkbox-inline">
				<input type="checkbox" id="is_married" name="is_married"
				<?php if ( isset( $_POST['is_married'] ) && 'on' == $_POST['is_married'] ) { echo "checked"; } ?> >Married
				</label>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<label for="has_student_loan" class="checkbox-inline">
				<input type="checkbox" id="has_student_loan" name="has_student_loan" 
				<?php if ( isset( $_POST['has_student_loan'] ) && 'on' == $_POST['has_student_loan'] ) { echo "checked"; } ?> >&nbsp;I have a student loan
				</label>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 text-center">
				<input type="submit" id="calculate_taxes" class="btn btn-primary btn-lg" name="calculate_taxes" value="Calculate" />
			</div>
		</div>

</form>
</div> <!-- end .col-md-6 -->