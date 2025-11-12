@props([
    'width' => '250',
    'height' => '250',
    'message' => 'Data Tidak Ditemukan'
])

<div class="flex flex-col justify-center items-center mb-1">
    <iframe src="https://lottie.host/embed/d55c1c38-580f-4a8d-bbf8-aa84ed9962c1/gQE574wAc1.json" width="{{ $width }}" height="{{ $height }}"></iframe>
    <flux:text variant="subtle">{{ $message }}</flux:text>
</div>
