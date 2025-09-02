{{-- resources/views/partials/flash.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  @if (session('swal'))
    // Mensaje ya armado desde el controlador (array para SweetAlert2)
    Swal.fire(@json(session('swal')));
  @elseif ($errors->any())
    // Validaciones de Laravel
    Swal.fire({
      icon: 'error',
      title: 'Corrige los siguientes errores',
      html: `{!! implode('<br>', $errors->all()) !!}`,
      confirmButtonText: 'OK'
    });
  @else
    @php
      // Buscar el primer key de mensaje disponible
      $keys  = ['success','status','error','danger','warning','info'];
      $found = collect($keys)->first(fn ($k) => session()->has($k));
      $text  = $found ? session($found) : null;

      // Mapear icono/título según tipo
      $icon = match ($found) {
        'error','danger' => 'error',
        'warning'        => 'warning',
        'info'           => 'info',
        default          => 'success',
      };

      $title = match ($icon) {
        'error'   => 'Ocurrió un problema',
        'warning' => 'Atención',
        'info'    => 'Aviso',
        default   => 'Éxito',
      };
    @endphp

    @if ($text)
      Swal.fire({
        icon: @json($icon),
        title: @json($title),
        text: @json($text),
        confirmButtonText: 'OK'
      });
    @endif
  @endif
});
</script>
