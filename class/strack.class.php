<?php


class MStrack
{


        public function addwebhook($cstring, $user_id, $mtype, $customer_id, $subscription_id,$stripe_event_id,$stripe_amount,$stripe_currency,$stripe_plan_id,$stripe_event_status )
        {
                //$sql = "insert into user (username,password,email) values (\'". $cname . "\',\'" . $cpassword . "\',\'" . $cemail . "\')";
                //mysql_select_db("liplus",$cstring);
                $sql = "insert into webhook_stripe_track (user_id, stripe_type,stripe_cus_id, stripe_sub_id, stripe_event_id, stripe_amount,stripe_currency, stripe_plan_id, stripe_event_status ) values ('" . $user_id . "','"  . $mtype  . "','" . $customer_id . "','" . $subscription_id . "','" . $stripe_event_id . "','" . $stripe_amount .  "','" . $stripe_currency . "','" . $stripe_plan_id .   "','" . $stripe_event_status . "')";
                $result = mysqli_query($cstring,$sql);
		if (!$result)
                 {
                     die('Invalid query: ' . mysqli_error($cstring) . " " . $sql);
                     return -1;
                 }
		 $webhook_id = mysqli_insert_id($cstring);
		 return $webhook_id;

        }


}

?>

