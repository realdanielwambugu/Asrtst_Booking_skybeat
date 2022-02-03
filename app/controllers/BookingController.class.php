<?php

Namespace controllers;

use controllers\core\Controller;

use Http\Request;

use support\proxies\Auth;

use support\proxies\Session;


use support\proxies\Validator;


class BookingController extends Controller
{

    public function index()
    {
        $bookings = $this->model('Booking')->all();

        return $this->view('bookings', $bookings);
    }

    public function validate(Request $request)
    {
        $validation = Validator::check($request, 'book');

        if ($validation->fails())
        {
            return error($validation->errors()->first());
        }

        if ($this->bookTimeInHours($request) == 0)
        {
           return error('invalid time: (Book From) and (book To) time can not be the same');
        }

        if ($this->bookTimeInHours($request) < 1)
        {
           return error('An artist can not be booked less than one hour');
        }

        return false;
    }

    public function create(Request $request)
    {
        $request->user_id = Auth::id();

        $request->cost = Session::pull('booking.cost');

        $request->artist_id = Session::get('artist_id');
        
        if ($error = $this->validate($request))
        {
            return $error;
        }

        if (!$this->model('Booking')->isBooked($request))
        {
           $booking = $this->model('Booking')->create($request);

           return succes('Artist booked successfully');
        }

      return error("This Artist is aready booked on {$request->book_date}
                        from {$request->from_time} To {$request->to_time}");
    }

    public function summary(Request $request)
    {
       $artist = $this->model('Artist')->find(Session::get('artist_id'));

       $artist->hours = $this->bookTimeInHours($request);

       $artist->total_Cost = $this->totalCost($artist);

       Session::set('booking', ["cost" => $artist->total_Cost]);

       return  $this->view('partials/summary', $artist);
    }

    public function bookTimeInHours(Request $request)
    {
      	$starttimestamp = strtotime($request->to_time);

      	$endtimestamp = strtotime($request->from_time);

      	$difference = abs($endtimestamp - $starttimestamp)/3600;

      	return $difference;
    }

    public function totalCost($artist)
    {
       return number_format(trim(preg_replace("/[^0-9]/", "", $artist->cost_per_hr)) * $artist->hours);

    }

    public function update(Request $request)
    {
       $booking = $this->model('Booking')->find($request->id);

       $booking->status = $request->status;

       $booking->save();

       return $this->index();
    }


    public function overView()
    {
       $booking = $this->model('Booking');

       return $this->view('bookingOverview', $booking);
    }

}
