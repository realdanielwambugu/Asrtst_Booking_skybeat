<?php







    public function FunctionName($value='')
    {
      $sql1 = $this->connection->prepare('use makinibakers');
      $sql1->execute();

      $sql = $this->connection->prepare("SELECT from_date,to_date FROM times");
      $sql->execute();

      $bookedTime = $sql->fetchAll();

      $from = '08:00:00';//or '13:00:00'
      $to = '16:04:00';//or '17:00:00'
       // $bookedTime = [
       //   (object)[
       //     'from_date'=>'10:00:00',
       //     'to_date' => '16:00:00'
       //   ],
       //   // (object)[
       //   //   'from_date'=>' 10:05:00',
       //   //   'to_date' => ' 12:22:00'
       //   // ]
       // ];
       echo "Compare: FROM: {$from} TO: $to</br></br>";

      foreach ($bookedTime as $time)
      {

        if (
          $this->Check_if_new_from_time_is_in_booked_time($from,$time->from_date,$time->to_date)

        ||
         $this->Check_if_new_to_time_is_in_booked_time($to,$time->from_date,$time->to_date)
        ||
        $this->Check_if_booked_from_time_is_in_new_time($time->from_date,$from,$to)
        ||
        $this->Check_if_booked_to_time_is_in_new_time($time->to_date,$from,$to)
        )
        {
          echo "From: {$time->from_date} To: {$time->to_date} Time in between: booking fail</br>";
        }else {
          echo "From: {$time->from_date} To: {$time->to_date} Time outside constraints: booking success</br>";
        }

      }
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
