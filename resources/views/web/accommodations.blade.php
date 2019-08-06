<html>
<head>
    <style>
        .collapsible {
            background-color: #777;
            color: white;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

        .active, .collapsible:hover {
            background-color: #555;
        }

        .content {
            padding: 0 18px;
            display: none;
            overflow: hidden;
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
@foreach($accommodations as $accommodation)
<button class="collapsible">{{$accommodation->name}}</button>
<div class="content">
    @foreach($accommodation->rooms as $room)
    <div class="content-inner" style="border-top: 2px solid black">
        <a href="/administrator/accommodations/rooms/{{$room->id}}">
            <p>Room id: {{$room->id}}</p>
            <p>Room number: {{$room->room_number}}</p>
            <p>Room capacity: {{$room->capacity}}</p>
            <p>Description: {{$room->description}}</p>
        </a>
    </div>
    @endforeach
</div>
@endforeach

<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
                content.style.display = "none";
            } else {
                content.style.display = "block";
            }
        });
    }
</script>
</body>
</html>
