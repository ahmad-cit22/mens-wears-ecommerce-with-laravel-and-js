@extends('admin.layouts.master')
@php
    $business = App\Models\Setting::find(1);
@endphp

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0">Create Bkash Transaction Record</h2>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Bkash Transaction Panel</li>
                        <li class="breadcrumb-item active">Create Transaction Record</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Main content -->
                    <form action="{{ route('bkash_panel.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="p-4 invoice col-10 m-auto" style="border-radius: 10px">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="selectmain">
                                            <label class="text-dark d-flex">Business Bkash Number *</label>
                                            <select name="bkash_business_id" class="select2 select-down" id="bkash_business_id" style="width: 100% !important;" required>
                                                <option value="0">--- Select an Option ---</option>
                                                @foreach ($bkash_nums as $num)
                                                    <option value="{{ $num->id }}">{{ $num->number . ' (' . $num->name . ')' }}</option>
                                                @endforeach
                                            </select>
                                            @error('bkash_business_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="selectmain">
                                            <label class="text-dark d-flex">Transaction Type *</label>
                                            <select name="tr_type" class="select2 select-down" id="tr_type" style="width: 100% !important;" required>
                                                <option value="0">--- Select an Option ---</option>
                                                <option value="CASH IN">CASH IN</option>
                                                <option value="CASH OUT">CASH OUT</option>
                                                <option value="SEND MONEY">SEND MONEY</option>
                                                <option value="PAYMENTS">PAYMENTS</option>
                                                <option value="RECHARGE">RECHARGE</option>
                                            </select>
                                            @error('tr_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-body">Amount *</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="number" step="0.001" name="amount" class="form-control" placeholder="Enter Bkash Amount" required>
                                        </fieldset>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="selectmain">
                                            <label class="text-dark d-flex">Purpose/Reason *</label>
                                            <select name="tr_purpose_id" class="select2 select-down" id="tr_purpose_id" style="width: 100% !important;" required>
                                                <option value="0">--- Select an Option ---</option>
                                                @foreach ($bkash_purposes as $purpose)
                                                    <option value="{{ $purpose->id }}">{{ $purpose->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('tr_purpose_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-body">Last Digits (Min: 4) *</label>
                                        <fieldset class="form-group mb-3">
                                            <input type="text" name="last_digit" class="form-control" placeholder="Enter Last Digits (Min: 4) of Customer Bkash Number" required>
                                        </fieldset>
                                        @error('last_digit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="text-body">Note/Comments
                                        </label>
                                        <fieldset class="form-group">
                                            <textarea name="comments" id="comments" class="form-control" placeholder="Add Notes/Comments about Bkash Transaction"></textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <button type="submit" class="mr-2 btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script></script>
@endsection
