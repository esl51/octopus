@extends('layouts.default')

@section('title', $page->meta_title ?: $page->title)
@section('keywords', $page->meta_keywords)
@section('description', $page->meta_description)

@section('head')

  @foreach ($page->translations as $t)
    <link rel="alternate" hreflang="{{ $t->locale }}" href="{{ $t->url }}">
  @endforeach

@endsection

@section('content')

  <p><strong>url:</strong> {{ $page->url }}</p>

  <p><strong>translations</strong></p>
  <ul>
    @foreach ($page->translations as $t)
      <li><a href="{{ $t->url }}">{{ $t->locale }}</a></li>
    @endforeach
  </ul>

  <p><strong>pages</strong></p>
  <ul>
    @foreach ($pages as $p)
      <li><a href="<?= $p->url ?>">{{ $p->title }}</a></li>
    @endforeach
  </ul>

  <h1>{{ $page->headline ?: $page->title }}</h1>

  {!! $page->body !!}

@endsection
