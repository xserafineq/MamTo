@props(['auction' => null])

@if(auth()->check() && $auction && (int) auth()->id() === (int) $auction->userId)
    <span class="badge rounded-pill text-bg-primary auction-own-badge">Twoje ogłoszenie</span>
@endif
