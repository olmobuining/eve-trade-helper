@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Buy and sell market orders</div>
                <div class="panel-body">
                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info" data-order="[[ 2, &quot;desc&quot; ]]">
                        <thead>
                        <tr>
                            <th style="width:30px;"><i class="fa fa-info-circle"></i></th>
                            <th style="width:20px;">Q</th>
                            <th>Ƶ Price</th>
                            <th>Ƶ Price in The Forge</th>
                            <th>Product</th>
                            <th>Outbid</th>
                            <th>Outbid Ƶ price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    {{ strtoupper($order->getOrderType()) }}
                                </td>
                                <td>
                                    {{ $order->volume_remain }}
                                </td>
                                <td data-order="{{$order->price}}">
                                    {{ number_format($order->price, 2, ",", ".") }}
                                </td>
                                <td data-order="{{$order->forge_price}}">
                                    {{ number_format($order->forge_price, 2, ",", ".") }}
                                </td>
                                <td>
                                    {{ $order->getInventoryName() }}
                                </td>
                                <td>
                                    @if ($order->outbid)
                                        Yes
                                    @else
                                        No
                                    @endif
                                </td>
                                <td data-order="{{$order->outbid_price}}">
                                    @if ($order->outbid)
                                        {{ number_format($order->outbid_price, 2, ",", ".") }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Transactions</div>
                <div class="panel-body">
                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info" data-order="[[ 3, &quot;desc&quot; ]]">
                        <thead>
                        <tr>
                            <th style="width:30px;"><i class="fa fa-info-circle"></i></th>
                            <th>Ƶ Price</th>
                            <th>Product</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>
                                    @if ($transaction->is_buy)
                                        BUY
                                    @else
                                        SELL
                                    @endif
                                </td>
                                <td data-order="{{$transaction->unit_price}}">
                                    {{ number_format($transaction->unit_price, 2, ",", ".") }}
                                </td>
                                <td>
                                    {{ $transaction->getInventoryName() }}
                                </td>
                                <td>
                                    {{ $transaction->date }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
