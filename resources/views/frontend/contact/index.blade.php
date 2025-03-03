@extends('layouts.frontend.layout')

@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        function component() {
            return {
                fullname: "",
                email: "",
                question: "",
                sendMessage() {
                    axios.post('{{route("api.contact.send")}}', {
                        fullname : this.fullname,
                        email : this.email,
                        question : this.question
                    }).then((response) => {
                        alert(response.data.message)
                    }).catch((err) => {

                        var messages = ""

                        for(const [key, message] of Object.entries(err.response.data.errors)){
                            messages += message + "\n"
                        }

                        alert(messages)
                    })
                }
            }
        }
    </script>
@endpush

@section('content')
    <div class="container" x-data="component()">
        <div class="row">
            <div class="col-12">
                <div id="contactForm">
                    <!-- <img src="assets/img/CONTACT-US.svg" alt="" /> -->
                    <div class="gradientTitle">CONTACT US</div>
                    <p>
                        For any inquire please get in touch with us. <br>
                        <br>
                        Don’t worry, your message will directly go to a team member’s inbox. We use forms to avoid bot messages.
                    </p>
                    <form name="contactForm">
                        <input type="text" x-model="fullname" name="fullname" placeholder="Full Name" />
                        <input type="text" x-model="email" name="email" placeholder="Email" />
                        <textarea name="question" x-model="question" placeholder="Your Message"></textarea>
                        <button type="button" @click.prevent="sendMessage()">SUBMIT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
