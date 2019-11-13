@extends('motor-docs::layouts.documentation')

@section('navigation')
    {!! $navigation !!}
@endsection

@section('content')
    <h1>Search results</h1>
    <p>
        Your query for '{{$query}} yielded {{count($searchResult)}} result(s).
    </p>
    <ul class="list-unstyled">
        @foreach($searchResult as $result)
            <li><a href="/{{config('motor-docs.route')}}/{{$result['package']}}/{{$result['file']}}">{!! $result['content'] !!}</a></li>
        @endforeach
    </ul>
@endsection
