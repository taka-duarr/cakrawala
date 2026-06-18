@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-slate-950 focus:border-slate-950 focus:ring-2 focus:ring-slate-950 rounded-xl shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] focus:shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition-all']) }}>
