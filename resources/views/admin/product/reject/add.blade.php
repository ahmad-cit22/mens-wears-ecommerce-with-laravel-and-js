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
                        <li class="breadcrumb-item active">Add to Reject Products</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <h4 class="my-2 ml-3">Add Reject Product</h4>
                <div class="card-header">
                    <form class="mt-2" action="{{ route('reject.store') }}" method="POST">
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
                                        <option value="">---- Select ----</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }} - ({{ $product->is_active == 1 ? 'Active' : 'Inactive' }})</option>
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
                                        <option value="">---- Select Size ----</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Qty*</label>
                                    <input type="number" name="qty" id="qty" class="form-control  @error('qty') is-invalid @enderror" required>
                                    @error('qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="mt-4" id="is_transfer_label"><input id="is_transfer" type="checkbox" name="is_transfer" class=" @error('is_transfer') is-invalid @enderror" value="1">&nbsp;&nbsp;Transfer to main stock</label>
                                    @error('is_transfer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="text" name="note" class="form-control  @error('note') is-invalid @enderror">
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
        // $('#product_id').change(function() {
        //     var product_id = $(this).val();
        //     if (product_id == '') {
        //         product_id = -1;
        //     }
        //     var option = "";
        //     var url = "{{ url('/') }}";

        //     $.get(url + "/get-size/" + product_id, function(data) {
        //         data = JSON.parse(data);
        //         data.forEach(function(element) {
        //             option += "<option value='" + element.id + "'>" + element.title + "</option>";
        //         });
        //         $('#size_id').html(option);
        //     });

        // });

        $('#is_transfer_label').click(function() {
            if ($('#is_transfer').is(':checked')) {
                $("#bank_id_box").show();
                $("#bank_id").prop('required', true);
            } else {
                $("#bank_id_box").hide();
                $("#bank_id").prop('required', false);
            }
        });
    </script>
@endsection
