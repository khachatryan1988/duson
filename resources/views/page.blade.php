@extends('layout.app')

@section('head')
    <title>{{$page->title}}</title>
    <meta name="description" content="{{$page->title}}">
@endsection

@section('content')
    @foreach($page->content as $section)
        <x-dynamic-component :component="'sections.' . $section['layout']" :section="$section['attributes']" />
    @endforeach
@endsection
