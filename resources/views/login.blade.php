<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EVE Trade Helper - Login</title>

    <!-- Bootstrap -->
    <script type="text/javascript">
        //<![CDATA[
        try{if (!window.CloudFlare) {var CloudFlare=[{verbose:0,p:0,byc:0,owlid:"cf",bag2:1,mirage2:0,oracle:0,paths:{cloudflare:"/cdn-cgi/nexp/dok3v=1613a3a185/"},atok:"f98dd88c8b3454e7d27d0649b543f99e",petok:"fb6a89e912b2ef86e46080f7991d944c5960bfd9-1497909481-1800",zone:"colorlib.com",rocket:"a",apps:{}}];document.write('<script type="text/javascript" src="//ajax.cloudflare.com/cdn-cgi/nexp/dok3v=85b614c0f6/cloudflare.min.js"><'+'\/script>');}}catch(e){};
        //]]>
    </script>
    <link href="/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form>
                    <h1>EVE Trade Helper</h1>
                    <div>

                        <a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://eve-trade-helper.dev/callback&client_id={{$client_id}}&scope=characterWalletRead characterAssetsRead characterMarketOrdersRead characterAccountRead corporationWalletRead corporationAssetsRead corporationMarketOrdersRead esi-wallet.read_character_wallet.v1 esi-assets.read_assets.v1 esi-markets.read_character_orders.v1&state={{$state}}"><img alt="EVE SSO Login" src="https://images.contentful.com/idjq7aai9ylm/4PTzeiAshqiM8osU2giO0Y/5cc4cb60bac52422da2e45db87b6819c/EVE_SSO_Login_Buttons_Large_White.png?w=270&amp;h=45"></a>
                        {{--<input type="text" class="form-control" placeholder="Username" required="" />--}}
                    </div>
                    <div>
                        {{--<input type="password" class="form-control" placeholder="Password" required="" />--}}
                    </div>
                    <div>
                        {{--<a class="btn btn-default submit" href="index.html">Log in</a>--}}
                        {{--<a class="reset_pass" href="#">Lost your password?</a>--}}
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>
</html>
