<?php

Namespace models;

use models\core\Model;

use database\Connection;

use Http\Request;

class Booking extends Model
{

  public function user()
  {
     return $this->belongsTo('models\User');
  }

  public function artist()
  {
     return $this->belongsTo('models\Artist');
  }


  public function isBooked(Request $request)
  {
      $booking_status = [];

      $bookings = $this->where('artist_id', '=', $request->artist_id)->get();

      foreach ($bookings as $booking)
      {
          if ($request->book_date === $booking->book_date)
          {
              if (
              $booking->Check_if_new_from_time_is_in_booked_time($request->from_time,$booking->from_time,$booking->to_time)
              ||
               $booking->Check_if_new_to_time_is_in_booked_time($request->to_time,$booking->from_time,$booking->to_time)
              ||
              $booking->Check_if_booked_from_time_is_in_new_time($booking->from_time,$request->from_time,$request->to_time)
              ||
              $booking->Check_if_booked_to_time_is_in_new_time($booking->to_time,$request->from_time,$request->to_time)
              )
              {
                 $booking_status[] = true;
              }else
              {
                  $booking_status[] = false;
              }
          }
      }

      return in_array(true, $booking_status);
  }

  public function Check_if_new_from_time_is_in_booked_time($new_from_time,$booked_from_time,$booked_to_time)
  {
     if ($new_from_time >= $booked_from_time &&  $new_from_time <= $booked_to_time) return true;
  }

  public function Check_if_new_to_time_is_in_booked_time($new_to_time,$booked_from_time,$booked_to_time)
  {
     if ($new_to_time >= $booked_from_time &&  $new_to_time <= $booked_to_time) return true;
  }

  public function Check_if_booked_from_time_is_in_new_time($booked_from_time,$new_from_time,$new_to_time)
  {
     if ($booked_from_time >= $new_from_time && $booked_from_time <= $new_to_time) return true;
  }

  public function Check_if_booked_to_time_is_in_new_time($booked_to_time,$new_from_time,$new_to_time)
  {
     if ($booked_to_time >= $new_from_time && $booked_to_time <= $new_to_time) return true;
  }

  public function pending()
  {
      return $this->where('status', '=', 'pending')->count();
  }

  public function confirmed()
  {
     return $this->where('status', '=', 'confirmed')->count();
  }

  public function rejected()
  {
     return $this->where('status', '=', 'rejected')->count();
  }

}
