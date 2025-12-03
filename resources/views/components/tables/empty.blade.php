@props([
    'colspan' => 1,
    'text' => 'Tidak ada data yang ditemukan',
])

<tr>
    <td colspan="{{ $colspan }}" class="px-5 py-3 text-center text-white/70">
        {{ $text }}
    </td>
</tr>
