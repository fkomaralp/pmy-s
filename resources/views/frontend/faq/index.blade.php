@extends('layouts.frontend.layout')

@push('scripts')
    <script src="/frontend/js/jquery.min.js"></script>
    <script>
        $(".faqSubject a").on("click", function () {
            var s = $(this).parent();
            var sd = $(this).parent().next(".faqSubjectDetails");

            s.addClass("active").siblings(".faqSubject").removeClass("active");
            sd.addClass("active").siblings(".faqSubjectDetails").removeClass("active");
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div id="faqPage">
                    <div class="gradientTitle">FAQ</div>
                    <div id="faqList">
                        @foreach($faqs as $faq)
                            <div class="faqSubject"><a href="javascript:;">{{$faq->title}}</a></div>
                            <div class="faqSubjectDetails">
                                @foreach($faq->questions as $question)
                                    <div class="question">
                                        <label>{{$question->question}}</label>
                                        <p>{{$question->answer}}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
