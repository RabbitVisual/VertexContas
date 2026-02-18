@extends('emails.layout')

@section('content')
@if(!empty($isPlain) && $isPlain)
{!! nl2br(e($bodyHtml ?? '')) !!}
@else
{!! $bodyHtml ?? '' !!}
@endif
@endsection
