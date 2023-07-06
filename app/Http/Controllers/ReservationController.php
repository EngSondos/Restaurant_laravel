<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\GetByDateRequest;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Table\TableResource;
use App\Models\Reservation;
use App\Models\Table;
use App\Traits\ApiRespone;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReservationController extends Controller
{
    use ApiRespone;
    public function index()
    {
        $reservations =  Reservation::with(['table','customer'])->paginate(8);
        // dd($reservations);
        return $this->sendData('',$reservations);
    }

    public function getReservationByTableId(int $table_id)
    {
     $table =Table::find($table_id);
     if(!$table)
     {
        return $this->error('This Table Not Exist');

     }
     $reservations =   Reservation::with(['table','customer'])->where('table_id','=',$table_id)->get();

     return $this->sendData('',$reservations);
    }

//waiter
    public function getReservationByTableIdInDay(int $table_id)
    {
     $table =Table::find($table_id);
     if(!$table)
     {
        return $this->error('This Table Not Exist');

     }
     $reservation =   Reservation::with(['table','customer'])->where('table_id','=',$table_id)->where('start_date',Carbon::now())->get();

     return $this->sendData('',$reservation);
    }

    public function getReservationByDate(GetByDateRequest $request)
    {
        //5 pm to 7 pm
        $reservations = Reservation::with(['table','customer'])->whereBetween('start_date', [$request->start_date, $request->end_date])->get();
        return $this->sendData('',$reservations);
    }
//customers function -->
    public function  store (StoreReservationRequest $request)
    {
        //request -->>> table , date ->
        //validation for date -> not in database same day
        //validation for time start must be after now and

        $reservation = new Reservation;
        $reservation->start_date = $request->input('start_date');
        $reservation->table_id = $request->input('table_id');

        $reservation->customer_id = $request->input('customer_id'); //will change by login customer

        // $reservation->customer_id = auth()->guard('customers')->id(); //will change by login customer
        $reservation->order_id = $request->input('order_id');


        if($reservation->save())
            return $this->success('Reservation Added Successfully');
        return $this->error('Reservation Not Added');


    }

    public function getAvailableDateByTableId(int $table_id)
    {
        $startDate = Carbon::now()->startOfDay();
        // dd($startDate);
        $endDate = $startDate->copy()->addDays(7);
        $freeDate=[];

        //get resevation for this week
        $reservations = Reservation::where('table_id',$table_id)
        ->whereBetween('start_date',[$startDate,$endDate])->whereNot('status','canceled')->orderby('start_date')
        ->pluck(DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d') as start_date"));

        //all is reserved
        if($reservations->count()==7)
        {
            return $this->success('No Time Available For this Table In This Week');
        }


        $dates=[];
        for ($date = $startDate; $date < $endDate; $date = $date->copy()->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // no reservetion on this week
        if($reservations->count() == 0 )
        {
            return $this->sendData('Free On This Week ',$dates);
        }

        //get available date
        $freeDate = array_diff($dates,$reservations->values()->toArray());
        $freeDate = array_values($freeDate);

        return $this->sendData('Free On This Week ',$freeDate);

    }

    public function getReservationByCustomerId()
    {
        $reservtions =  Reservation::where('customer_id','=',auth()->user()->id)->get();
        return $this->sendData('',$reservtions);
    }



    public function cancelReservation(int $id)
    {
      $reservation =   Reservation::find($id);
      if(!$reservation)
      {
        return $this->error('Reservation Not Exits');
      }
      $reservation->status="canceled";

     if( $reservation->save())
     {
        return $this->success('Reservation Canceled');
     }
     return $this->error('Some Thing Wrong');
    }

    public function AcceptReservation(int $id)
    {
      $reservation =   Reservation::find($id);
      if(!$reservation)
      {
        return $this->error('Reservation Not Exits');
      }
      $reservation->status="accepted";

     if( $reservation->save())
     {
        return $this->success('Reservation Accepted');
     }
     return $this->error('Some Thing Wrong');
    }

}
