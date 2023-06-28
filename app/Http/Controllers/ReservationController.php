<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Traits\ApiRespone;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    use ApiRespone;
    public function index()
    {
        $reservations =  Reservation::with('table')->paginate(8);
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



}
