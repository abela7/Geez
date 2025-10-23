@extends('layouts.admin')

@section('title', 'Payroll Reports - ' . $period->name)
@section('page_title', 'Payroll Reports')

@section('content')
<livewire:admin.payroll.reports :period="$period" />
@endsection

