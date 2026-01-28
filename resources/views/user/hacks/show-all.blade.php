@extends('layouts.user.app')
@section('title', 'Tải Hack Game')
@section('content')

<x-hero-header title="Tải Game" description="" />


<div class="container">
  <div class="hack-grid">
    @foreach($hacks as $hack)
      <div class="hack-card">
<div class="hack-top">
  @if($hack->logo)
    <img src="{{ $hack->logo }}" alt="{{ $hack->name }} Logo" class="hack-logo">
  @endif
  <span class="hack-name">
    {{ $hack->name }}
    <span class="status-dot {{ $hack->active ? 'active' : 'inactive' }}"></span>
  </span>
</div>


        <div class="hack-thumb" style="background-image:url('{{ $hack->thumbnail ?? $hack->logo }}');"></div>

        <div class="hack-bottom">
        <a href="{{ route('hacks.show', $hack) }}" class="cybr-btn" aria-label="XEM THÊM">
        <span class="cybr-btn__content">XEM THÊM</span>
        <span aria-hidden class="cybr-btn__glitch">XEM THÊM</span>
        <span aria-hidden class="cybr-btn__tag">ONEONE</span>
        </a>
        </div>
      </div>
    @endforeach
  </div>
</div>

@endsection
