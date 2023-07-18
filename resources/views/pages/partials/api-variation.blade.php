@if(!is_null($variation))
<label for="size_id_{{ optional($variation->size)->id }}" style="font-size: 20px;"><input type="radio" name="size_id" class="size_id" value="{{ optional($variation->size)->id }}" style="height: 20px;width: 20px;" id="size_id_{{ optional($variation->size)->id }}" {{ $variation->qty < 1 ? 'disabled' : '' }}> {{ optional($variation->size)->title }}</label>
@endif