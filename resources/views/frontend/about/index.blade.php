@extends('layouts.frontend.layout')

@push('scripts')
@endpush

@section('content')
    <div class="container">
        <div class="row g-0">
            <div class="col-12">
                <div id="aboutImg">
                    <img src="/frontend/img/about2.jpg" alt="" class="d-block w-100" />
                </div>
                <div class="aboutText">
                    <span class="text">
                        We do one thing and doing it well;<br>
                        Digitally delivering best moments of your sport experiences
                    </span>
                </div>
            </div>
        </div>

        <div class="aboutList">
            <div class="row">
                <div class="col-sm-6">
                    <div class="aboutBox">
                        <div class="icon">
                            <img src="/frontend/img/icon-aperture.svg" alt="" />
                        </div>
                        <div class="text">
                            Capturing best sports moments with experienced sport photographers
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="aboutBox">
                        <div class="icon">
                            <img src="/frontend/img/icon-data.svg" alt="" />
                        </div>
                        <div class="text">
                            Automatically organizing and tagging with bib numbers
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="aboutBox">
                        <div class="icon">
                            <img src="/frontend/img/icon-photos.svg" alt="" />
                        </div>
                        <div class="text">
                            Delivering quick and securely to participants’ inboxes
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="aboutBox">
                        <div class="icon">
                            <img src="/frontend/img/icon-tag.svg" alt="" />
                        </div>
                        <div class="text">
                            Simple pricing. Print size, digital size or even free at sponsored events
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="organizerBox">
            <div class="text">Are you an organiser?</div>
            <a href="mailto:picmyrun@gmail.com" class="button">GET IN TOUCH</a>
        </div>
    </div>
@endsection
