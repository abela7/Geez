@extends('layouts.admin')

@section('title', 'Review Payroll - ' . $period->name)
@section('page_title', 'Review Payroll')

@section('content')
<livewire:admin.payroll.review :period="$period" />
@endsection
