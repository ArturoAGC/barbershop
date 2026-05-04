@component('mail::message')
# ¡Tu reserva está confirmada! ✂️

Hola, **{{ $reservation->user->name }}**.

Tu cita en **BarberBook** ha sido confirmada. Aquí están los detalles:

@component('mail::panel')
**Servicio:** {{ $reservation->service->name }}
**Barbero:** {{ $reservation->barber->name }}
**Fecha:** {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
**Hora:** {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
@endcomponent

Si necesitas cancelar tu cita, puedes hacerlo desde tu cuenta.

Gracias por elegir BarberBook.

@component('mail::button', ['url' => config('app.url')])
Ver mis reservas
@endcomponent

@endcomponent