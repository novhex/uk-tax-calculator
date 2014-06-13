$( '#childcare_vouchers_are' ).focus( function() {
  $( '#voucher_extra').show( 'fast' );
});

$( '#childcare_vouchers_are' ).blur( function() {
  if ( '' === $( '#childcare_vouchers_are' ).val() ) {
  $( '#voucher_extra').hide( 'fast' );
  } else {
    return;
  }
});

$( '#pension-expand' ).on( 'click', function() {
  if ( $( 'tr.hmrc-pension-row' ).is( ':visible' ) ) {
    $( 'tr.hmrc-pension-row' ).hide( 'fast' );
    $( '#pension-expand .glyphicon' ).removeClass( 'open' );
  } else {
    $( 'tr.hmrc-pension-row' ).show( 'fast' );
    $( '#pension-expand .glyphicon' ).addClass( 'open' );
  }
});

$( '#tax-expand' ).on( 'click', function() {
  if ( $( 'tr.tax-bands' ).is( ':visible' ) ) {
    $( 'tr.tax-bands' ).hide( 'fast' );
    $( '#tax-expand .glyphicon' ).removeClass( 'open' );
  } else {
    $( 'tr.tax-bands' ).show( 'fast' );
    $( '#tax-expand .glyphicon' ).addClass( 'open' );
  }
});