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
                    <h1 class="m-0">Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Order</li>
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
                    <!-- <div class="callout callout-info">
                                                                                                                                                                                                                                                                                                                              <h5><i class="fas fa-info"></i> Note:</h5>
                                                                                                                                                                                                                                                                                                                              This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
                                                                                                                                                                                                                                                                                                                            </div> -->


                    <!-- Main content -->
                    <div class="invoice p-3 mb-3">
                        <!-- title row -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                @if ($order->is_final == 0)
                                    {{-- <div class="col-12" align="right">
                                        <a href="#confirmSell{{ $order->id }}" data-toggle="modal" class="btn btn-primary bg-purple"><i class="fas fa-check"></i> Mark as Sold</a>
                                        <hr>
                                    </div> --}}
                                @endif
                                <div class="col-10">
                                    <h4>
                                        <img src="{{ asset('images/website/' . $business->footer_logo) }}" width="200">
                                        <small class="float-right" style="float: right;">Date: {{ Carbon\Carbon::parse($order->created_at)->format('d M Y, g:ia') }}</small>
                                    </h4><br>
                                </div>
                                <div class="col-2">
                                    <a href="{{ route('fos.index') }}" class="btn btn-info bg-primary float-right">Order Sheet</a>

                                </div>
                                <br>

                                <hr style="color: #800020;">
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-8 invoice-col">

                                    <address>
                                        Name: <strong>{{ $order->name }}</strong><br>
                                        Phone: <strong>{{ $order->phone }}</strong><br>
                                        Email: {{ $order->email }}<br>
                                        Shipping Address: {{ $order->shipping_address }}<br>
                                        @if ($order->courier_id)
                                            Courier Name: {{ $order->courier->name }}
                                        @endif
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <div>
                                            Status: <span class="badge badge-{{ $order->status->color }}"> {{ $order->status->title }}</span><br>
                                            Special Status: <span class="badge badge-{{ $order->special_status->color }}"> {{ $order->special_status->title }}</span>
                                        </div>

                                        Order Track Number: <strong># {{ $order->code }}</strong><br>
                                    </address>
                                    <form action="{{ route('order.advance.payment', $order->id) }}" method="POST">
                                        @csrf
                                        <div class="form-froup">
                                            <label>Advance Amount</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" name="amount" value="{{ $order->advance }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary bg-purple" type="submit">Save payment</button>
                                                </div>
                                            </div>
                                            @error('amount')
                                                <span class="invalid-feedback" role="alert" style="display: block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row -->

                            <div class="row">


                            </div>

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->order_product as $product)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    @if ($product->product != null)
                                                        <td>{{ $product->product->title }}{{ isset($product->size_id) ? ' - ' . $product->size->title : '' }}
                                                            @if ($product->return_qty != null)
                                                                <span class="text-danger ml-2">({{ $product->return_qty }} Product(s) Returned)</span>
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td> --</td>
                                                    @endif
                                                    <td>{{ env('CURRENCY') }}{{ $product->price }}</td>
                                                    <td>{{ $product->qty }}</td>
                                                    <td>{{ env('CURRENCY') }}{{ $product->price * $product->qty }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4" align="right">Discount (-):</td>
                                                <td>{{ env('CURRENCY') }}{{ $order->discount_amount == null ? 0 : $order->discount_amount }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="4" align="right">Total:</td>
                                                <td>{{ env('CURRENCY') }}{{ round($order->price) }}</td>
                                            </tr>
                                            @if ($order->advance)
                                                <tr>
                                                    <td colspan="4" align="right">Advanced (-):</td>
                                                    <td>{{ env('CURRENCY') }}{{ $order->advance }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="4" align="right">Total Payable:</td>
                                                <td>{{ env('CURRENCY') }}{{ round($order->price - $order->advance) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->



                            <!-- this row will not appear when printing -->
                            <!-- <div class="row no-print">
                                                                                                                                                                                                                                                                                                                                <div class="col-12">
                                                                                                                                                                                                                                                                                                                                  
                                                                                                                                                                                                                                                                                                                                  <a href="" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="fas fa-download"></i> Generate PDF</a>
                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                              </div> -->
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal -->
    <div class="modal fade" id="confirmSell{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('order.convert.sell', $order->id) }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary bg-purple" type="submit"><i class="fas fa-check"></i> Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- add loss Modal -->
    <div class="modal fade" id="add-loss" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Loss</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('expenseentry.loss.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank*</label>
                                    <select class="form-control @error('bank_id') is-invalid @enderror" name="bank_id" required>
                                        <option value="">Please select relevant bank</option>
                                        @foreach (App\Models\Bank::orderBy('name', 'ASC')->get() as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('bank_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount*</label>
                                    <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" required>
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="text" name="note" class="form-control @error('note') is-invalid @enderror">
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        //Date range picker
        $('#reservation').daterangepicker();
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
