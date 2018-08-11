function daterange_from_formatter_report(date) {
	$('#report_daterange_from span').html(date.format('MMMM D, YYYY'));

	if($('#report_daterange_to').data('daterangepicker')){
		var date_to = $('#report_daterange_to').data('daterangepicker').startDate;
		var date_from = $('#report_daterange_from').data('daterangepicker').startDate;
		if(date_from>date_to){
			$('#report_daterange_to').data('daterangepicker').setStartDate(date_from);
			$('#report_daterange_to').data('daterangepicker').setEndDate(date_from);
			$('#report_daterange_to span').html(date_from.format('MMMM D, YYYY'));
		}
	}
    }

    function daterange_to_formatter_report(date) {
	$('#report_daterange_to span').html(date.format('MMMM D, YYYY'));

	if($('#report_daterange_from').data('daterangepicker')){
		var date_to = $('#report_daterange_to').data('daterangepicker').startDate;
		var date_from = $('#report_daterange_from').data('daterangepicker').startDate;
		if(date_from>date_to){
			$('#report_daterange_from').data('daterangepicker').setStartDate(date_to);
			$('#report_daterange_from').data('daterangepicker').setEndDate(date_to);
			$('#report_daterange_from span').html(date_to.format('MMMM D, YYYY'));
		}
	}
    }
