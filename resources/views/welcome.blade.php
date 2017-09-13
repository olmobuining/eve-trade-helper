@extends('layouts.master')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('Buy and sell market orders') }}</div>
                <div class="panel-body">
                    <table id="orders_table" class="table table-striped table-bordered no-footer" role="grid" aria-describedby="datatable_info">
                        <thead>
                        <tr>
                            <th style="width:30px;"><i class="fa fa-info-circle"></i></th>
                            <th style="width:20px;">Q</th>
                            <th>{{ __('Ƶ Price') }}</th>
                            <th>{{ __('Ƶ Price in The Forge') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Outbid') }}</th>
                            <th>{{ __('Outbid Ƶ price') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ __('Transactions') }}</div>
                <div class="panel-body">
                    <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info" data-order="[[ 3, &quot;desc&quot; ]]">
                        <thead>
                        <tr>
                            <th style="width:30px;"><i class="fa fa-info-circle"></i></th>
                            <th>{{ __('Ƶ Price') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>
                                    @if ($transaction->is_buy)
                                        {{ __('BUY') }}
                                    @else
                                        {{ __('SELL') }}
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
