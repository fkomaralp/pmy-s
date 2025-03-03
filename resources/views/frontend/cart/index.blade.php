@extends('layouts.frontend.layout')

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
{{--    <script src="https://js.stripe.com/v3/"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.2/masonry.pkgd.js"></script>

    <script>
        $(function(){
            setTimeout(function (){
                $('.mansory-grid').masonry({
                    itemSelector: '.mansory-grid-item',
                    columnWidth: 350
                });
            }, 100)
        })
        {{--const stripe = Stripe('{{env("STRIPE_KEY")}}');--}}

        {{--const elements = stripe.elements();--}}
        {{--const cardElement = elements.create('card');--}}

        {{--cardElement.mount('#card-element');--}}

        {{--const cardHolderName = document.getElementById('card-holder-name');--}}
        {{--const cardButton = document.getElementById('pay-now-button');--}}
        {{--const clientSecret = "{{ isset($intent) ? $intent->client_secret : "" }}";--}}

        {{--cardButton.addEventListener('click', async (e) => {--}}
        {{--    e.preventDefault()--}}

        {{--    const { paymentMethod, error } = await stripe.confirmCardSetup(--}}
        {{--        clientSecret, {--}}
        {{--            payment_method: {--}}
        {{--                card: cardElement,--}}
        {{--                billing_details: { name: cardHolderName.value }--}}
        {{--            }--}}
        {{--        }--}}
        {{--    );--}}

        {{--    if (error) {--}}
        {{--        $('.payment-error').text(error.message)--}}
        {{--    } else {--}}
        {{--        $('#stripe .spinner').removeClass("hidden")--}}

        {{--        document.getElementById('pmethod').value = paymentMethod.id--}}

        {{--        document.getElementById('stripe-payment-form').submit()--}}
        {{--    }--}}
        {{--});--}}

        function component() {

            window.well_done = new bootstrap.Modal(document.getElementById('well_done'), {
                backdrop: 'static',
                keyboard: false
            })

            // window.stripe = new bootstrap.Modal(document.getElementById('stripe'), {
            //     keyboard: false
            // })

            return {
                name: "",
                address: "",
                postcode: "",
                email: "",
                order_number: "{{$order_number}}",
                cart: {!! $cart !!},
                date_options : { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'UTC' },
                total: ({{$total_price}}).toFixed(2),
                sendImages() {
                    axios.post('{{route("api.cart.send_images")}}', {
                        name : this.name,
                        address : this.address,
                        postcode : this.postcode,
                        email : this.email,
                        order_number: "{{$order_number}}"
                    }).then((response) => {
                        if(!response.data.status && response.data.reload){
                            alert(response.data.message)
                            window.location.reload()
                        } else if(!response.data.status){
                            alert(response.data.message)
                        } else {
                            window.well_done.show()
                        }
                    }).catch((err) => {

                        var messages = ""

                        for(const [key, message] of Object.entries(err.response.data.errors)){
                            messages += message + "\n"
                        }

                        alert(messages)
                    })
                },
                removeFromCart(e, id, event_id) {
                    e.preventDefault()

                    let total = 0

                    axios.delete('/api/cart/'+id, {
                    }).then((response) => {
                        // var count = 0;
                        //
                        // this.cart[event_id] = this.cart[event_id].filter(x => x.id !== id)
                        //
                        // if(this.cart[event_id].length === 0){
                        //     delete this.cart[event_id]
                        // }
                        //
                        // for(const [key, value] of Object.entries(this.cart)){
                        //     count += this.cart[key].length
                        // }
                        //
                        // for(const [key, value] of Object.entries(this.cart)){
                        //     this.cart[key].forEach(function(item){
                        //         total += parseFloat(item.event_price.price.price)
                        //     })
                        // }
                        // this.total = total.toFixed(2)

                        // Alpine.store('cart_count', count);
                        // alert(response.data.message)
                        window.location.reload()

                    }).catch((err) => {
                        console.log(err)

                        var messages = ""

                        for(const [key, message] of Object.entries(err.response.data.errors)){
                            messages += message + "\n"
                        }

                        alert(messages)
                    })
                },
                // stripePayment() {
                //     window.stripe.show()
                // },
                paypalPayment() {
                    $($('.customer-form input').get().reverse()).each(function() {
                        if(!this.checkValidity()) {
                            this.reportValidity()
                        }
                    });

                    axios.post('{{route("api.user.check")}}', {
                        name : this.name,
                        address : this.address,
                        postcode : this.postcode,
                        email : this.email,
                    }).then((response) => {

                        if(response.data.status){
                            window.location.href = "{{route('frontend.payment.process.paypal.payment')}}"
                        }

                    }).catch((err) => {

                    })

                }
            }
        }
    </script>

@endpush

@section('content')
    <div x-data="component()">
        <div class="container" id="shoppingCart" x-show="Object.keys(cart).length > 0">
            <div class="title cart-title p-5">YOUR SHOPPING</div>
            <div class="text-danger text-center">
                {{session("status")}}
            </div>
            <div class="row" id="billingDetails">

                <div class="cart-list">
                    <div class="cart-item-container container p-0">
                        <template x-for="item in cart">
                            <template x-if="item.length > 0">
                                <div class="row mt-5">
                                    <div class="cart-item-container-title " x-html="item[0].event.title + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + new Date(item[0].event.event_date).toLocaleDateString('en-US', date_options)"></div>
                                    <div class="row mt-2">
                                        <div class="col-3 d-inline">
                                            <div class="cart-item-text" x-text="'Event Code:  #' + item[0].event.event_code"></div>
                                        </div>
                                        <div class="col-9 p-0">
                                            <template x-for="i in item">
                                                <div class="row">
                                                    <div class="col-6 cart-item-text text-end" x-text="i.event_price.price.text_type + ' Photos'"></div>
                                                    <div class="col-2 cart-item-text text-end" x-text="'#' + i.bib_number"></div>
                                                    <div class="col-2 cart-item-text text-end" x-text="'#' + i.event_price.price.price"></div>
                                                    <div class="col-2 cart-item-text text-end"><a href="#" @click="removeFromCart($event, i.id, i.event.id)" class="remove-from-cart-button cart-item-container-button">x</a></div>
                                                </div>
                                            </template>

                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>

                    </div>
                    <div class="container mt-5 p-0">
                            <div class="cart-list-total col-12 p-0 pt-2">
                                <div class="row">
                                    <div class="col-2">ORDER NUMBER</div>
                                    <div class="col-2" x-text="order_number"></div>
                                    <div class="col-4 text-end">TOTAL</div>
                                    <div class="col-4 text-center" x-text="'£' + total"></div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="row mt-10">
                <div class="row col-12">
                    <div class="col-6 customer-form">
                        <div class="title">CUSTOMER DETAILS</div>
                        <input type="text" name="name" x-model="name" required placeholder="Name/Surname" class="cart-form-input mt-3"/>
                        <input type="text" name="email" x-model="email" required placeholder="Email" class="cart-form-input mt-3"/>
                        <input type="text" name="address" x-model="address" required  placeholder="Address" class="cart-form-input mt-3"/>
                        <input type="text" name="postcode" x-model="postcode" required placeholder="Postcode" class="cart-form-input mt-3"/>
                    </div>
                    <div class="col-6">

                        <div class="title">SELECT PAYMENT METHOD</div>
                        <div class="d-flex flex-row justify-content-evenly mt-4">
                            @if($total_price <= 0)
                                <div class="payment-method-button-container"><a href="" @click.prevent="sendImages()" class="payment-method-button">Free Download</a></div>
                             @else
                                <div @click="paypalPayment()">
                                    <svg class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="100" height="88.89" preserveAspectRatio="xMidYMid meet" viewBox="0 0 576 512"><path fill="currentColor" d="M186.3 258.2c0 12.2-9.7 21.5-22 21.5c-9.2 0-16-5.2-16-15c0-12.2 9.5-22 21.7-22c9.3 0 16.3 5.7 16.3 15.5zM80.5 209.7h-4.7c-1.5 0-3 1-3.2 2.7l-4.3 26.7l8.2-.3c11 0 19.5-1.5 21.5-14.2c2.3-13.4-6.2-14.9-17.5-14.9zm284 0H360c-1.8 0-3 1-3.2 2.7l-4.2 26.7l8-.3c13 0 22-3 22-18c-.1-10.6-9.6-11.1-18.1-11.1zM576 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48zM128.3 215.4c0-21-16.2-28-34.7-28h-40c-2.5 0-5 2-5.2 4.7L32 294.2c-.3 2 1.2 4 3.2 4h19c2.7 0 5.2-2.9 5.5-5.7l4.5-26.6c1-7.2 13.2-4.7 18-4.7c28.6 0 46.1-17 46.1-45.8zm84.2 8.8h-19c-3.8 0-4 5.5-4.2 8.2c-5.8-8.5-14.2-10-23.7-10c-24.5 0-43.2 21.5-43.2 45.2c0 19.5 12.2 32.2 31.7 32.2c9 0 20.2-4.9 26.5-11.9c-.5 1.5-1 4.7-1 6.2c0 2.3 1 4 3.2 4H200c2.7 0 5-2.9 5.5-5.7l10.2-64.3c.3-1.9-1.2-3.9-3.2-3.9zm40.5 97.9l63.7-92.6c.5-.5.5-1 .5-1.7c0-1.7-1.5-3.5-3.2-3.5h-19.2c-1.7 0-3.5 1-4.5 2.5l-26.5 39l-11-37.5c-.8-2.2-3-4-5.5-4h-18.7c-1.7 0-3.2 1.8-3.2 3.5c0 1.2 19.5 56.8 21.2 62.1c-2.7 3.8-20.5 28.6-20.5 31.6c0 1.8 1.5 3.2 3.2 3.2h19.2c1.8-.1 3.5-1.1 4.5-2.6zm159.3-106.7c0-21-16.2-28-34.7-28h-39.7c-2.7 0-5.2 2-5.5 4.7l-16.2 102c-.2 2 1.3 4 3.2 4h20.5c2 0 3.5-1.5 4-3.2l4.5-29c1-7.2 13.2-4.7 18-4.7c28.4 0 45.9-17 45.9-45.8zm84.2 8.8h-19c-3.8 0-4 5.5-4.3 8.2c-5.5-8.5-14-10-23.7-10c-24.5 0-43.2 21.5-43.2 45.2c0 19.5 12.2 32.2 31.7 32.2c9.3 0 20.5-4.9 26.5-11.9c-.3 1.5-1 4.7-1 6.2c0 2.3 1 4 3.2 4H484c2.7 0 5-2.9 5.5-5.7l10.2-64.3c.3-1.9-1.2-3.9-3.2-3.9zm47.5-33.3c0-2-1.5-3.5-3.2-3.5h-18.5c-1.5 0-3 1.2-3.2 2.7l-16.2 104l-.3.5c0 1.8 1.5 3.5 3.5 3.5h16.5c2.5 0 5-2.9 5.2-5.7L544 191.2v-.3zm-90 51.8c-12.2 0-21.7 9.7-21.7 22c0 9.7 7 15 16.2 15c12 0 21.7-9.2 21.7-21.5c.1-9.8-6.9-15.5-16.2-15.5z"/></svg>
                                </div>
{{--                                <div @click="stripePayment()">--}}
{{--                                    <svg class="cursor-pointer"  xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="100" height="88.89" preserveAspectRatio="xMidYMid meet" viewBox="0 0 576 512"><path fill="currentColor" d="M492.4 220.8c-8.9 0-18.7 6.7-18.7 22.7h36.7c0-16-9.3-22.7-18-22.7zM375 223.4c-8.2 0-13.3 2.9-17 7l.2 52.8c3.5 3.7 8.5 6.7 16.8 6.7c13.1 0 21.9-14.3 21.9-33.4c0-18.6-9-33.2-21.9-33.1zM528 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zM122.2 281.1c0 25.6-20.3 40.1-49.9 40.3c-12.2 0-25.6-2.4-38.8-8.1v-33.9c12 6.4 27.1 11.3 38.9 11.3c7.9 0 13.6-2.1 13.6-8.7c0-17-54-10.6-54-49.9c0-25.2 19.2-40.2 48-40.2c11.8 0 23.5 1.8 35.3 6.5v33.4c-10.8-5.8-24.5-9.1-35.3-9.1c-7.5 0-12.1 2.2-12.1 7.7c0 16 54.3 8.4 54.3 50.7zm68.8-56.6h-27V275c0 20.9 22.5 14.4 27 12.6v28.9c-4.7 2.6-13.3 4.7-24.9 4.7c-21.1 0-36.9-15.5-36.9-36.5l.2-113.9l34.7-7.4v30.8H191zm74 2.4c-4.5-1.5-18.7-3.6-27.1 7.4v84.4h-35.5V194.2h30.7l2.2 10.5c8.3-15.3 24.9-12.2 29.6-10.5h.1zm44.1 91.8h-35.7V194.2h35.7zm0-142.9l-35.7 7.6v-28.9l35.7-7.6zm74.1 145.5c-12.4 0-20-5.3-25.1-9l-.1 40.2l-35.5 7.5V194.2h31.3l1.8 8.8c4.9-4.5 13.9-11.1 27.8-11.1c24.9 0 48.4 22.5 48.4 63.8c0 45.1-23.2 65.5-48.6 65.6zm160.4-51.5h-69.5c1.6 16.6 13.8 21.5 27.6 21.5c14.1 0 25.2-3 34.9-7.9V312c-9.7 5.3-22.4 9.2-39.4 9.2c-34.6 0-58.8-21.7-58.8-64.5c0-36.2 20.5-64.9 54.3-64.9c33.7 0 51.3 28.7 51.3 65.1c0 3.5-.3 10.9-.4 12.9z"/></svg>--}}
{{--                                </div>--}}
                            @endif
                        </div>
                        <div class="continue-shopping-link mt-5 text-center"><a class="" href="/">CONTINUE SHOPPING</a></div>
                    </div>
                </div>

                <div class="mansory-grid mt-10">
                    @foreach($bib_number_thumbnails as $photo)
                        <div class="mansory-grid-item">
                            <img src="{{$photo->thumbnail}}" alt="{{$photo->title}}" width="350"/>
                        </div>
                    @endforeach
                </div>
{{--            <div class="row mt-10" id="photoList">--}}
{{--                    @foreach($cart as $items)--}}
{{--                        @foreach($items as $item)--}}
{{--                            @foreach($item->event->bib_number->where("bib_number", $item->bib_number) as $bib_number)--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="photoBox overflow-hidden">--}}
{{--                                        <img src="{{$bib_number->thumbnail}}" alt="{{$bib_number->title}}" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        @endforeach--}}
{{--                    @endforeach--}}
{{--            </div>--}}
        </div>
        </div>
        <div x-cloak class="container" id="shoppingCart" x-show="Object.keys(cart).length < 1">
            <div class="empty-cart-text text-center">Your cart is empty.</div>
            <div class="empty-cart-text text-center continue-shopping-link"><a href="/">CONTINUE SHOPPING</a></div>
        </div>

        <div class="modal" tabindex="-1" id="well_done">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center p-5">
                        <p class="font-bold pb-4 well_done_title">WELL DONE</p>
                        <p>Your purchase is completed.</p>
                        <p>A confirmation message and download link sent to your email (<span x-text="email"></span>). (This process may take a few minutes).</p>
                        <div class="w-full pt-4">
                            <a href="/" class="well_done_button">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

{{--    <div class="modal" tabindex="-1" id="stripe">--}}
{{--        <div class="modal-dialog">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body text-center p-5 pb-4 pt-3 stripe-card-form">--}}
{{--                    <form id="stripe-payment-form" method="POST" action="{{route("frontend.payment.process.stripe")}}">--}}
{{--                        @csrf--}}
{{--                        <div class="mb-3"><img width="100" src="/frontend/img/stripe.png"></div>--}}
{{--                        <input id="card-holder-name" placeholder="Cardholder name" type="text">--}}
{{--                        <input id="pmethod" name="paymentMethodId" type="hidden">--}}

{{--                        <!-- Stripe Elements Placeholder -->--}}
{{--                        <div id="card-element"></div>--}}

{{--                        <div class="payment-error p-3"></div>--}}

{{--                        <button id="pay-now-button" >--}}
{{--                            <div class="spinner hidden" id="spinner"></div>--}}
{{--                            Update Payment Method--}}
{{--                        </button>--}}

{{--                        <a id="cancel-button" data-bs-dismiss="modal">--}}
{{--                            Cancel--}}
{{--                        </a>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
