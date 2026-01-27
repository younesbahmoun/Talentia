<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  
    <ul>
        @foreach($languages as $language)
            <li>{{ $language }}</li>
        @endforeach
    </ul>
</body>
</html>
<?php
// echo $name;
// echo "<br>";
// echo $age;
// echo $name;
 ?>