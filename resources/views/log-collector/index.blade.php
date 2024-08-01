@extends('layouts.layouts')

@section('konten')
    <section class="section">
        <div class="section-header">
            <h1>Data Log</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-form">
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Number</th>
                                            <th>User Agent</th>
                                            <th>Location Latitude</th>
                                            <th>Location Longitude</th>
                                            <th>Network Info</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $value)
                                            <tr>
                                                <td>{{ $value['id'] }}</td>
                                                <td>{{ $value['number'] }}</td>
                                                <td>{{ $value['agent'] }}</td>
                                                <td>{{ $value['latitude'] }}</td>
                                                <td>{{ $value['longitude'] }}</td>
                                                <td>{{ $value['network'] }}</td>
                                                <td>{{ $value['created_at'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
