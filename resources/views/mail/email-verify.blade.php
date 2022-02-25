<h1>Welcome! to Teledocs online service!</h1>
<h3> One more step to finish your account!</h3>
<h3> Just add this code to the website!</h3>
<h3> Code: {{$verify['code']}} </h3>
<h3> This code will expire at {{  Carbon\Carbon::parse($verify['expiration'])->toDayDateTimeString()}}</h3>