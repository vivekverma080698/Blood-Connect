<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Geocoding </title>

    <style>
    body{
        font-family:arial;
        font-size:.8em;
    }

    input[type=text]{
        padding:0.5em;
        width:20em;
    }

    input[type=submit]{
        padding:0.4em;
    }

    #address-examples{
        margin:1em 0;
    }
    </style>

</head>
<body>
 <div id='address-examples'>
    <div>Address examples:</div>
    <div>1. G/F Makati Cinema Square, Pasong Tamo, Makati City</div>
    <div>2. 80 E.Rodriguez Jr. Ave. Libis Quezon City</div>
</div>   
<form action="" method="post">
    <input type='text' name='address' placeholder='Enter any address here' />
    <input type='submit' value='Geocode!' />
</form>
<?php 
// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDzsn-4wZV91omAtAEogIe2xyKxEcFafCk";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
        $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
        $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }
 
    else{
        echo "<strong>ERROR: {$resp['status']}</strong>";
        return false;
    }
}
?>
  
</body>
<?php
if($_POST){
 
    // get latitude, longitude and formatted address
    $data_arr = geocode($_POST['address']);
 
    // if able to geocode the address
    if($data_arr){
         
        $latitude = $data_arr[0];
        $longitude = $data_arr[1];
        $formatted_address = $data_arr[2];
                     
    ?>
 
    <p>Latitude is : <?php echo $latitude; ?> <br>
        Longitude is : <?php echo $longitude; ?> </p>

   
    <?php
 
    // if unable to geocode the address
    }else{
        echo "No map found.";
    }
}
?>
</html>