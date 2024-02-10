<div class="row justify-content-center">
    <div class="col-12 text-center mb-2">
        @if ($status != 'not_found')
            <h4 class="mb-3">Scanned Product Details</h4>
            @if ($status == 'success')
                <div class="productThumb my-2">
                    <img class="img-fluid" src="{{ asset('images/product/pos_images/' . $scanned_product->product->image) }}" alt="{{ $scanned_product->product->title }}">
                </div>

                <div class="productContent">
                    {{ $scanned_product->product->title }}{{ is_null($scanned_product->size) ? '' : ' - ' . optional($scanned_product->size)->title }}
                </div>

                <h5 class="mt-2 mb-4 text-success"><i class="fas fa-check-circle text-success mr-1"></i> Product Matched Successfully!</h5>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="{{ route('order.barcode.check.confirm', $order_product_id) }}" class="btn btn-success">Confirm Checking</a>
            @else
                <div class="productThumb my-2">
                    <img class="img-fluid" src="{{ asset('images/product/pos_images/' . $scanned_product->product->image) }}" alt="{{ $scanned_product->product->title }}">
                </div>

                <div class="productContent">
                    {{ $scanned_product->product->title }}{{ is_null($scanned_product->size) ? '' : ' - ' . optional($scanned_product->size)->title }}
                </div>

                <h5 class="mt-2 mb-4 text-danger"><i class="fas fa-times-circle text-danger mr-1"></i> Sorry! Product Not Matched!</h5>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            @endif
        @else
            <h5 class="text-danger mb-3"><i class="fas fa-exclamation-triangle text-danger mr-1"></i> Scanning Error: Product Not Found!</h5>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        @endif

    </div>
</div>
