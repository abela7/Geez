@extends('layouts.admin')

@section('title', 'Payroll Records')

@section('content')
    <livewire:admin.payroll.records :period="$period" />
@endsection

