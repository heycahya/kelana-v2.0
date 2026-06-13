<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-stone/50 border border-transparent rounded-[26px] font-semibold text-sm text-near-black hover:bg-near-black hover:text-white hover:scale-[1.02] active:scale-95 transition-all duration-300 ease-in-out focus:outline-none focus:ring-0 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
