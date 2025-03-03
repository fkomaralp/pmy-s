importScripts("https://unpkg.com/axios/dist/axios.min.js");

self.addEventListener('message', (e) => {

    if(file.status === 0) {
        axios.post('', {
            file_name: file.file_name,
            event_id: @this.event_id
        }).then((result) => {

            let interval_function = () => {
                axios.post('{{route("api.image_status")}}', {
                    "event_id": @this.event_id,
                    "file_name": file.file_name
                }).then((result) => {
                    if (result.data.status) {
                        this.status[file.file_name] = result.data.result
                        this._status[file.file_name] = result.data._result

                        this.analysisFinished = result.data.is_finished_all
                        this.analysisStarted = !result.data.is_finished_all

                        if (result.data.is_finished_all) {
                            {{--axios.post('{{route("api.image_analysis.clear_finished")}}', {--}}
                                {{--    event_id: @this.event_id--}}
                                {{--})--}}
    }

    if (result.data.is_finished) {
        cb()
    } else {
        setTimeout(interval_function, 1000);
    }
}
})
};

    interval_function()
})
} else {
        cb()
    }

})
