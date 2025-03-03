<?php

namespace App\Http\Livewire\Dashboard\Orders;

use App\Jobs\SendImagesToUserJob;
use App\Models\Event;
use App\Models\Order;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use RedirectsActions, WithPagination;

    public $showingErrors = false;
    public $searchby_event = 0;
    public $searchby_email = "";
    public $searchby_ordernumber = "";

    public function updatingSearchbyEvent()
    {
        $this->resetPage();
    }

    public function updatingSearchbyEmail()
    {
        $this->resetPage();
    }

    public function updatingSearchbyOrdernumber()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::with("user")->orderBy("id", "desc");
        if((int)$this->searchby_event !== 0 && $this->searchby_event !== null){
            $orders = $orders->where("event_id", $this->searchby_event);
        }

        if($this->searchby_email !== ""){
            $email = $this->searchby_email;

            $orders->whereHas('user', function($q) use($email)
            {
                $q->where('email', "LIKE",  "%".$email. "%");

            });
        }

        if($this->searchby_ordernumber !== ""){
            $order_number = $this->searchby_ordernumber;

            $orders->whereHas('user', function($q) use($order_number)
            {
                $q->where('order_number', "LIKE",  "%".$order_number. "%");

            });
        }

        $orders = $orders->paginate(5);

        $events = Event::where("status", 1)->get();

        return view('livewire.dashboard.orders.index', compact("orders", "events"));
    }

    public function retryJob($uuid)
    {
        $order = Order::where("job_uuid", $uuid)->first();
//        foreach($orders as $order){
        $order->job_status = 0;
        $order->save();
//        }

//        \Artisan::call('queue:retry '.$uuid);

        SendImagesToUserJob::dispatch($order);

        return redirect(request()->header('Referer'));
    }
}
