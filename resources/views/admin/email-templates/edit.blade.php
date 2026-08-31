@extends('admin.layouts.app')

@section('title', 'Edit Email Template')
@section('page-title', 'Edit Email Template')

@section('content')

<div class="mb-5">
    <a href="{{ route('admin.email-templates.index') }}"
       class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
        <i class="mdi mdi-arrow-left"></i> Back to templates
    </a>
</div>

<form method="POST" action="{{ route('admin.email-templates.update', $template) }}">
    @csrf
    @method('PUT')
    @include('admin.email-templates._form', ['template' => $template])
</form>

@endsection
