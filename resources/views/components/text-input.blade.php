@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-stone focus:border-near-black focus:ring-near-black rounded-[26px] px-6 py-3 shadow-none w-full outline-none transition-colors']) !!}>
