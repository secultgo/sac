@if ($status == '0')
    <span class="badge badge-danger">Aberto</span>
@elseif ($status == '1')
    <span class="badge badge-warning">Em Andamento</span>
@elseif ($status == '2')
    <span class="badge badge-success">Concluído</span>
@endif