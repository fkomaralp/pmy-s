<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-pgreen-800 hover:bg-pgreen-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-pgreen-900 focus:outline-none focus:border-pgreen-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
