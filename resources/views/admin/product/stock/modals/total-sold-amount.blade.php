<div class="container-fluid">
    <div class="card p-4">
        @php
            $sells_cost_amount = 0;
        @endphp
        <div class="row">
            @foreach ($orders as $item)
                @if ($item->order_status_id != 5 && $item->is_return != 1)
                    @foreach ($item->order_product as $order_product)
                        @php
                            $sells_cost_amount += $order_product->product->variation->production_cost * $order_product->qty;
                        @endphp
                    @endforeach
                @endif
            @endforeach
            <div class="col-12 mb-2 ">
                <h5>Cost of Sold Products: <b class="ml-1 text-info">{{ round($sells_cost_amount) }} TK</b></h5>
            </div>
        </div>
    </div>
</div>
