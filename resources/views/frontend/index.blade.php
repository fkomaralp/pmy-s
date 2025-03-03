@extends('layouts.frontend.layout')

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        function component() {
            return {
                page: 1,
                events : {!! json_encode($events) !!},
                other_page_events : [],
                keyword: "",
                searchResult: [],
                showResults: false,
                loadMore() {
                    this.page++

                    this.other_page_events = this.events.slice(6, this.page*6)
                },
                getImage(pageEvent) {
                    return (pageEvent.image === null) ? `'{{$DEFAULT_EVENT_IMAGE}}'` : pageEvent.image
                },
                onSearch(){
                    if(this.keyword.length > 0){
                        axios.post('/api/events/searchByName', {keyword : this.keyword})
                            .then((response) => {
                                // handle success
                                this.searchResult = response.data
                                if(this.searchResult.length > 0){
                                    this.showResults = true
                                } else {
                                    this.showResults = false
                                }
                            })
                            // .catch(function (error) {
                            //     // handle error
                            //     console.log(error);
                            // })
                            // .then(function () {
                            //     // always executed
                            // });
                    } else {
                        this.showResults = false
                    }
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
                <div id="homeSearch">
                    <img src="/frontend/img/homeimg.jpg" class="d-block w-100" alt="" />
                    <div class="searchForm">
                        <form method="post" @submit.prevent.stop="onSearch()">
                            <input type="text" @keyup="onSearch()" x-model="keyword" placeholder="Search Event Name" title="Search Event Name" autocomplete="off" />
                            <button type="button" @click="onSearch()" >RUN >></button>
                        </form>
                        <div x-cloak class="searchFormResult" x-show="showResults" @click.away="showResults = false">
                            <ul>
                                <template x-for="result in searchResult" :key="result.id">
                                    <li>
                                        <a :href="'event/'+result.id+'/details'" x-text="result.title + '' + ((result.country !== '') ? ' / ' + result.country : '') + ((result.city !== '') ? ' / ' + result.city : '')"></a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        @if($events->count() > 0)
            <div class="container" id="recentEvents" >
                <div class="title">RECENT EVENTS</div>
                <div class="row" id="eventList">
                    @foreach($events->take(6) as $event)
                        <div class="col-md-4">
                            <a href="{{route("frontend.event.access.show", ["event_id" => $event->id, "event_title" => slug($event->title)])}}">
                                <div class="eventBox">
                                    <div class="img_container" style="background-image: url({{($event->image === null) ? $DEFAULT_EVENT_IMAGE : $event->image}})">
                                    </div>
                                    <span class="eventTitle">{{$event->title}}</span>
                                    <div class="d-flex">
                                        <span class="location">{{$event->city}}</span>
{{--                                        <span class="date">{{$event->event_date->format("d/m/Y")}}</span>--}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                        <template x-for="(pageEvent, index) in other_page_events" :key="index">
                            <div class="col-md-4">
                                <a x-bind:href="'/event/'+pageEvent.id+'/details'">
                                    <div class="eventBox">
                                        <div class="img_container" :style="`background-image: url(${getImage(pageEvent)})`">
                                        </div>
                                        <span class="eventTitle" x-text="pageEvent.title"></span>
                                        <div class="d-flex">
                                            <span class="location" x-text="pageEvent.city"></span>
{{--                                            <span class="date">{{$event->event_date->format("d/m/Y")}}</span>--}}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </template>
                </div>
                @if($events->count() > 6)
                    <div class="loadMoreBtn">
                        <a href="#" x-on:click.prevent="loadMore()">LOAD MORE</a>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
