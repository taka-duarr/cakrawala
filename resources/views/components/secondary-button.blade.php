<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-5 py-2.5 bg-white border-2 border-slate-950 rounded-xl font-black text-xs text-slate-950 uppercase tracking-widest shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-950 focus:ring-offset-2 disabled:opacity-25 active:translate-x-0.5 active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(15,23,42,1)] transition-all duration-150']) }}>
    {{ $slot }}
</button>
