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
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-pick-up-list" type="button" role="tab"
                                        aria-controls="nav-home" aria-selected="true">Complaints</button>
                                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-new-pick-up" type="button" role="tab"
                                        aria-controls="nav-profile" aria-selected="false">Send New Complaint</button>
                                </div>
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
                                                                <i class="ti ti-loader fs-5"></i> waiting for responses...
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
                                    <div class="tab-pane fade" id="nav-new-pick-up" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <form action="{{ route('send.complaints') }}" method="POST">
                                            @csrf
                                            <div class="col-12 p-2">
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="preferred_time" class="form-label">
                                                            Problem</label>
                                                        <textarea name="complaints" id="complaints" class="form-control" placeholder="tell us your complaints here..."></textarea>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary"><i class="ti ti-send"></i>
                                                    Submit</button>
                                            </div>
                                        </form>
                                        <hr>
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
