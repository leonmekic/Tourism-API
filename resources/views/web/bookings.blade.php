<html>
<body>
<div>
    <h2>Bookings</h2>
    @foreach($room->bookings as $booking)
    <div style="border-bottom: 2px solid black">
        <p>User id {{$booking->user_id}}</p>
        <p>Room id {{$booking->room_id}}</p>
        <p>Booking period: from {{$booking->time_from}} to {{$booking->time_to}}</p>
        <p>Additional Information {{$booking->additional_information}}</p>
        <p>Approved {{$booking->approved}}</p>
    </div>
    @endforeach
</div>
<div><a href="{{ url()->previous() }}">Go back</a></div>
</body>
</html>