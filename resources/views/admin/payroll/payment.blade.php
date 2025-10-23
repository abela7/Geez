@extends('layouts.admin')

@section('title', 'Process Payments - ' . $period->name)
@section('page_title', 'Process Payments')

@section('content')
<livewire:admin.payroll.payment :period="$period" />
@endsection
