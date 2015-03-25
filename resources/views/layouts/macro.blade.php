{!!  
    HTML::macro('cash', function($number) {
        return number_format($number);
    }) 
!!}

{!!  
    HTML::macro('date', function($date) {
        return $date == '0000-00-00 00:00:00'?
                '' : substr($date, 0, 10);
    }) 
!!}

{!!
    HTML::macro('time', function($date) {
        return $date == '0000-00-00 00:00:00'?
                '' : substr($date, 0, 16);
    })
!!}
