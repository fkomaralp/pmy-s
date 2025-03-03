{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
{{--    <title>{{$event_title}}</title>--}}
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"/>--}}
{{--    <style type="text/css">--}}
{{--        @font-face {--}}
{{--            font-family: 'nowaybold';--}}
{{--            src: url('{{asset('/fonts/noway-bold-webfont.eot')}}');--}}
{{--            src: url('{{asset('/fonts/noway-bold-webfont.eot?#iefix')}}') format('embedded-opentype'),--}}
{{--            url('{{asset('/fonts/noway-bold-webfont.woff2')}}') format('woff2'),--}}
{{--            url('{{asset('/fonts/noway-bold-webfont.woff')}}') format('woff'),--}}
{{--            url('{{asset('/fonts/noway-bold-webfont.ttf')}}') format('truetype'),--}}
{{--            url('{{asset('/fonts/noway-bold-webfont.svg#nowaybold')}}') format('svg');--}}
{{--            font-weight: normal;--}}
{{--            font-style: normal;--}}
{{--        }--}}
{{--        body {--}}
{{--            display: block !important;--}}
{{--            margin: 10px auto !important;--}}
{{--            background-color: #fff;--}}
{{--            width: 600px;--}}
{{--            height: 100%;--}}
{{--            border: 1px solid #1e1e32;--}}
{{--            border-top: 5px solid #1e1e32;--}}
{{--        }--}}
{{--        .well_done, .username {--}}
{{--            font-family: 'nowaybold';--}}
{{--            font-size: 45px;--}}
{{--            color: #1d1e31;--}}
{{--        }--}}
{{--        .download_btn {--}}
{{--            font-family: 'nowaybold';--}}
{{--            font-size: 25px;--}}
{{--            color: #1d1e31;--}}
{{--        }--}}
{{--        .username {--}}
{{--            color:#0AFA96;--}}
{{--        }--}}
{{--        .download_btn {--}}
{{--            width: 300px;--}}
{{--            height: 50px;--}}
{{--            background-color: #0AFA96;--}}
{{--            text-align: center;--}}

{{--            transform: skewX(-15deg);--}}
{{--            line-height: normal;--}}
{{--        }--}}
{{--        .download_link_doest_work {--}}
{{--            font-style: italic;--}}
{{--            font-size: 14px;--}}
{{--        }--}}
{{--        .details_title {--}}
{{--            font-style: italic;--}}
{{--            font-size: 18px;--}}
{{--            font-family: 'nowaybold';--}}
{{--        }--}}
{{--        .footer {--}}
{{--            background-color: #1d1e31;--}}
{{--            color: white;--}}
{{--            height: 150px;--}}
{{--            padding: 10px;--}}
{{--            font-size: 12px;--}}
{{--        }--}}
{{--    </style>--}}

{{--</head>--}}
{{--<body class="">--}}
{{--<div class="p-3">--}}
{{--<div class="w-full "><img src="{{asset('/img/mail_logo.png')}}" width="200"></div>--}}

{{--<div class="w-full mt-5 well_done">WELL DONE</div>--}}
{{--<div class="w-full username">{{$order->user->name}}</div>--}}

{{--<div class="w-full mt-4">Time to celebrate your achievement at {{$event_title}} and share your moments with your friends.</div>--}}
{{--<div class="w-full mt-4">We'd appreciate if you use the official event hashtag #{{$event_code}}</div>--}}

{{--<div class="w-full mt-5 mb-5">--}}
{{--    <a class="text-decoration-none text-black" href="{{$url}}"><div class="download_btn mx-auto pt-1">DOWNLOAD</div></a>--}}
{{--    <div class="w-full mx-auto p-2 mt-2 text-center download_link_doest_work">Copy/Paste this link to your browser if the link above doesn't work.--}}
{{--        {{$url}}--}}
{{--    </div>--}}
{{--</div>--}}
{{--<hr style="height:2px; width:100%; border-width:0; color:black; background-color:black; margin-top:5px">--}}
{{--<div class="block">--}}
{{--    <div class="mt-5 d-flex flex-row ">--}}

{{--        <div class="text-nowrap details_title">--}}
{{--            ORDER DETAILS--}}
{{--        </div>--}}
{{--        <div class="px-5">--}}
{{--            {{$event_title}} of No. {{$order->bib_number}}<br>--}}
{{--                    Order number: {{$order->order_number}}<br>--}}
{{--                    Order date: {{$order->created_at}}<br>--}}
{{--                    Customer details: {{$order->user->name}}<br>--}}
{{--                    Paid: £ {{($order->orderPrice) ? $order->orderPrice->price : 0}}<br>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="w-full block mt-4 footer text-center">--}}
{{--    <img src="{{asset('/img/mail_favicon.png')}}" width="60" class="my-3"><br>--}}
{{--    PicMyRun is a sports content delivery service by Sporlab Ltd.<br>--}}
{{--    {{$mail}}--}}
{{--</div>--}}


{{--</body>--}}
{{--</html>--}}

Well Done {{$order->user->name}}<br><br>

Time to celebrate your achievement and share your moments with your friends.<br><br>

Download your photos here:<br><br>

<a href="{{$url}}">DOWNLOAD</a><br><br>

Order Details:<br><br>
{{$order->user->name}}<br><br>
{{$event_title}}<br><br>
{{$order->bib_number}}<br><br>
{{$order->order_number}}<br><br>
{{$order->created_at}}<br><br>
{{($order->orderPrice) ? $order->orderPrice->price : 0}}<br><br>

PicMyRun is a sports content delivery service by Sporlab Ltd.<br>
{{$mail}}<br>
Thanks<br>
Team PICMYRUN<br>
