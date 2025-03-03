<?php

namespace App\Http\Livewire\Dashboard;

use App\Jobs\SendImagesToUserJob;
use App\Models\Event;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class Index extends Component
{
    use RedirectsActions;

    public function render()
    {
//        $order = Order::find(19);
//        $r = new SendImagesToUserJob($order);
//        $r->handle();
//        dd("1asd");

        $events = Event::orderBy("created_at")->where("status", 1)->take(5)->get();
        $all_events = Event::where("status", 1)->get()->sortByDesc("created_at")->take(5);
        $orders = Order::orderBy("created_at")->take(5)->get();

        $d1 = Order::where("status", 1)->where("job_status", 0)->whereDate("created_at", "=", date("Y-m-d"))->get();
        $w1 = Order::where("status", 1)->where("job_status", 0)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $m1 = Order::where("status", 1)->where("job_status", 0)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
        $m3 = Order::where("status", 1)->where("job_status", 0)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->addMonths(2)])->get();
        $m6 = Order::where("status", 1)->where("job_status", 0)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->addMonths(5)])->get();
        $y1 = Order::where("status", 1)->where("job_status", 0)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->addMonths(11)])->get();

        $repeated_bib_number_event = [];
        $unique_cnt_collection = collect();
        $dublicated_bib_numbers = collect();
        $unique_cnt_total = [];
        $unique_cnt = collect();

        foreach ($all_events as $event) {
//            $bib_number_cnts = DB::select(
//                DB::raw('SELECT p.id AS p_id,
//       COUNT(DISTINCT bn.bib_number) AS bib_number_cnt
//FROM photos p
//JOIN bib_numbers bn ON bn.photo_id = p.id and bn.deleted_at IS NOT NULL
//where p.event_id = '.$event->id.'
//GROUP BY p.id')
//            )
//            ;
//
//            $cnt = 0;
//            $_unique_cnt = 0;
//
//            if(count($bib_number_cnts) > 0){
//                foreach ($bib_number_cnts as $_cnt){
//                    if($_cnt->bib_number_cnt > 10){
//                        $cnt += $_cnt->bib_number_cnt;
//                    } else {
//                        $_unique_cnt += $_cnt->bib_number_cnt;
//                    }
//                }
//            }
//
//            $repeated_bib_number_event[$event->id] = $cnt;
//            $unique_cnt[$event->id] = $_unique_cnt;


            foreach ($event->photos as $photo) {
                /** @var Collection $bib_numbers_groups */
                $bib_numbers_groups = $photo->bib_numbers->groupBy("bib_number");
                /** @var Collection $bib_numbers_group */
                foreach ($bib_numbers_groups as $bib_numbers_group) {
                    if ($dublicated_bib_numbers->get($event->id) === null){
                        $dublicated_bib_numbers->put($event->id, collect());
                    }

                    $cnt = $dublicated_bib_numbers->get($event->id)->get($bib_numbers_group->first()->bib_number);

                    $event_col = $dublicated_bib_numbers->get($event->id);
                    $event_col->put($bib_numbers_group->first()->bib_number, $bib_numbers_group->count() + $cnt);

                }
            }

            if($event->photos->count() === 0){
                $dublicated_bib_numbers->put($event->id, collect());
            }

            $_dublicated_bib_numbers = collect();

            $unique_bib_numbers = clone $dublicated_bib_numbers;
            if($dublicated_bib_numbers->has($event->id)){
                $_dublicated_bib_numbers = $dublicated_bib_numbers->get($event->id)->filter(function ($val, $key){
                        if($val >= 10){
                            return true;
                        } else {
                            return false;
                        }
                });
            }

            $dublicated_bib_numbers->put($event->id, $_dublicated_bib_numbers);
            if($unique_bib_numbers->has($event->id)){
                $unique_cnt = $unique_bib_numbers->get($event->id)->filter(function ($val, $key){
                        if($val < 10){
                            return true;
                        } else {
                            return false;
                        }
                });
            }

            $unique_cnt_collection->put($event->id, $unique_cnt);

//            foreach ($dublicated_bib_numbers->all() as $_key => $sub_dublicated_bib_numbers) {
//                foreach ($sub_dublicated_bib_numbers->all() as $bib => $cnt) {
//                    if ($cnt < 10) {
//                        $unique_cnt[$_key][$bib] = $cnt;
//                    }
//                }
//            }
//            foreach ($unique_cnt as $key => $item){
//                foreach ($item as $_key => $_item){
//                    if(!isset($unique_cnt_total[$key])) {
//                        $unique_cnt_total[$key] = 0;
//                    }
//                        $unique_cnt_total[$key] = $unique_cnt_total[$key] + $_item;
//                }
//            }
        }
        return view('dashboard', compact("events", "unique_cnt_collection", "orders", "d1", "w1", "m1", "m3", "m6", "y1", "all_events", "dublicated_bib_numbers"));
    }
}
