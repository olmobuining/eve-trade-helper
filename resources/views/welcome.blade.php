@extends('layouts.master')



@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Current market orders<small>Buy and sell</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="col-sm-12">

                            <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                <thead>
                                <tr>
                                    <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">B/S</th>
                                    <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Price</th>
                                    <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Product</th>
                                    <th class="sorting" aria-controls="datatable" aria-label="Position: activate to sort column ascending">Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            @if($order->is_buy_order)
                                                BUY
                                            @else
                                                SELL
                                            @endif
                                        </td>
                                        <td>
                                            {{number_format($order->price,0,",",".")}}
                                        </td>
                                        <td>
                                            {{$order->getInventoryName()}}
                                        </td>
                                        <td>
                                            {{$order->volume_remain}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
