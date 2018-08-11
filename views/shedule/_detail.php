<table class="table" id="shedule-table">
    <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Регион</th>
      <th scope="col">Дата выезда из Москвы</th>
      <th scope="col">ФИО курьера</th>
      <th scope="col">Дата прибытия в регион</th>
    </tr>
  </thead>
<?php if(sizeof($routes)){ ?>
<?php foreach($routes as $route){ ?>
    <tr>
        <td><?=$route['id'];?></td>
        <td><?=$route['region'];?></td>
        <td><?=date('d.m.Y',strtotime($route['start_date']));?></td>
        <td><?=$route['surname'];?> <?=$route['firstname'];?> <?=$route['lastname'];?></td>
        <td><?=date('d.m.Y',strtotime($route['arrival_date']));?></td>
    </tr>
<?php } ?>
<?php }else{ ?>
    <tr>
        <td colspan="5">Нет данных. Выберите другой период</td>
    </tr>
<?php } ?>
</table>
