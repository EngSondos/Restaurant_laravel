<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Traits\ApiRespone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReservationController extends Controller
{
    use ApiRespone;
    public function index()
    {
        $reservations =  Reservation::with('table')->paginate(8);
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
     $reservations =   Reservation::where('table_id','=',$table_id)->get();

     return $this->sendData('',$reservations);
    }

    public function getReservationByDate(Request $request)
    {
        //5 pm to 7 pm
        $reservations = Reservation::with('table')->whereBetween('start_date', [$request->start_date, $request->end_date])->get();
        return $this->sendData('',$reservations);
    }

    public function  store (Request $request)
    {
        //request -->>> table , date ->
        $calculate_end_date = Carbon::parse($request->start_date)->addHours(2)->toDate()->format('Y-m-d H:i:s');
        $end_date = $request->input('end_date',$calculate_end_date);

        $reservation = new Reservation;
        $reservation->start_date = $request->input('start_date');
        $reservation->end_date = $end_date;
        $reservation->table_id = $request->input('table_id');
        $reservation->customer_id = $request->input('customer_id'); //will change by login customer

        if($reservation->save())
            return $this->success('Reservation Added Successfully');
        return $this->error('Reservation Not Added');


    }
    public function showAvailableTimeToCustomerByTableId(Request $request)
    {
        //table id and day which whant to reserve in it
         dd(Reservation::where('table_id','=',$request->table_id)
         ->whereRaw('DATE(start_date) = ?', [$request->date])
         ->get());
    }

    // public function

}
