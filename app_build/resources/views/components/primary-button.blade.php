<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full inline-flex justify-center items-center px-6 py-4 bg-electric-lime border border-near-black rounded-[26px] font-medium text-near-black hover:bg-[#aef03e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-near-black transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
