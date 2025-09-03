@props(['url'])
<tr>
<td class="header">
<a href="{{ config('app.url') }}" style="display:inline-flex;align-items:center;gap:10px;color:#1e7cf2;text-decoration:none;">
    {{-- Pon tu logo; ubícalo en public/images/logo-mail.png --}}
    <img src="{{ asset('images/logo-mail.png') }}" alt="{{ config('app.name') }}" style="height:32px;">
    <span style="font-weight:800;">{{ config('app.name') }}</span>
</a>
</td>
</tr>

