@extends('layouts.frontend.layout')

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        function component() {
            window.searchingModal = new bootstrap.Modal(document.getElementById('searchingModal'), {
                backdrop: 'static',
                keyboard: false
            })

            return {
                bib_number: "",
                event_access_code: "",
                event_id: {{$event->id}},
                isError: false,
                password_error: "",
                searchResult: "Please wait...",
                searchStatus: false,
                go() {

                    this.searchStatus = false
                    this.searchResult = "Please wait..."

                    window.searchingModal.show()

                    axios.post('/api/events/check', {
                        event_access_code: this.event_access_code,
                        bib_number: this.bib_number,
                        event_id: this.event_id
                    })
                        .then((response) => {
                            if(response.data.status){
                                location.href = response.data.redirect_to
                            } else {
                                this.isError = true
                                this.searchStatus = true
                                this.password_error = response.data.message
                                this.searchResult = this.password_error
                            }

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
                            {{$event->event_date->format("d/m/Y")}}<br>
                            <p class="pt-3 pb-3">{{$event->note}}</p>
                        </div>
                    </div>
                    <div class="passwordForm mt-5">
                        <form name="passwordForm" method="POST" @submit.prevent="event.preventDefault()">
                            <input type="text" x-model="bib_number" placeholder="Bib Number" title="Bib Number" />
                            @if($event->protected)
                                <input type="text" x-model="event_access_code" placeholder="Event Access Code" title="Event Access Code" />
                            @endif
                            <button type="submit" x-on:click="go()">GO</button>
{{--                            <div x-if="isError" x-text="password_error"></div>--}}
                        </form>
                    </div>
                </div>

                <div class="container mt-5 mb-5">
                    <div class="row">
                        @foreach ($photos as $photo)
                            <div class="col-lg-3 col-md-4 col-sm-8 text-center">
                                <a href="{{$photo->thumbnail}}" target="_blank">
                                    <img src="{{$photo->thumbnail}}" class="rounded m-3" height="150">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="text-center">
                {{ $photos->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="searchingModal">
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-body text-center p-5 ">
                    <p class="font-bold pb-4"><img src="{{$favicon}}" width="60"></p>
                    <p class="font-bold pb-4" x-text="searchResult">Please wait...</p>
                    <div class="w-full" x-cloak x-show="searchStatus">
                        <a href="#" @click.prevent="window.searchingModal.hide()">OK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($event->protected)
        <div class="container">
            <div id="lockedDesc">
                <div class="lockedIcon"><img src="/frontend/img/locked.svg" alt="" /></div>
                <div class="row">
                    <div class="col-md-6 col-sm-8 mx-auto text">
                        <p>This event's photos are protected.<br><br>
                            Please enter the Event Access Code sent by the event organiser and your bib number to access your photos. <br><br>
                            If you don’t know your Event Access Code, <br><br>
                            please contact your organiser or send us an email to {{$email}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
@endsection
