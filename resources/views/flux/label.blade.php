@pure

@props([
    'badge' => null,
    'aside' => null,
])

@php
    $classes = Flux::classes()
        ->add('inline-flex items-center')
        ->add('text-sm font-medium')
        ->add('[:where(&)]:text-white [:where(&)]:dark:text-white')
        ;
@endphp

<ui-label {{ $attributes->class($classes) }} data-flux-label>
    {{ $slot }}

    <?php if (is_string($badge)): ?>
        <span class="ms-1.5 text-white/80 text-xs bg-white/40 px-1.5 py-1 -my-1 rounded-[4px] dark:bg-white/10 dark:text-zinc-300" aria-hidden="true">
            {{ $badge }}
        </span>
    <?php elseif ($badge): ?>
        <span class="ms-1.5" aria-hidden="true">
            {{ $badge }}
        </span>
    <?php endif; ?>

    <?php if ($aside): ?>
        <span class="ms-1.5 text-white/70 text-xs bg-white/5 px-1.5 py-1 -my-1 rounded-[4px] dark:bg-white/10 dark:text-zinc-300" aria-hidden="true">
            {{ $aside }}
        </span>
    <?php endif; ?>
</ui-label>
