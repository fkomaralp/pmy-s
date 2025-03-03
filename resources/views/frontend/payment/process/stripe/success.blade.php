@extends('layouts.frontend.layout')

@push('scripts')

@endpush

@section('content')
    <div>
        <p class="font-bold pb-4 well_done_title">WELL DONE</p>
        <p>Your purchase is completed.</p>
        <p>A confirmation message and download link sent to your email address.</p>
        <div class="w-full pt-4">
            <button type="button" class="well_done_button" data-bs-dismiss="modal">OK</button>
        </div>
    </div>

@endsection
