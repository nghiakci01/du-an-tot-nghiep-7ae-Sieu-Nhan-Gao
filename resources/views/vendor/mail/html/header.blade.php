@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<span style="font-size: 28px; font-weight: 700; color: #1a1a2e; letter-spacing: 2px;">ELITE</span>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
