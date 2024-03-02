@php
    $business = App\Models\Setting::find(1);
@endphp
<!DOCTYPE HTML>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <title></title>
</head>

<body>
    <p>Accessory ({{ $accessory->name }}) quantity has gone below minimum level.</p>
    <a href="{{ route('accessory.index') }}" style="margin: 25px 0;">View</a>

    <p>{{ $business->name }}</p>
</body>

</html>
