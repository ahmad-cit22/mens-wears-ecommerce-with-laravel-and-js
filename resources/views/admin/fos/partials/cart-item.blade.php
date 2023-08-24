@if($cart)
<tr>
	<td>{{ $cart->name }} @if($cart->options->size_name != '' || $cart->options->size_name != NULL ) - {{ $cart->options->size_name }} @endif</td>
	<td>{{ $cart->qty }}</td>
	<td>
		<input type="number" name="price[]" value="{{ $cart->price }}" style="width: 80px;" autocomplete="off" onblur="update_cart('{{ $cart->rowId }}')" id="price_{{ $cart->rowId }}" onfocus="this.select();">
	</td>
	<td>{{ $cart->price * $cart->qty }}</td>
	<td>
		<div class="card-toolbar text-right">
		<a onclick="remove_cart('{{ $cart->rowId }}')" class="text-danger" title="Delete" style="cursor: pointer;"><i class="fas fa-times"></i></a>
		</div>
	</td>
</tr>
@endif