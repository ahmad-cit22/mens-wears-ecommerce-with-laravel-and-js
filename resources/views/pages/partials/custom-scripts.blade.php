@include('sweetalert::alert')
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script type="text/javascript">
  function view_product_details(product_id) {

    url = "{{ route('api.product.details') }}";
    var product_id = product_id;
    $.ajax({
        url: url,
        type: "POST",
        data:{
            product_id:product_id,_token: '{{csrf_token()}}',
        },
        beforeSend: function() {
          $('.view_product_details').click();
          $('#product_details_output').html('<div class="p-10 text-center"><p>Loading....</p></div>');
        },
        success:function(response){
          $('#product_details_output').html(response.product_details);

        }
    });
  }
  function addToCart(product_id, type) {
    var qty = $('#qty').val();
    var size_id = '';
    if (type == 'variation') {
        // var size_id = $('#size_id').val();
        var object = document.querySelector('input[name="size_id"]:checked');
        if (object != null) {
          var size_id = document.querySelector('input[name="size_id"]:checked').value;
        }
    }

    // alert(product_id + '--' + qty + '--' + size_id + '--' + type);

    if (size_id == '' && type == 'variation') {
      toastr.options = {
            "positionClass": "toast-top-right"
          }
            toastr.warning('Please Select a Size');
    }
    else {
      url = "{{ route('cart.add') }}";
      var product_id = product_id;
      $.ajax({
          url: url,
          type: "POST",
          data:{
              product_id:product_id,size_id:size_id,type:type,qty:qty,_token: '{{csrf_token()}}',
          },
          success:function(response){
            $('#total_count').html(response.total_count);
            $('#mobile_total_count').html(response.total_count);
            $('#cart_sidebar_total').html(response.total_amount);
            $('#cart_sidebar').html(response.cart_sidebar);
            $('.added_to_cart_' + product_id).addClass('added_to_cart');
            $('.added_to_cart_' + product_id).text('Added To Cart');

            toastr.options = {
              "positionClass": "toast-top-right"
            }
              toastr.success('Product Added into Cart');
          }
      });
    }
  }

  function addToWishlist(product_id) {
    //alert(product_id);
    url = "{{ route('wishlist.add') }}";
    var product_id = product_id;
    $.ajax({
        url: url,
        type: "POST",
        data:{
            product_id:product_id,_token: '{{csrf_token()}}',
        },
        success:function(response){


          toastr.options = {
            "positionClass": "toast-top-right"
          }
          if (response.auth == 1) {
            if(response.status == 0){
              toastr.error('Something went wrong!');
            }
            if (response.status == 1) {
              toastr.success('Product Added into Wishlist!');
            }
            if(response.status == 2){
              toastr.warning('Product already in  your wishlist!');
            }
          }
          else{
            toastr.warning('You are not logged in!');

          }
        }
    });
  }
</script>
<script>
  $('#payment_option').change(function () {
      $payment_option = $('#payment_option').val();
      if($payment_option == 'Cash on Delivery' ) {
          $('#cod').removeClass('hidden');
          $('#bkash').addClass('hidden');
          $('#rocket').addClass('hidden');
      }
      if($payment_option == 'Bkash' ) {
          $('#cod').addClass('hidden');
          $('#bkash').removeClass('hidden');
          $('#transaction_id').removeClass('hidden');
          $('#rocket').addClass('hidden');
      }
      if($payment_option == 'Rocket' ) {
          $('#cod').addClass('hidden');
          $('#bkash').addClass('hidden');
          $('#rocket').removeClass('hidden');
          $('#transaction_id').removeClass('hidden');
      }

  })
</script>

@if (session('error'))

    <script>
        toastr.options = {
            "positionClass": "toast-top-right"
        }
        toastr.error('{{ session('error') }}');
    </script>

@endif
