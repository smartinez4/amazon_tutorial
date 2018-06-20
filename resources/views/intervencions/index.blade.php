<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>

{{--
<h1>  {{ $intervencions->Codi_procedim }}  </h1>
--}}

{{--<h1> {{dd($intervencions)}} </h1>--}}
<ul>
@foreach($intervencions as $intervencio)

    <li>
        <a href="/intervencions/{{ $intervencio->Codi_procedim}}">
            {{ $intervencio -> Data_hora_Intervencio }}
        </a>
    </li>

@endforeach
</ul>
</body>
</html>
