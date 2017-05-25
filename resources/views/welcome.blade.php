<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CUSTOMER-TOOL</title>
    <!-- Custom Main StyleSheet CSS -->
    <link href="<?php echo URL::to('/');?>/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="dx-index">
        <div class="box">
            <div class="wap-logo"><img class="dx-logo" src="images/logo.png"></div>
        </div>
        <div class="dx-title">
            <p>Marketing<p>
            <span>Budget Management 2017</span>
            <div class="box">
                @if (Route::has('login'))
                    @if (Auth::check())
                    <a href="{{ url('/list_job') }}" class="button button--nuka button--style button--inverted">Home</a>
                    @else
                        <a href="{{ url('/login') }}" class="button button--nuka button--style button--inverted">login</a>
                        <!--<a href="{{ url('/register') }}" class="button button--nuka button--style button--inverted">register</a>-->
                    @endif
                    
                @endif
            </div>
            
        </div>
        <p class="dx-diachi">Trụ sở: Tầng 18 tòa Center Building, số 1 Nguyễn Huy Tưởng, Thanh Xuân, Hà Nội</p>
    </div>
    <canvas id="c"></canvas>
<script src="<?php echo URL::to('/');?>/js/anime.min.js"></script>
<script src="<?php echo URL::to('/');?>/js/custom.js"></script>
</body>
</html>