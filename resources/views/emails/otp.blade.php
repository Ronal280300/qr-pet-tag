{{-- resources/views/emails/otp.blade.php --}}
<!doctype html><html><body style="font-family:system-ui,Segoe UI,Roboto">
  <h2>Tu código</h2>
  <p>Usa este código para continuar:</p>
  <div style="font-size:28px;font-weight:800;letter-spacing:4px">
    {{ $code }}
  </div>
  <p style="color:#6b7280">Caduca en 10 minutos.</p>
</body></html>