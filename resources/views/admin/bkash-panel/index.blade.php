@extends('admin.layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bkash Transaction Panel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Bkash Transactions</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="container-fluid">
                {{-- bkash_panel.store --}}
                <form id="add-form" class="mb-4" action="{{ route('bkash_panel.store') }}" method="POST" style="display: none;">
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
                                        <input type="number" name="amount" step="0.001" class="form-control" placeholder="Enter Bkash Amount" required>
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
                                <button class="btn btn-danger mr-2" id="hide-add-form-btn" type="button" style="display: none">Hide Form</button>
                                <button type="submit" class="mr-2 btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-end">
                        <button class="btn btn-info btn-sm mb-3 mr-2 " id="show-add-form-btn" type="button">Show Create Form</button>
                    </div>
                    {{-- bkash_panel.search --}}
                    <form action="{{ route('bkash_panel.search') }}" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date From</label>
                                    <input type="date" name="date_from" class="form-control @error('date_from') is-invalid @enderror" @if ($date_from != '') value="{{ $date_from }}" @endif>
                                    @error('date_from')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date To</label>
                                    <input type="date" name="date_to" class="form-control @error('date_to') is-invalid @enderror" @if ($date_to != '') value="{{ $date_to }}" @endif>
                                    @error('date_to')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Bkash Number</label>
                                    <select name="bkash_business_id" class="select2 form-control @error('bkash_business_id') is-invalid @enderror">
                                        <option value="">Please Select a Number</option>
                                        @foreach (App\Models\BkashNumber::all() as $number)
                                            <option value="{{ $number->id }}" @if ($bkash_business_id == $number->id) selected @endif>{{ $number->number . ' (' . $number->name . ')' }}</option>
                                        @endforeach

                                    </select>
                                    @error('bkash_business_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="color: #fff;">.</label>
                                    <button type="submit" class="form-control btn btn-primary">Search</button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label style="color: #fff;">.</label>
                                <a href="{{ route('bkash_panel.index') }}" class="form-control btn btn-primary">Clear Filter</a>
                            </div>

                        </div>
                    </form>

                    <div class="row mt-4">
                        <div class="col-md-8 m-auto">
                            <table id="" class="table table-bordered table-striped table-hover">
                                <tr>
                                    <td align="center" style="font-size: 18px !important;" colspan="4">
                                        @if ($bkash_business_id == '')
                                            Select a Number to Get the Data
                                        @else
                                            {{ $bkash_number->number . ' (' . $bkash_number->name . ')' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="25%">CASH IN</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ number_format($cash_in, 2) }}</td>
                                    <th width="25%">CASH OUT</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ number_format($cash_out, 2) }}</td>
                                </tr>
                                <tr>
                                    <th width="25%">SEND MONEY</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ number_format($send_money, 2) }}</td>
                                    <th width="25%">PAYMENTS</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ number_format($payments, 2) }}</td>
                                </tr>
                                <tr>
                                    <th width="25%">RECHARGE</th>
                                    <td class="text-center font-weight-bold">&#2547; {{ number_format($recharge, 2) }}</td>
                                    <th style="font-size: 20px !important; background: rgba(199, 129, 1, 0.176)" width="25%">CURRENT BALANCE</th>
                                    <td style="font-size: 20px !important; background: rgba(199, 129, 1, 0.176)" class="text-center font-weight-bold text-{{ $current_balance >= 0 ? 'success' : 'danger' }}">&#2547; {{ number_format($current_balance, 2) }}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                @include('admin.partials.page_search')
                <h4 class="ml-3">Transaction List</h4>
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Date/Time</th>
                                <th>Bkash Number</th>
                                <th>TR Type</th>
                                <th>TR Purpose</th>
                                <th width="13%">Comments</th>
                                <th>Amount</th>
                                <th>Last Digit</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function() {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                // ordering: false,
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'business_bkash_number'
                    },
                    {
                        data: 'tr_type'
                    },
                    {
                        data: 'purpose'
                    },
                    {
                        data: 'comments'
                    },
                    {
                        data: 'amount'
                    },
                    {
                        data: 'last_digit'
                    },
                    {
                        data: 'created_by',
                    },
                ],
            });
        });
    </script>

    <script>
        // var isShown = false;
        $('#show-add-form-btn').click(function() {
            $('#add-form').show();
            $('#hide-add-form-btn').show();
            $('#show-add-form-btn').hide();
            // var isShown = true;
        })

        $('#hide-add-form-btn').click(function() {
            $('#add-form').hide();
            $('#hide-add-form-btn').hide();
            $('#show-add-form-btn').show();
            // var isShown = false;
        })
    </script>
@endsection
