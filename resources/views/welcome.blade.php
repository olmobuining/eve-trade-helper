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
                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending" style="width:30px;"><i class="fa fa-info-circle"></i></th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending" style="width:20px;">Q</th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Ƶ Price</th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Product</th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Outbid</th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Outbid Ƶ price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    @if ($order->is_buy_order)
                                        BUY
                                    @else
                                        SELL
                                    @endif
                                </td>
                                <td>
                                    {{ $order->volume_remain }}
                                </td>
                                <td data-order="{{$order->price}}">
                                    {{ number_format($order->price, 2, ",", ".") }}
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
                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending" style="width:30px;"><i class="fa fa-info-circle"></i></th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Ƶ Price</th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Product</th>
                            <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Date</th>
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
