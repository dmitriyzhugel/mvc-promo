<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Date Range Picker  -->
	<link rel="stylesheet" href="/app/vendor/bootstrap-daterangepicker/daterangepicker.css"/>

    <title>Расписание поездок</title>
  </head>
  <body>
    <div class="container">
        <h2>Расписание поездок</h2>
        <div class="row">
            <div class="col-md-3">
                <div id="report_daterange_from"
        		     style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
        			<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        			<span></span> <b class="caret"></b>
        		</div>
	        </div>
        	<div class="col-md-3">
        		<div id="report_daterange_to"
        		     style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
        			<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        			<span></span> <b class="caret"></b>
        		</div>
        	</div>
            <div class="col-md-6">
                <button id="btnLoadMain" type="button" class="btn btn-primary">Показать</button>
                <button id="btnAddRoute" type="button" class="btn btn-success" style="float:right;">Добавить маршрут</button>
            </div>
        </div>
        <table class="table" id="table-report-shedule" style="margin-top:10px;">
            <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Регион</th>
              <th scope="col">Дата выезда из Москвы</th>
              <th scope="col">ФИО курьера</th>
              <th scope="col">Дата прибытия в регион</th>
            </tr>
          </thead>
        <?php foreach($routes as $route){ ?>
            <tr>
                <td><?=$route['id'];?></td>
                <td><?=$route['region'];?></td>
                <td><?=date('d.m.Y',strtotime($route['start_date']));?></td>
                <td><?=$route['surname'];?> <?=$route['firstname'];?> <?=$route['lastname'];?></td>
                <td><?=date('d.m.Y',strtotime($route['arrival_date']));?></td>
            </tr>
        <?php } ?>
        </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="/app/vendor/bootstrap-daterangepicker/moment-with-locales.min.js"></script>
    <script src="/app/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/js/functions.js"></script>

    <script type="text/javascript">
    $( document ).ready(function() {
        // Handler for .ready() called.
        moment.locale('ru');
        daterange_from_formatter_report(moment('<?=$cur_date;?>'));
		daterange_to_formatter_report(moment('<?=$cur_date;?>'));
        $('#newStartDate span').html(moment().format('MMMM D, YYYY'));

        $('#report_daterange_from').daterangepicker({
			autoApply: true,
			singleDatePicker: true,
			locale: {
				applyLabel: 'OK',
				cancelLabel: 'Отмена',
				customRangeLabel: 'Ваш период',
				format: 'DD.MM.YYYY'
			}
		}, daterange_from_formatter_report);

        $('#report_daterange_to').daterangepicker({
			autoApply: true,
			singleDatePicker: true,
			locale: {
				applyLabel: 'OK',
				cancelLabel: 'Отмена',
				customRangeLabel: 'Ваш период',
				format: 'DD.MM.YYYY'
			}
		}, daterange_to_formatter_report);

        $('#newStartDate').daterangepicker({
			autoApply: true,
			singleDatePicker: true,
			locale: {
				applyLabel: 'OK',
				cancelLabel: 'Отмена',
				customRangeLabel: 'Ваш период',
				format: 'DD.MM.YYYY'
			}
		}, function(date){
            $('#newStartDate span').html(date.format('MMMM D, YYYY'));
            calc_arrival_date();
        });

        $('#report_daterange_from').data('daterangepicker').setStartDate(moment('<?=$cur_date;?>'));
		$('#report_daterange_from').data('daterangepicker').setEndDate(moment('<?=$cur_date;?>'));
		$('#report_daterange_to').data('daterangepicker').setStartDate(moment('<?=$cur_date;?>'));
		$('#report_daterange_to').data('daterangepicker').setEndDate(moment('<?=$cur_date;?>'));
        $('#newStartDate').data('daterangepicker').setEndDate(moment('<?=$cur_date;?>'));

        function loadRoutes(){
            var date_from = $('#report_daterange_from').data('daterangepicker').startDate.format('YYYY-MM-DD');
		    var date_to = $('#report_daterange_to').data('daterangepicker').startDate.format('YYYY-MM-DD');
            $('#table-report-shedule').html('<tbody><tr><td><img src="/img/ajax-loader.gif"/></td></tr></tbody>');
            $.ajax({
			type: "POST",
			url: '/shedule/detail',
			dataType: "text",
			cache: false,
			data: {
				date_from: date_from,
				date_to: date_to
			}, // post data
			success: function (data) {
				$('#table-report-shedule').html(data);
			}
		});
        }

        function calc_arrival_date(){
            var region_id = parseInt($('#newRegion').val());
            var start_date = $('#newStartDate').data('daterangepicker').startDate.format('YYYY-MM-DD');
                if(region_id && start_date){
                    $.ajax({
        			type: "POST",
        			url: '/shedule/calc_arrival_date',
        			dataType: "json",
        			cache: false,
        			data: {
        				region_id: region_id,
        				start_date: start_date
        			}, // post data
        			success: function (data) {
        				if(data.status == 'OK'){
                            $('#arrival-date-alert').show();
                            $('#arrival-date-calc').html( data.arrival_date );
                        }
        			}
                });
            }else{
                $('#arrival-date-alert').hide();
                $('#arrival-date-calc').html( '&nbsp;' );
            }
        }

        $('#btnLoadMain').click(function () {
            loadRoutes();
        });

        $('#btnAddRoute').click(function (){
            $('#arrival-date-alert').hide();
            $('#arrival-date-calc').html( '&nbsp;' );
            $('#newRegion').val(0);
            $('#newCourier').val(0);
            $('#frmAddRouteModal').modal('show');
        });

        $('#newRegion').change(function(){
            calc_arrival_date();
        });

        $('#btnSaveRoute').click(function(){
            var region_id = parseInt($('#newRegion').val());
            var start_date = $('#newStartDate').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var courier_id = parseInt($('#newCourier').val());
            if(region_id && start_date && courier_id){
                $.ajax({
                type: "POST",
                url: '/shedule/save',
                dataType: "json",
                cache: false,
                data: {
                    region_id: region_id,
                    start_date: start_date,
                    courier_id: courier_id
                }, // post data
                success: function (data) {
                    if(data.status == 'OK'){
                        $('#frmAddRouteModal').modal('hide');
                        location.href=location.href;
                    }else{
                        $('#error-alert').show();
                        $('#error-alert').html( data.message );
                        $('#error-alert').fadeOut( 3000 );
                    }
                }
            });
        }else{
            $('#error-alert').show();
            $('#error-alert').html( 'Заполните форму!' );
            $('#error-alert').fadeOut( 3000 );
        }
        });

    });
    </script>

    <!-- Модальная форма -->
    <!-- Modal -->
    <div class="modal fade" id="frmAddRouteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Новый маршрут</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="newRegion"><b>Регион</b></label>
                    <select class="form-control" id="newRegion">
                        <option value="0">Выберите регион...</option>
                      <?php foreach($regions as $region){ ?>
                          <option value="<?=$region['id'];?>"><?=$region['name'];?></option>
                      <?php } ?>
                    </select>
                 </div>
                 <div class="form-group">
                     <label for="newStartDate"><b>Дата выезда из Москвы</b></label>
                     <div id="newStartDate"
             		     style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
             			<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
             			<span></span> <b class="caret"></b>
             		</div>
                 </div>
                 <div class="form-group">
                    <label for="newCourier"><b>Курьер</b></label>
                    <select class="form-control" id="newCourier">
                        <option value="0">Выберите курьера...</option>
                      <?php foreach($couriers as $courier){ ?>
                          <option value="<?=$courier['id'];?>"><?=$courier['surname'];?> <?=$courier['firstname']?> <?=$courier['lastname'];?></option>
                      <?php } ?>
                    </select>
                 </div>
                 <div class="alert alert-danger" role="alert" id="error-alert" style="display:none;"></div>
                 <div class="alert alert-warning" role="alert" id="arrival-date-alert" style="display:none;">
                     Дата прибытия в регион: <span id="arrival-date-calc"></span>
                 </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            <button id="btnSaveRoute" type="button" class="btn btn-primary">Сохранить</button>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
