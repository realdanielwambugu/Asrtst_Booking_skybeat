<?php
  function app($make = null, $parameters = [])
  {
      if (is_null($make))
      {
         return base\Container::getInstance();
      }

  }

  function dnd($value)
  {
     echo "<pre>". print_r($value, true) ."</pre>";
     die();
  }

  function d($value)
  {
     echo "<pre>". print_r($value, true) ."</pre>";
  }


  function arrayToString($value)
  {
     return is_array($value) ? implode(" ", $value) : $value;
  }

  function toArray($value, $with = null)
  {
      if (is_string($value))
      {
         return explode($with, $value);
      }

      if (is_object($value))
      {
          return (array) $value;
      }

  }


  function is_multi_array($array)
  {
      rsort($array);

      return isset($array[0]) && is_array($array[0]);
  }

  function cutString($string, $char, $start = true, $as = [])
  {
      if (contains($string, $char))
      {

        $front = substr($string, 0, strpos($string, $char));

        $back = substr($string, strrpos($string, $char)+1);

        if ($start === 'both')
        {
           $start = isset($as[0]) ? $as[0] : 'start';

           $end = isset($as[1]) ? $as[1] : 'end';

           return [$start => $front, $end  => $back];
        }

         if ($start)
         {
             return $front;
         }

          return $back;
      }

      return $string;
  }


  function contains($string, $char)
  {
     return strpos(" " . $string, $char);
  }

  function is_callabe($callback)
  {
    return $callback instanceOf Closure;
  }

   function classBasename($longName)
  {
      $array = explode('\\',$longName);

      return end($array);
  }

 function redirectTo($path)
{
    return "redirect:{$path}";
}


function succes($message)
{
   return "<p style='color:green;'>{$message}</p>";
}


function error($message)
{
   return "<p style='color:red;'>{$message}</p>";
}

function url($file, $pathName, $root = 'public')
{
    return support\proxies\Storage::getUrl($file, $pathName, $root);
}

 function dateInstance($date)
{
   return new DateTime($date);
}

 function formatDate($date, $format)
{
    return date_format(dateInstance($date), $format);
}
