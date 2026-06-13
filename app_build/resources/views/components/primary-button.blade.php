<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex justify-center items-center px-6 py-3.5 bg-electric-lime border border-transparent rounded-[26px] text-sm font-semibold text-white hover:bg-near-black hover:text-white hover:scale-[1.02] active:scale-95 transition-all duration-300 ease-in-out focus:outline-none']) }}>
    {{ $slot }}
</button>
