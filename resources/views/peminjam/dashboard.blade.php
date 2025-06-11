@extends('layouts.peminjam')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard Peminjam</h1>
    <div class="container mt-5">
        <!-- Tambahan Card untuk Status Peminjaman -->
        <div class="row">
            <!-- Pending Loans Card -->
            <div class="col-md-3 mb-4">
                <a href="{{ url('peminjam/pinjams') }}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="icon mb-3">
                                <i data-feather="clock" class="text-warning" style="width: 50px; height: 50px;"></i>
                            </div>
                            <h5 class="card-title">Pending Loans</h5>
                            <p class="card-text" id="pendingLoans">{{ $pendingLoans }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Approved Loans Card -->
            <div class="col-md-3 mb-4">
                <a href="{{ url('peminjam/pinjams') }}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="icon mb-3">
                                <i data-feather="check-circle" class="text-success" style="width: 50px; height: 50px;"></i>
                            </div>
                            <h5 class="card-title">Approved Loans</h5>
                            <p class="card-text" id="approvedLoans">{{ $approvedLoans }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Rejected Loans Card -->
            <div class="col-md-3 mb-4">
                <a href="{{ url('peminjam/pinjams') }}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="icon mb-3">
                                <i data-feather="x-circle" class="text-danger" style="width: 50px; height: 50px;"></i>
                            </div>
                            <h5 class="card-title">Rejected Loans</h5>
                            <p class="card-text" id="rejectedLoans">{{ $rejectedLoans }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Returned Loans Card -->
            <div class="col-md-3 mb-4">
                <a href="{{ url('peminjam/pinjams') }}" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm hover-effect">
                        <div class="card-body">
                            <div class="icon mb-3">
                                <i data-feather="rotate-cw" class="text-primary" style="width: 50px; height: 50px;"></i>
                            </div>
                            <h5 class="card-title">Returned Loans</h5>
                            <p class="card-text" id="returnedLoans">{{ $returnedLoans }}</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
</div>
    @include('layouts.content')
@endsection
