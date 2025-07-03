@extends('layouts.app')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    #map {
        height: 500px;
    }
</style>
@section('content')
    <div class="container-fluid">
        <x-flash-messages />
        <div class="row">
            <!--  Row 1 -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                <p class="fs-5 text-primary">Manage Users Complaints</p>
                            </div>
                            <div class="card-body">
                                <div class="tab-content mt-0" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-pick-up-list" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <div class="row" style="height: 500px;">
                                            <div class="scrollable" style="overflow-y: scroll; height: 100%;">
                                                @foreach ($complaints as $item)
                                                    <div class="card p-3">
                                                        <span><i class="ti ti-send text-primary fs-6"></i> <em
                                                                class="text-secondary fs-3">{{ $item->complaints }}</em></span><span
                                                            class="fs-2 mt-2">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y (l)') }}</span>
                                                        <hr>
                                                        <div class="col-12 mb-3">
                                                            <p class="fs-3"><i
                                                                    class="ti ti-checklist text-primary fs-6"></i>
                                                                Feedbacks</p>
                                                            @if ($item->responses === null)
                                                                <form action="{{ route('send.feedbacks') }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="complaint_id" id=""
                                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($item->id) }}">
                                                                    <div class="col-md-12 mb-3">
                                                                        <textarea name="responses" id="complaints" class="form-control" placeholder="send feedbacks..."></textarea>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-send"></i> Send</button>
                                                                </form>
                                                            @else
                                                                <span class="fs-3">
                                                                    {{ $item->responses }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <hr>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
