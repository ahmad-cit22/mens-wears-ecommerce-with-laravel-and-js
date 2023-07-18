@php
  $business = App\Models\Setting::find(1);
@endphp
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style type="text/css">
    #invoice-POS{
  padding:2mm;
  margin: 0 auto;
  width: 50mm;
  background: #FFF;
  }

#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

/*#top{min-height: 100px;}*/
/*#mid{min-height: 80px;} 
#bot{ min-height: 50px;}*/

#top .logo{
  //float: left;
  /*height: 60px;*/
  /*width: 60px;*/
}

.text-center{
  text-align: center;
}
p,label{
  font-size: 16px;
}
  
  
}
  </style>
</head>
<body style="margin: 0px 0px">

  <div id="invoice-POS">
    
    
    
    <div id="mid">
      @for($i = 1; $i <= $qty; $i++)
      <div class="text-center">
        <p style="margin-top: 10px;line-height: 10px;"><b>GO BY FABRIFEST</b></p>
        <p style="margin-bottom: 0px;"><b>BDT {{ $stock->price }}/-</b></p>
        <label>{{ $stock->product->title }}{{ is_null($stock->size) ? '' : (' - ' . optional($stock->size)->title) }}</label><br>
        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($stock->id + 1000, 'C39') }}" alt="barcode"   /><br>
        <label>{{ $stock->id + 1000 }}</label>
        <p style="margin: 0px;">www.gobyfabrifest.com</p>
      </div>
      @endfor
    </div><!--End Invoice Mid-->
  </div><!--End Invoice-->

</body>
</html>