@php /** @var \Illuminate\Support\Collection|\App\Models\User[] $users */ @endphp
@php $it = 0; @endphp
@php $authUser = auth()?->user(); @endphp
@php $withoutPagination = $withoutPagination ?? false; @endphp
@php $subscribeAllowed = $subscribeAllowed ?? false; @endphp
@forelse($users->filter()->all() as $user)
    @if(0 === $it || 0 === $it % 3)
        <div class="col-lg-12">
            <div class="row">
                @endif
                @php ++$it @endphp
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <a href="{{ $user->getUserLink() }}" class="">
                                <img src="{{ $user->getAvatar() }}"
                                     alt="avatar"
                                     class="rounded-circle img-fluid" style="width: 150px;">
                            </a>
                            <h5 class="my-3">{{ $user->getFullName() }}</h5>
                            <p class="text-muted mb-1">
                                {{ $user->getAtUsername() }}
                                @if ($user->isPremium())
                                    <span title="Створені збори" class="badge bg-golden p-1">
                                    <i class="bi bi-telegram" title="Telegram Premium"
                                       style="color: #fff;"></i>
                                    </span>
                                @endif
                                @if ($user->fundraisings->count())
                                    <span title="Створені збори" class="badge p-1 bg-info">
                                    &nbsp;{{ $user->fundraisings->count() }}&nbsp;
                                </span>
                                @endif
                                @if ($user->getDonateCount())
                                    <span title="Завалідовані донати" class="badge p-1 bg-success">
                                    &nbsp;{{ $user->getDonateCount() }}&nbsp;
                                </span>
                                @endif
                                @if ($user->getPrizesCount())
                                    <span title="Призи для зборів" class="badge p-1 bg-warning">
                                    &nbsp;{{ $user->getPrizesCount() }}&nbsp;
                                </span>
                                @endif
                            </p>
                            @if($subscribeAllowed && $authUser && $user->fundraisings->count() > 0)
                                @php $volunteer = $user; @endphp
                                @include('subscribe.button', compact('volunteer', 'authUser'))
                            @endif
                        </div>
                    </div>
                </div>
                @if($it && 0 === $it % 3)
            </div>
        </div>
    @endif
@empty
    <p>Користувачі не знайдені</p>
@endforelse
@if(!$withoutPagination)
    <div class="col-12">
        <div class="row">
            {{ $users->links('layouts.pagination', ['elements' => $users]) }}
        </div>
    </div>
@endif

