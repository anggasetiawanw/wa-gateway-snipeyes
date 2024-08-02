@extends('layouts.layouts')

@section('konten')
    <section class="section">
        <div class="section-header">
            <h1>Pesan Url</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Pesan url</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <form action="{{ route('autoreplys.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nomor Telepon</label>
                                        <input id="telepon" type="text" class="form-control" name="telepon" value="" placeholder="6282xxxxxxxx">
                                    </div>
                                </div>
                                <div class="float-right">
                                    <button type="submit" class="btn btn-primary" id="store">Kirim</button>
                                </div>
                            </form>
                            <!-- Loading Toast -->
                            <div id="loading-toast" class="loading-toast">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('css')
    <style>
        .loading-toast {
            display: none;
            position: fixed;
            top: 100px;
            right: 20px;
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }
    </style>
@endsection

@section('js')
    <script>
        $('#store').click(function (e){
            e.preventDefault();

            // Show loading toast
            $('#loading-toast').show();

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var tgl = yyyy + "-" + mm + "-" + dd;

            let telepon = $('#telepon').val();
            // let pesan = $('#pesan').val();
            let pesan = "https://www.google.com";

            $.post('http://152.42.184.255:5001/send-custom', { session: "mysession", to : telepon, text : pesan, tanggal:tgl},
                function(returnedData){
                    Swal.fire({
                        text: 'Pesan berhasil dikirim',
                        icon: 'success'
                    });
                    $('#telepon').val('');
                    $('#pesan').val('');
                }).fail(function(e){
                    Swal.fire({
                        text: 'Pesan gagal dikirim',
                        icon: 'error'
                    });
                    console.log(`Error: ${e}`);
                }).always(function(){
                    // Hide loading toast
                    $('#loading-toast').hide();
                });
        });
    </script>
@endsection
