@extends('layouts.app')
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
                                <p class="text-primary">Notification Center For The PickUp Week (
                                    {{ $startOfWeek . ' ' . ' - ' . ' ' . $endOfWeek }} )</p>
                            </div>
                            <div class="card-body">
                                <div class="tab-content mt-0" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-pick-up-list" role="tabpanel"
                                        aria-labelledby="nav-profile-tab">
                                        <form action="{{ route('set.reminders') }}" method="POST" class="row">
                                            @csrf
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Message
                                                        Title
                                                    </label>
                                                    <input type="text" class="form-control" name="title" value="PickUp request reminder for the week {{ $startOfWeek . ' ' . ' - ' . ' ' . $endOfWeek }}"
                                                        id="exampleFormControlInput1" placeholder="message title...."
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1" class="form-label">Message
                                                        Body
                                                    </label>
                                                    <textarea class="form-control" name="message_body" placeholder="message body...." required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <button type="submit" class="btn btn-primary"><i class="ti ti-send"></i>
                                                    Submit</button>
                                            </div>
                                        </form>
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
