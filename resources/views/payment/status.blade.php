@extends('layouts.app-auth');

@section('content')
<h3 class="text-3xl text-grey-500 text-center m-5">Payment Status</h3>
    <table class="table-auto shadow-lg bg-white " align="center" style="width: 60%">
        <tr >
            <td class="border px-8 py-4">PAYMENT ID</td><td class="border px-8 py-4">#{{ $detail->id }}</td>
        </tr>
        <tr>
            <td class="border px-8 py-4">PAYMENT METHOD</td><td class="border px-8 py-4">{{ $detail->method }}</td>
        </tr>
        <tr>
            <td class="border px-8 py-4">AMOUNT</td><td class="border px-8 py-4">${{ $detail->amount }} </td>
        </tr><tr>
            <td class="border px-8 py-4">STATUS</td><td class="border px-8 py-4"><span class="p-2 rounded @if($detail->status == 'paid')bg-green-500 text-white @else bg-red-500 text-white @endif">{{$detail->status }}</span></td>
        </tr>
        <tr>
            <td colspan="2" class="border px-8 py-4">
              @if($detail->method == 'coinpayments')

                <form action="https://www.coinpayments.net/index.php" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_pay">
	<input type="hidden" name="reset" value="1">
	<input type="hidden" name="merchant" value="@php echo env('cp_merchant_id')@endphp">
	<input type="hidden" name="currency" value="USD">
	<input type="hidden" name="amountf" value="{{ $detail->amount }}">
	<input type="hidden" name="email" value="@php echo DB::table('users')->where('id',session()->get('user_id'))->first()->email @endphp">
	<input type="hidden" name="first_name" value="@php echo DB::table('users')->where('id',session()->get('user_id'))->first()->username @endphp">
	<input type="hidden" name="allow_currencies" value="BTC,USDT.BEP2,USDT.ERC20,ETH,DOGE">
	<input type="hidden" name="item_name" value="TopUp Balance RyuJin App #{{ $detail->id }}">
	<input type="hidden" name="item_number" value="{{ $detail->id }}">
	<input type="hidden" name="want_shipping" value="0">	
	<input type="hidden" name="success_url" value="{{ url('/payment/status/'.$detail->id) }}">
	<input type="hidden" name="ipn_url" value="{{ url('/payment/cp_ipn') }}"><center>
	<input type="submit" value="PAY NOW" class="bg-blue-500 p-3 rounded text-white" style="width: 100%">
	</center>
</form>

@elseif($detail->method == 'perfectmoney')
<form target="_blank" action="https://perfectmoney.is/api/step1.asp" method="POST">
    <input type="hidden" name="token" value="{{ $detail->token }}">


<input type="hidden" name="BAGGAGE_FIELDS" value="token">
<input type="hidden" name="PAYEE_ACCOUNT" value="@php echo env('PAYEE_ACCOUNT') @endphp">
<input type="hidden" name="PAYEE_NAME" value="RyuJin App">
<input type="hidden" name="PAYMENT_AMOUNT" value="{{ $detail->amount }}">
<input type="hidden" name="PAYMENT_UNITS" value="USD">
<input type="hidden" name="PAYMENT_URL" value="{{ url('/payment/perfectmoney') }}">
<input type="hidden" name="NOPAYMENT_URL" value="{{ url('/payment/status/'.$detail->id)}}">
<input type="hidden" name="SUGGESTED_MEMO" value="TopUp Balance RyuJin App #{{ $detail->id }}">
<input type="hidden" name="PAYMENT_ID" value="{{ $detail->id }}">
<input type="submit" value="PAY NOW" class="bg-blue-500 p-3 rounded text-white" style="width: 100%">
</form>
@endif
            </td>
        </tr>
    </table>
@endsection
