@extends('layouts.frontend.layout')
@push('scripts')
    <script type="text/javascript" src="/frontend/js/jquery.min.js"></script>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="/frontend/js/js.cookie.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.js"></script>


    <script type="text/javascript" src="/js/blowup.min.js"></script>

    <script>
        $(function(){
            setTimeout(function (){

                $('.mansory-grid').masonry({
                    itemSelector: '.mansory-grid-item',
                    columnWidth: 530
                });

            }, 1000)

            $(".blowup").each(function () {
                $(this).blowup({
                    "scale": 2,
                    border: "6px solid #0ADF88"
                });
            })

        })

        function component() {

            window.addToCartModal = new bootstrap.Modal(document.getElementById('addToCartModal'), {
                backdrop: 'static',
                keyboard: false
            })

            return {
                addToCartResult: "Adding...",
                addToCartStatus: false,
                addToCart(event_id, price_id, bib_number) {
                    axios.post('/api/cart', {
                        event_id: event_id,
                        price_id: price_id,
                        bib_number: bib_number,
                    })
                        .then((response) => {
                            window.addToCartModal.show()
                            this.$store.cart_count = response.data.count
                            // alert(response.data.message)
                        })
                }
            }
        }
    </script>
@endpush
@section('content')
<div x-data="component()">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div id="eventPassword">
                    <img src="/frontend/img/eventpw-bg.jpg" class="d-block w-100" alt="" />
                    <div class="eventDesc">
{{--                        <img src="/frontend/img/nice-work.png" alt="" />--}}
                        <div>
                            <strong>{{$event->title}}</strong><br>
                            {{$event->country}} @if($event->country !== "" && $event->city !== ""),@endif {{$event->city}}<br>
                            {{$event->event_date->format("d/m/Y")}}
                            <p class="mt-4 pb-4">{{$event->note}}</p>
                        </div>
                    </div>
                    <div class="personNumber mt-5">{{$bib_number}}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="personEvents">
        <div class="title">GET YOUR PHOTOS</div>
        <div class="row" id="priceList">
            @foreach($event->price_list as $price)
                <div class="col-md-4">
                    <a href="#" @click.prevent="addToCart({{$event->id}}, {{$price->price_id}}, {{$bib_number}})" class="priceListBtn">
                        <span class="type">{{$price->price->title}}</span>
                        <span class="price">£{{$price->price->price}}</span>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mansory-grid">
            @foreach($bib_number_obj as $bnobj)
                    <div class="mansory-grid-item">
                        <a href="{{$bnobj->thumbnail}}" target="_blank"><img class="blowup" src="{{$bnobj->thumbnail}}" alt="{{$event->title}}" width="530"/></a>
                    </div>
            @endforeach
        </div>
    </div>

    <div class="modal" tabindex="-1" id="addToCartModal">
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-body text-center p-5 ">
                    <p class="font-bold pb-4"><img src="{{$favicon}}" width="60"></p>
                    <p class="font-bold pb-4" >Items added to your cart</p>
                    <div class="w-full d-flex" >
                        <a href="#" @click.prevent="window.addToCartModal.hide()">Continue Shopping</a>
                        <a href="/cart" class="bg-pgreen-700">Go to cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
