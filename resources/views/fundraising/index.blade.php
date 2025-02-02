@extends('layouts.base')
@section('page_title', 'Всі збори та фонди - donater.com.ua')
@section('page_description', 'Вся звітність по Фондам та актуальним зборам коштів - donater.com.ua')
@php $withJarLink = true; @endphp
@php $withZvitLink = true; @endphp
@php $raffles = true; @endphp
@php $withOwner = true; @endphp
@section('content')
    <div class="container px-4 py-5">
        <h2 class="pb-2 border-bottom">Всі збори та фонди</h2>
        @foreach($fundraisings->all() as $it => $fundraising)
            <div class="container px-4 py-5">
                <h3 class="pb-2 border-bottom">
                    @include('layouts.fundraising_status', compact('fundraising', 'withOwner'))
                </h3>
                <div class="row">
                    <div class="col-md-4 px-2 py-2">
                        <div class="card border-0 rounded-4 shadow-lg">
                            <a href="{{ route('fundraising.show', ['fundraising' => $fundraising->getKey()]) }}" class="card">
                                <img src="{{ url($fundraising->getAvatar()) }}" class="bg-image-position-center"
                                     alt="{{ $fundraising->getName() }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 px-2 py-2">
                        <div>
                            {!! $fundraising->getDescription() !!}
                        </div>
                        @if(request()->user()?->can('update', $fundraising))
                            @php $raffles = true; @endphp
                        @endif
                        @include('layouts.links', compact('fundraising', 'withZvitLink', 'withJarLink'))
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-12">
        <div class="row">
            {{ $fundraisings->links('layouts.pagination', ['elements' => $fundraisings]) }}
        </div>
    </div>
@endsection
