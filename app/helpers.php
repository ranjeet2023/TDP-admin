<?php

use Illuminate\support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Notification;


    if(!function_exists('notification_new')) {
        function notification_new($customer_id,$title,$body,$date){
            $result = Notification::insert(['user_id'=>$customer_id,'title'=>$title,'body'=>$body,'created_date'=>$date,]);

            if($result == true){
                $return = 'success';
            }
            else{
                $return = 'failed';
            }
            return $return;
        }
    }

?>
