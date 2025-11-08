# 🚧 Implementación Pendiente - Nuevas Funcionalidades

Fecha: 2025-11-08
Branch: `claude/analyze-repository-code-011CUqHCLRMtwdrMcMvCf9B1`

## ✅ Completado

1. **Favicon en perfil público** - ✅ DONE
   - Archivo: `resources/views/layouts/public.blade.php`
   - Agregado favicon SVG igual que layouts/app.blade.php

2. **Controlador y rutas Email Logs** - ✅ DONE
   - Controlador: `app/Http/Controllers/Admin/EmailLogController.php`
   - Rutas agregadas en `routes/web.php:262-263`

---

## 🚧 Pendiente de Implementación

### 1. Vistas para Email Logs

**Archivos a crear:**

#### `resources/views/portal/admin/email-logs/index.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Logs de Correos')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-envelope me-2"></i>Logs de Correos Electrónicos
      </h1>
    </div>
  </div>

  {{-- Estadísticas --}}
  <div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-envelope fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Enviados</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sent'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-check-circle fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Fallidos</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Hoy</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Este Mes</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['month'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Filtros --}}
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('portal.admin.email-logs.index') }}">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="status">Estado</label>
              <select name="status" id="status" class="form-control">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos</option>
                <option value="sent" {{ $status === 'sent' ? 'selected' : '' }}>Enviados</option>
                <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Fallidos</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="search">Buscar</label>
              <input type="text" name="search" id="search" class="form-control"
                     placeholder="Email, asunto, tipo..." value="{{ $search }}">
            </div>
          </div>
          <div class="col-md-2">
            <label class="d-block">&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-search"></i> Filtrar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Tabla --}}
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Registros ({{ $logs->total() }})</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Destinatario</th>
              <th>Asunto</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Orden</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logs as $log)
              <tr>
                <td>
                  <small class="text-muted">{{ $log->sent_at?->format('d/m/Y H:i') ?? $log->created_at->format('d/m/Y H:i') }}</small>
                </td>
                <td>{{ $log->recipient }}</td>
                <td>
                  <span class="d-inline-block text-truncate" style="max-width: 300px;" title="{{ $log->subject }}">
                    {{ $log->subject }}
                  </span>
                </td>
                <td>
                  <span class="badge badge-secondary">{{ $log->type ?? 'general' }}</span>
                </td>
                <td>
                  @if($log->status === 'sent')
                    <span class="badge badge-success">
                      <i class="fas fa-check"></i> Enviado
                    </span>
                  @else
                    <span class="badge badge-danger">
                      <i class="fas fa-times"></i> Fallido
                    </span>
                  @endif
                </td>
                <td>
                  @if($log->order)
                    <a href="{{ route('portal.admin.orders.show', $log->order) }}" class="btn btn-sm btn-outline-primary">
                      #{{ $log->order->order_number }}
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('portal.admin.email-logs.show', $log) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="fas fa-inbox fa-3x mb-3"></i>
                  <p>No se encontraron registros</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      {{ $logs->appends(request()->query())->links() }}
    </div>
  </div>
</div>
@endsection
```

#### `resources/views/portal/admin/email-logs/show.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Detalle Email Log')

@section('content')
<div class="container py-4">
  <div class="row mb-4">
    <div class="col">
      <a href="{{ route('portal.admin.email-logs.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Logs
      </a>
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-envelope-open me-2"></i>Detalle del Email
      </h1>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Información del Correo</h6>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th width="30%">Fecha de Envío</th>
                <td>{{ $log->sent_at?->format('d/m/Y H:i:s') ?? $log->created_at->format('d/m/Y H:i:s') }}</td>
              </tr>
              <tr>
                <th>Destinatario</th>
                <td>{{ $log->recipient }}</td>
              </tr>
              <tr>
                <th>Asunto</th>
                <td>{{ $log->subject }}</td>
              </tr>
              <tr>
                <th>Tipo</th>
                <td>
                  <span class="badge badge-secondary">{{ $log->type ?? 'general' }}</span>
                </td>
              </tr>
              <tr>
                <th>Estado</th>
                <td>
                  @if($log->status === 'sent')
                    <span class="badge badge-success badge-lg">
                      <i class="fas fa-check-circle"></i> Enviado Exitosamente
                    </span>
                  @else
                    <span class="badge badge-danger badge-lg">
                      <i class="fas fa-exclamation-triangle"></i> Error al Enviar
                    </span>
                  @endif
                </td>
              </tr>
              @if($log->error_message)
                <tr>
                  <th>Mensaje de Error</th>
                  <td>
                    <div class="alert alert-danger mb-0">
                      <i class="fas fa-bug me-2"></i>
                      <code>{{ $log->error_message }}</code>
                    </div>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      {{-- Orden Relacionada --}}
      @if($log->order)
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orden Relacionada</h6>
          </div>
          <div class="card-body">
            <p><strong>Número:</strong> #{{ $log->order->order_number }}</p>
            <p><strong>Plan:</strong> {{ $log->order->plan->name }}</p>
            <p><strong>Total:</strong> ₡{{ number_format($log->order->total, 2) }}</p>
            <p><strong>Estado:</strong>
              <span class="badge badge-{{ $log->order->status === 'completed' ? 'success' : 'warning' }}">
                {{ ucfirst($log->order->status) }}
              </span>
            </p>
            <a href="{{ route('portal.admin.orders.show', $log->order) }}" class="btn btn-primary btn-block">
              <i class="fas fa-external-link-alt"></i> Ver Orden
            </a>
          </div>
        </div>
      @endif

      {{-- Usuario Relacionado --}}
      @if($log->user)
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Usuario Relacionado</h6>
          </div>
          <div class="card-body">
            <p><strong>Nombre:</strong> {{ $log->user->name }}</p>
            <p><strong>Email:</strong> {{ $log->user->email }}</p>
            <p><strong>Teléfono:</strong> {{ $log->user->phone ?? 'N/A' }}</p>
            <a href="{{ route('portal.admin.clients.show', $log->user) }}" class="btn btn-info btn-block">
              <i class="fas fa-user"></i> Ver Cliente
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
```

### 2. Agregar Email Logs al Navbar Admin

**Archivo:** `resources/views/layouts/app.blade.php`

Buscar la sección del menú Admin (alrededor de línea 196-207) y agregar:

```blade
<li><a class="dropdown-item" href="{{ route('portal.admin.email-logs.index') }}"><i class="fa-solid fa-envelope"></i> Logs de Correos</a></li>
```

### 3. Verificar Email al Admin (Ya Existe)

El email al admin cuando se genera una orden **YA ESTÁ IMPLEMENTADO** en:
- `app/Http/Controllers/CheckoutController.php:166-169`
- Método: `sendAdminNotificationEmail()`

NO REQUIERE CAMBIOS.

### 4. Sistema de Notificaciones en Tiempo Real

**PENDIENTE DE IMPLEMENTACIÓN COMPLETA**

Opciones:
1. **Pusher/Laravel Echo** (requiere configuración externa)
2. **Polling cada 30 segundos** (más simple, no requiere servicios externos)

#### Opción Recomendada: Polling Simple

**Archivo:** `resources/views/layouts/app.blade.php` (agregar al final antes de `</body>`)

```javascript
<script>
// Solo para admins
@if(Auth::check() && Auth::user()->is_admin)
  // Polling cada 30 segundos para nuevas notificaciones
  let notifInterval = setInterval(() => {
    fetch('{{ route("portal.admin.notifications.unread") }}')
      .then(r => r.json())
      .then(data => {
        const badge = document.getElementById('notif-badge');
        const count = data.count || 0;

        if (count > 0) {
          if (!badge) {
            // Crear badge si no existe
            const navLink = document.querySelector('.navbar .fa-bell').parentElement;
            const newBadge = document.createElement('span');
            newBadge.id = 'notif-badge';
            newBadge.className = 'badge badge-danger badge-pill position-absolute';
            newBadge.style.cssText = 'top: 0; right: 0; font-size: 0.65rem;';
            newBadge.textContent = count;
            navLink.style.position = 'relative';
            navLink.appendChild(newBadge);
          } else {
            badge.textContent = count;
          }
        } else if (badge) {
          badge.remove();
        }
      })
      .catch(err => console.log('Notif poll error:', err));
  }, 30000); // 30 segundos
@endif
</script>
```

**Agregar icono campana al navbar:**

En `layouts/app.blade.php`, dentro del dropdown de Admin (línea ~196), agregar:

```blade
@if(Auth::user()->is_admin)
  <li class="nav-item">
    <a class="nav-link position-relative" href="{{ route('portal.admin.notifications.index') }}" title="Notificaciones">
      <i class="fa-solid fa-bell"></i>
    </a>
  </li>
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">
      <i class="fa-solid fa-screwdriver-wrench me-1"></i> Admin
    </a>
    ...
  </li>
@endif
```

---

## 📝 Checklist de Implementación

- [ ] Crear `resources/views/portal/admin/email-logs/index.blade.php`
- [ ] Crear `resources/views/portal/admin/email-logs/show.blade.php`
- [ ] Agregar link "Logs de Correos" al navbar admin
- [ ] (Opcional) Agregar icono de campana al navbar
- [ ] (Opcional) Agregar script de polling para notificaciones
- [ ] Testing: Verificar acceso a `/portal/admin/email-logs`
- [ ] Testing: Verificar filtros (todos/enviados/fallidos)
- [ ] Testing: Verificar búsqueda
- [ ] Testing: Ver detalle de un log
- [ ] Testing: Links a órdenes funcionan
- [ ] Commit y push
- [ ] Crear PR

---

## 🚀 Comandos para Deploy

```bash
# En desarrollo
git add -A
git commit -m "FEAT: Panel completo de Email Logs + Notificaciones en tiempo real"
git push -u origin claude/analyze-repository-code-011CUqHCLRMtwdrMcMvCf9B1

# Crear PR
https://github.com/Ronal280300/qr-pet-tag/compare/main...claude/analyze-repository-code-011CUqHCLRMtwdrMcMvCf9B1
```

---

## 📌 Notas Importantes

1. **Email al admin YA funciona** - No requiere cambios
2. **El modelo EmailLog ya existe** - Solo faltan las vistas
3. **Notificaciones:** El sistema básico ya existe, solo falta agregar polling/icono campana
4. **Favicon público:** Ya agregado en layouts/public.blade.php

---

## 🔗 Recursos

- **EmailLog Model:** `app/Models/EmailLog.php`
- **EmailLog Controller:** `app/Http/Controllers/Admin/EmailLogController.php`
- **Rutas:** `routes/web.php:262-263`
- **Notificaciones Controller:** Ya existe en el sistema
