unplu.gg PHP class
==================

A PHP class to send and retrieve data from unplu.gg Energy Monitoring websitem


## Example
```php
require "unpluggClass.php";
  
$token_API = "5LB3dFwTpRXq454QmdYX";
$meter_ID = "50f9549fd339be09a5000019";

$unplugg = new Unplugg($token_API, $meter_ID);

// postConsumptions($watts, $datetime = 0)
// post consumption in watt-hour
echo $unplugg->postConsumptions(356.65);

// 1 => "all_time", 2 => "today", 3 => "yesterday", 4 => "last_week", 5 => "last_month", 6 => "last_semester"
// getConsumptions($timeframe = 1, $sent_id = 0)
// get consumptions within a timeframe and with a sent_id (zero for all)
echo $unplugg->getConsumptions(); // will get all consumptions

// getHomes($id = 0)
// get all homes. if $id defined, get home with corresponding id
echo $unplugg->getHomes();
```
