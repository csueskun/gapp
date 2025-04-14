
@if (isset($status['success']))
<script>mostrarSuccess("{{$status['success']}}");</script>
@elseif (isset($status['warning']))
<script>mostrarWarning("{{$status['warning']}}");</script>
@elseif (isset($status['danger']))
<script>mostrarError("{{$status['danger']}}");</script>
@elseif (isset($status['info']))
<script>mostrarInfo("{{$status['info']}}");</script>
@elseif (isset($status['success-contenido']))
<script>mostrarSuccess("{{$status['success-contenido']}}");</script>
@elseif (isset($status['warning-contenido']))
<script>mostrarWarning("{{$status['warning-contenido']}}");</script>
@endif
