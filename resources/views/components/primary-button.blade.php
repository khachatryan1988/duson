<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary py-2']) }}>
    {{ $slot }}
</button>
