
@if (isset($status['success']))
<div class="alert alert-success" role="alert"> 
    <strong>
        <span class="glyphicon glyphicon-ok-sign"></span> Hecho!
    </strong> 
    {{ $status['success'] }} 
</div>
@elseif (isset($status['warning']))
<div class="alert alert-warning" role="alert"> 
    <strong>
        <span class="fa fa-exclamation-triangle"></span> Atención!
    </strong> 
    {{ $status['warning'] }}
</div>
@elseif (isset($status['danger']))
<div class="alert alert-danger" role="alert"> 
    <strong>
        <span class="glyphicon glyphicon-remove-sign"></span> Error!
    </strong> 
    {{ $status['danger'] }}
</div>
@elseif (isset($status['info']))
<div class="alert alert-info" role="alert"> 
    <strong>
        <span class="glyphicon glyphicon-info-sign"></span> Información!
    </strong> 
    {{ $status['info'] }}
</div>
@elseif (isset($status['success-contenido']))
<div class="alert alert-success" role="alert"> 
    <strong>
        <span class="glyphicon glyphicon-ok-sign"></span>
    </strong> 
    {{ $status['success-contenido'] }} 
</div>
@elseif (isset($status['warning-contenido']))
<div class="alert alert-warning" role="alert"> 
    <strong>
        <span class="glyphicon glyphicon-warning-sign"></span>
    </strong> 
    {{ $status['warning-contenido'] }}
</div>
@endif
