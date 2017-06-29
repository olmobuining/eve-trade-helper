@extends('layouts.master')

@section('content')
    @foreach($orders as $order)
        @if($order->is_buy_order)
            buy for
        @else
            sell for
        @endif
        {{number_format($order->price,0,",",".")}} -- type_id:{{$order->type_id}} -- volume_remain:{{$order->volume_remain}}<br>
    @endforeach
@endsection
