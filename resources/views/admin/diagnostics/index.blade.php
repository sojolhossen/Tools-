@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">System Status & Diagnostic Health</h1>
            <p class="text-xs text-slate-400">
                Operational integrity inspection of database, filesystem storage permissions, drivers, and external integrations.
            </p>
        </div>
    </div>

    <!-- Diagnostic Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($checks as $category => $items)
            <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400 mb-4">{{ $category }}</h2>

                <div class="space-y-3">
                    @foreach($items as $label => $value)
                        <div class="flex items-center justify-between py-2 border-b border-slate-800/60 last:border-0 text-xs">
                            <span class="text-slate-400 font-medium">{{ $label }}</span>
                            <span class="font-mono text-slate-200 font-semibold {{ str_contains((string) $value, 'OK') || str_contains((string) $value, 'Connected') || str_contains((string) $value, 'Configured') ? 'text-emerald-400' : '' }}">
                                {{ $value }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
