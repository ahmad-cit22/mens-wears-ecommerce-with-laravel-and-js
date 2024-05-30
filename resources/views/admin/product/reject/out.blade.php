@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">reject-product-out</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <h4 class="my-2 ml-3">Reject Product Out Form</h4>
                <div class="card-header">
                    <form class="mt-2" action="{{ route('reject.product.out.store') }}" method="POST">
                        @csrf
                        {{-- <div class="row" id="barcodeContainer">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Scan Barcode</label>
                                        <input type="text" id="barcode" name="barcode" class="form-control  @error('barcode') is-invalid @enderror" placeholder="Scan Barcode" autofocus>
                                    </div>
                                </div>
                            </div> --}}
                        <div class="row" id="formContainer">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product*</label>
                                    <select class="select2 form-control  @error('product_id') is-invalid @enderror" name="product_id" id="product_id" required>
                                        <option value="">---- Select a Product ----</option>
                                        @foreach ($reject_stocks as $stock)
                                            @if ($stock->qty > 0)
                                                <option value="{{ $stock->product_id }}">{{ $stock->product->title }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Size*</label>
                                    <select class="select2 form-control" name="size_id" id="size_id" required>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qty*</label>
                                    <input type="number" name="qty" id="qty" class="form-control  @error('qty') is-invalid @enderror" placeholder="Quantity" required>
                                    @error('qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="mt-4" id="is_others_income_label"><input id="is_others_income" type="checkbox" name="is_others_income" class=" @error('is_others_income') is-invalid @enderror" value="1">&nbsp;&nbsp;Treat as others income</label>
                                    @error('is_others_income')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="price_box" style="display: none">
                                    <label>Price</label>
                                    <input type="number" id="price" name="price" class="form-control  @error('price') is-invalid @enderror" placeholder="Price">
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="bank_id_box" style="display: none">
                                    <label>Bank</label>
                                    <select id="bank_id" class="select2 form-control @error('bank_id') is-invalid @enderror" name="bank_id">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="text" name="note" class="form-control  @error('note') is-invalid @enderror" placeholder="Note">
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('#product_id').change(function() {
            var product_id = $(this).val();
            if (product_id == '') {
                product_id = -1;
            }
            var option = "";
            var url = "{{ url('/') }}";

            $.get(url + "/get-size/" + product_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.title + "</option>";
                });
                $('#size_id').html(option);
            });

        });

        $('#is_others_income_label').click(function() {
            if ($('#is_others_income').is(':checked')) {
                $("#price_box").show();
                $("#price").prop('required', true);
                $("#bank_id_box").show();
                $("#bank_id").prop('required', true);
            } else {
                $("#price_box").hide();
                $("#price").prop('required', false);
                $("#bank_id_box").hide();
                $("#bank_id").prop('required', false);
            }
        });
    </script>
@endsection
