@extends('admin.layouts.app')

@section('title', 'Email Templates')
@section('page-title', 'Email Templates')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <p class="text-sm text-gray-500 max-w-2xl">
        Saved copy for the offer emails you send from the customer list. Loading a template on the compose screen
        fills in the subject, headline, message, discount code and button — you can still edit anything before sending.
    </p>
    <a href="{{ route('admin.email-templates.create') }}"
       class="px-4 py-2 bg-[#bb976d] text-white text-sm font-medium rounded-lg hover:bg-[#a8845a] transition-colors whitespace-nowrap">
        <i class="mdi mdi-plus mr-1"></i> New Template
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
    <table class="w-full text-sm min-w-[720px]">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Name</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Subject</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Code</th>
                <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                <th class="text-right px-5 py-3 font-medium text-gray-600">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($templates as $template)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $template->name }}</p>
                    @if($template->heading)<p class="text-xs text-gray-400">{{ $template->heading }}</p>@endif
                </td>
                <td class="px-5 py-3 text-gray-600 max-w-xs truncate" title="{{ $template->subject }}">{{ $template->subject }}</td>
                <td class="px-5 py-3">
                    @if($template->promo_code)
                        <code class="text-xs text-[#bb976d] bg-[#bb976d]/8 px-1.5 py-0.5 rounded">{{ $template->promo_code }}</code>
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    @if($template->is_active)
                        <span class="text-xs font-medium px-2 py-0.5 rounded bg-green-50 text-green-700 border border-green-100">Active</span>
                    @else
                        <span class="text-xs font-medium px-2 py-0.5 rounded bg-gray-100 text-gray-500 border border-gray-200">Hidden</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-right whitespace-nowrap">
                    <a href="{{ route('admin.email-templates.edit', $template) }}"
                       class="text-[#bb976d] hover:underline text-xs font-medium">Edit</a>
                    <form method="POST" action="{{ route('admin.email-templates.destroy', $template) }}" class="inline ml-3"
                          onsubmit="return confirm('Delete the “{{ $template->name }}” template? Emails already sent are unaffected.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center">
                    <p class="text-gray-400 mb-3">No templates yet.</p>
                    <a href="{{ route('admin.email-templates.create') }}" class="text-[#bb976d] hover:underline text-sm font-medium">Create your first one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
