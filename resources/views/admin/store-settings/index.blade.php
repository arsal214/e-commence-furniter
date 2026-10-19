@extends('admin.layouts.app')

@section('title', 'Store Settings')
@section('page-title', 'Store Settings')

@section('content')
@php
    $in = 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#bb976d]';
    $show = function (string $type, $v) {
        return $type === 'bool' ? ($v ? 'On' : 'Off') : ($type === 'money' ? '$' . number_format((float) $v, 2) : (string) $v);
    };
@endphp
<div class="max-w-3xl space-y-5">

    @if(session('success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm"><ul class="text-red-700 space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul></div>
    @endif

    <div class="p-4 bg-white rounded-xl border border-gray-100 text-sm text-gray-600 space-y-1">
        <p>Changes here go live immediately, with no file editing or server commands.</p>
        <p>Leave a field on <strong>Default</strong> (or blank) to use the value from the server's <code>.env</code> file, shown beside each setting. Payment keys, tokens and passwords are never edited here: they stay in <code>.env</code> for safety.</p>
    </div>

    <form method="POST" action="{{ route('admin.store-settings.update') }}" class="space-y-5">
        @csrf @method('PUT')

        @foreach($groups as $group => $items)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider pb-2 border-b border-gray-100">{{ $group }}</h3>

                @foreach($items as $key => $def)
                    @php
                        [, $label, $help, $type] = $def;
                        $current = old("settings.$key", $saved[$key] ?? '');
                        $isSet = ($saved[$key] ?? '') !== '';
                    @endphp
                    <div>
                        <label for="s-{{ $key }}" class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>

                        @if($type === 'bool')
                            <select id="s-{{ $key }}" name="settings[{{ $key }}]" class="{{ $in }} bg-white">
                                <option value="" @selected($current === '')>Default ({{ $show('bool', $defaults[$key]) }})</option>
                                <option value="on" @selected($current === 'on')>On</option>
                                <option value="off" @selected($current === 'off')>Off</option>
                            </select>
                        @else
                            <input id="s-{{ $key }}" name="settings[{{ $key }}]" type="number" class="{{ $in }}"
                                   step="{{ $type === 'money' ? '0.01' : '1' }}" min="{{ $def[5] }}" max="{{ $def[6] }}"
                                   value="{{ $current }}" placeholder="Default ({{ $show($type, $defaults[$key]) }})">
                        @endif

                        <p class="text-xs text-gray-400 mt-1">{{ $help }}
                            <span class="{{ $isSet ? 'text-[#bb976d] font-semibold' : '' }}">{{ $isSet ? 'Set here.' : 'Using the .env default.' }}</span>
                        </p>
                    </div>
                @endforeach
            </div>
        @endforeach

        <button class="bg-[#bb976d] text-white font-semibold rounded-lg px-6 py-2.5 text-sm">Save settings</button>
    </form>
</div>
@endsection
