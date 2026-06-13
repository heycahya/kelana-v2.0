<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3.5 bg-red-600 border border-transparent rounded-[26px] font-semibold text-sm text-white hover:bg-red-700 hover:scale-[1.02] active:scale-95 transition-all duration-300 ease-in-out focus:outline-none disabled:opacity-25']) }}>
    {{ $slot }}
</button>
