<!DOCTYPE html>

<html>
<head>

<title>Salary Calculator | 2014 / 2015 PAYE Income Tax Calculator</title>

<meta name="description" content="A quick and easy PAYE, wage, income tax and salary calculator that lets you work out how much tax and national insurance you pay, and your net income."/>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<link href='bootstrap/css/bootstrap.css' rel='stylesheet'>

<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

<style>
.calculator_form {
	border: 1px solid #ddd;
}

.row {
	padding: 2px 0;
}

.calculator_form .row {
	border-bottom: 1px solid #ddd;
}

.radio-inline + .radio-inline, .checkbox-inline + .checkbox-inline {
	margin-top: 5px;
}

label, .last-col {
	margin: 5px 0;
}

span.glyphicon.glyphicon-chevron-right.open {
	-webkit-transform: rotate(90deg);
    -moz-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
}

.taxbands-row {
	background: #e74c3c;
	color: white;
	border-left: 1px solid #e74c3c;
	border-right: 1px solid #e74c3c;
}

#results .taxbands-row td {
	border: none;
}

#results .taxbands-row .odd {
	background: #c93224;
}

.tax-bands {
	background: #f97568;
	border-left: 1px solid #f97568;
	border-right: 1px solid #f97568;
}

.tax-bands .odd {
	background:#e55e52;
}

.net-row {
	background: #2ecc71;
	border-left: 1px solid #2ecc71;
	border-right: 1px solid #2ecc71;
}

.net-row .odd {
	background: #27ae60;
}

.net-row .row-label {
	font-weight: bold;
}

.pension-row {
	color: white;
	background: #3498db;
	border-left: 1px solid #2980b9;
	border-right: 1px solid #2980b9;
}

.pension-row .odd {
	background: #2980b9;
}

.hmrc-pension-row {
	background: #ccc;
	border-left: 1px solid #ccc;
	border-right: 1px solid #ccc;
}

.hmrc-pension-row .odd {
	background: #aeaeae;
}

.student-row {
	color: white;
	background: #34495e;
	border-left: 1px solid #34495e;
	border-right: 1px solid #34495e;
}

.student-row .odd {
	background: #2c3e50;
}

.childcare-row {
	color: white;
	background: #9b59b6;
	border-left: #9b59b6;
	border-right: #9b59b6;
}

.childcare-row .odd {
	background: #8e44ad;
}

#results .tax-bands td, #results .net-row td, #results .pension-row td,
#results .hmrc-pension-row td, #results .student-row td, #results .childcare-row td {
	border:none;
}

.yr, .mth, .wk, .day {
	text-align: right;
}

tr a .glyphicon {
	color: white;
}

tr a .glyphicon:hover {
	color: #ecf0f1;
}

@media (min-width: 1200px) {
	.container {
		width: 970px;
	}
}
</style>

<script type="text/javascript">//<![CDATA[
        
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-38268400-1']);
				            _gaq.push(['_trackPageview']);
            (function () {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';

                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
            //]]></script>

</head>
<body>
