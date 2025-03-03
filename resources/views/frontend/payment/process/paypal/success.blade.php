@extends('layouts.frontend.layout')

@push('scripts')

@endpush

@section('content')
    <div class="col-lg-12 text-center mb-5 mt-5">
        <p class="font-bold pb-4 well_done_title">WELL DONE</p>
        <p>Your purchase is completed.</p>
        <p>A confirmation message and download link sent to your email ({{$user->email}}) address. (This process may take a few seconds.)</p>
        <div class="w-full pt-4">
            <a class="well_done_button" href="/">OK</a>
        </div>
    </div>
@endsection
