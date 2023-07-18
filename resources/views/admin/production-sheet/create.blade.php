@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Production Sheet</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">production-sheet</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<section class="content">
	<div class="container-fluid">
		<div class="card">
              <div class="card-header">
                
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                @can('product.edit')
                <form action="{{ route('production.store') }}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Product Code*</label>
                        <input type="text" class="form-control  @error('product_code') is-invalid @enderror" name="product_code" placeholder="Product Code">
                        @error('product_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Category*</label>
                        <select class="select2 form-control  @error('category_id') is-invalid @enderror" name="category_id" id="category_id" required>
                          <option value="">---- Select ----</option>
                          @foreach($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->title }}</option>
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
                        <label>Date*</label>
                        <input type="date" name="production_date" class="form-control  @error('production_date') is-invalid @enderror" required>
                        @error('production_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>

                    

                    <div class="col-md-12">
                      <h6>FABRIC</h6>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('fabric_supplier_id') is-invalid @enderror" name="fabric_supplier_id">
                              <option value="">---- Select ----</option>
                              @foreach($suppliers as $supplier)
                              <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                              @endforeach
                            </select>
                            @error('fabric_supplier_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Qty</label>
                            <input type="text" name="fabric_qty" class="form-control  @error('fabric_qty') is-invalid @enderror">
                            @error('fabric_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="fabric_amount" class="form-control  @error('fabric_amount') is-invalid @enderror">
                            @error('fabric_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <h5>CONTRAST FABRIC</h5>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('contrast_supplier_id') is-invalid @enderror" name="contrast_supplier_id">
                              <option value="">---- Select ----</option>
                              @foreach($suppliers as $supplier)
                              <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                              @endforeach
                            </select>
                            @error('contrast_supplier_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Qty</label>
                            <input type="text" name="contrast_qty" class="form-control  @error('contrast_qty') is-invalid @enderror">
                            @error('contrast_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="contrast_amount" class="form-control  @error('contrast_amount') is-invalid @enderror">
                            @error('contrast_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <h5>SWING CHARGE</h5>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('swing_supplier_id') is-invalid @enderror" name="swing_supplier_id">
                              <option value="">---- Select ----</option>
                              @foreach($suppliers as $supplier)
                              <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                              @endforeach
                            </select>
                            @error('swing_supplier_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Qty</label>
                            <input type="text" name="swing_qty" class="form-control  @error('swing_qty') is-invalid @enderror">
                            @error('swing_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="swing_amount" class="form-control  @error('swing_amount') is-invalid @enderror">
                            @error('swing_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <h5>PRINTING CHARGE</h5>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Supplier</label>
                            <select class="select2 form-control  @error('printing_supplier_id') is-invalid @enderror" name="printing_supplier_id">
                              <option value="">---- Select ----</option>
                              @foreach($suppliers as $supplier)
                              <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                              @endforeach
                            </select>
                            @error('printing_supplier_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Qty</label>
                            <input type="text" name="printing_qty" class="form-control  @error('printing_qty') is-invalid @enderror">
                            @error('printing_qty')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="printing_amount" class="form-control  @error('printing_amount') is-invalid @enderror">
                            @error('printing_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 border-right">
                      <h5>Accessories</h5>
                      <div class="row" align="right">
                        <div class="col-md-12 mb-2">
                          <a class="btn btn-primary extra-fields-accessory"><i class="fas fa-plus"></i></a>
                        </div>
                      </div>
                      <div class="accessory_records">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Item</label>
                              <select class="form-control" name="accessory_id[]">
                                @foreach($accessories as $accessory)
                                <option value="{{ $accessory->id }}">{{ $accessory->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Amount</label>
                              <div class="input-group mb-3">
                                <input type="number" class="form-control @error('accessory_amount') is-invalid @enderror" name="accessory_amount[]" placeholder="Amount">
                                  <div class="input-group-append">
                                    <!-- <button class="btn btn-outline-danger" type="button"><i class="fas fa-minus"></i></button> -->
                                  </div>
                                  @error('accessory_amount')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="accessory_records_dynamic"></div>
                    </div>

                    <div class="col-md-6">
                      <h5>Other Costs</h5>
                      <div class="row" align="right">
                        <div class="col-md-12 mb-2">
                          <a class="btn btn-primary extra-fields-cost"><i class="fas fa-plus"></i></a>
                        </div>
                      </div>
                      <div class="cost_records">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Item</label>
                              <input type="text" class="form-control" name="cost_name[]" placeholder="Name">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Amount</label>
                              <div class="input-group mb-3">
                                <input type="number" class="form-control @error('cost_amount') is-invalid @enderror" name="cost_amount[]" placeholder="Amount">
                                  <div class="input-group-append">
                                    <!-- <button class="btn btn-outline-danger" type="button"><i class="fas fa-minus"></i></button> -->
                                  </div>
                                  @error('cost_amount')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="cost_records_dynamic"></div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Output Units</label>
                        <input type="number" name="output_units" class="form-control" placeholder="Output Units" required>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </div>

                  </div>
                </form>
                @endcan
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            
	</div>
</section>
@endsection

@section('scripts')
  <script>
    $('#product_id').change(function(){
        var product_id = $(this).val();
        if (product_id == ''){
            product_id = -1;
        }
        var option = "";
        var url = "{{ url('/') }}";

        $.get( url + "/get-size/"+product_id, function( data ) {
            data = JSON.parse(data);
            data.forEach(function (element) {
                option += "<option value='"+ element.id +"'>"+ element.title + "</option>";
            });
            $('#size_id').html(option);
        });

    });
  </script>

  <script>
  $('.extra-fields-accessory').click(function() {
    $('.accessory_records').clone().appendTo('.accessory_records_dynamic');
    $('.accessory_records_dynamic .accessory_records').addClass('single remove');
    $('.single .extra-fields-accessory').remove();
    $('.single .row .input-group-append').append('<a href="#" class="remove-field btn-remove-accessory btn btn-danger"><i class="fas fa-minus"></i></a>');
    $('.accessory_records_dynamic > .single').attr("class", "remove");

    $('.accessory_records_dynamic input').each(function() {
      var count = 0;
      var fieldname = $(this).attr("name");
      $(this).attr('name', fieldname + count);
      count++;
    });

  });

  $(document).on('click', '.remove-field', function(e) {
    $(this).parent('.input-group-append').parent('.input-group').parent('.form-group').parent('.col-md-6').parent('.row').parent('.remove').remove();
    e.preventDefault();
  });
</script>
<script>
  $('.extra-fields-cost').click(function() {
    $('.cost_records').clone().appendTo('.cost_records_dynamic');
    $('.cost_records_dynamic .cost_records').addClass('single remove');
    $('.single .extra-fields-cost').remove();
    $('.single .row .input-group-append').append('<a href="#" class="remove-field btn-remove-cost btn btn-danger"><i class="fas fa-minus"></i></a>');
    $('.cost_records_dynamic > .single').attr("class", "remove");

    $('.cost_records_dynamic input').each(function() {
      var count = 0;
      var fieldname = $(this).attr("name");
      $(this).attr('name', fieldname + count);
      count++;
    });

  });

  $(document).on('click', '.remove-field', function(e) {
    $(this).parent('.input-group-append').parent('.input-group').parent('.form-group').parent('.col-md-6').parent('.row').parent('.remove').remove();
    e.preventDefault();
  });
</script>
@endsection