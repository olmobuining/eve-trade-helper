@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Ƶ {{ number_format($total_sell, 0, ",", ".") }}</div>
                            <div>Total Ƶ in sell orders</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-download fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Ƶ {{ number_format($total_buy, 0, ",", ".") }}</div>
                            <div>Total Ƶ in buy orders</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Buy and sell market orders <a href="#refresh" id="refresh_order_table">Refresh data</a></div>
                <div class="panel-body">
                    <table id="orders_table" class="table table-striped table-bordered no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                            <tr>
                                <th style="width:30px;"><i class="fa fa-info-circle"></i></th>
                                <th style="width:20px;">Q</th>
                                <th style="width:100px;">Ƶ Price</th>
                                <th style="width:100px;">Ƶ Price in The Forge</th>
                                <th>Product</th>
                                <th>Outbid</th>
                                <th>Outbid Ƶ price</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Ƶ {{ number_format($profit, 0, ",", ".") }}</div>
                            <div>Total Ƶ profit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Ƶ {{ number_format(($profit/100)*8, 0, ",", ".") }}</div>
                            <div>Tax paid on profited items</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-money fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">Ƶ {{ number_format($profit-(($profit/100)*8), 0, ",", ".") }}</div>
                            <div>Profit with tax deducted</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Transactions</div>
                <div class="panel-body">
                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info" data-order="[[ 4, &quot;desc&quot; ]]">
                        <thead>
                        <tr>
                            <th style="width:30px;"><i class="fa fa-info-circle"></i></th>
                            <th style="width:20px;">Q</th>
                            <th style="width:80px;">Ƶ Price</th>
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
                                <td>
                                    {{ $transaction->quantity }}
                                </td>
                                <td data-order="{{$transaction->unit_price}}" style="text-align: right;">
                                    {{ number_format($transaction->unit_price, 2, ",", ".") }}
                                </td>
                                <td>
                                    <a href="javascript:openMarket({{ $transaction->type_id }})">{{ $transaction->getInventoryName() }}</a>
                                </td>
                                <td>
                                    {{ $transaction->getDateForTimezone()->format('Y-m-d H:i:s') }}
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
