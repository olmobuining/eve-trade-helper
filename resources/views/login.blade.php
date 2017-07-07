@extends('layouts.base')
@section('title')
    Login
@endsection
@section('sidebar')
    @component('components.topbar')
    @endcomponent
@endsection
@section('basecontent')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        @if (Session::has('flash_message'))
                            <div class="alert-block ale alert-error alert">
                                {{ Session::get('flash_message') }}
                            </div>
                        @endif
                        <div class="text-center">
                            <a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://eth.olmobuining.nl/callback&client_id={{$client_id}}&scope=characterWalletRead characterAssetsRead characterMarketOrdersRead characterAccountRead corporationWalletRead corporationAssetsRead corporationMarketOrdersRead esi-wallet.read_character_wallet.v1 esi-assets.read_assets.v1 esi-markets.read_character_orders.v1&state={{$state}}"><img
                                        alt="EVE SSO Login"
                                        src="https://images.contentful.com/idjq7aai9ylm/4PTzeiAshqiM8osU2giO0Y/5cc4cb60bac52422da2e45db87b6819c/EVE_SSO_Login_Buttons_Large_White.png?w=270&amp;h=45"
                                        class="btn"></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
