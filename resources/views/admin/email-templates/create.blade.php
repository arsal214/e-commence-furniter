@extends('admin.layouts.app')

@section('title', 'New Email Template')
@section('page-title', 'New Email Template')

@section('content')

<div class="mb-5">
    <a href="{{ route('admin.email-templates.index') }}"
       class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-800 transition-colors">
        <i class="mdi mdi-arrow-left"></i> Back to templates
    </a>
</div>

<form method="POST" action="{{ route('admin.email-templates.store') }}">
    @csrf
    @include('admin.email-templates._form', ['template' => null])
</form>

@endsection
