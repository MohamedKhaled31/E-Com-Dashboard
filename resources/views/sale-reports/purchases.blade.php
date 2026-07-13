@extends('layouts.app')

@section('title', 'Purchase Report')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <form action="{{ route('reports.purchases') }}" method="GET" class="form-inline">
            <div class="form-group mr-2">
                <select name="month" class="form-control font-weight-bold text-primary">
                    @foreach($monthsDropdown as $value => $label)
                        <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Apply</button>
        </form>

        <a href="{{ route('reports.purchases.export', ['month' => $selectedMonth]) }}" class="btn btn-sm btn-success shadow-sm">
            <i class="fas fa-download fa-sm text-white-50 mr-1"></i> Export Purchases to Excel
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Details for: {{ Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>Day</th>
                            <th>Invoices Count</th>
                            <th>Items Count</th>
                            <th>Total Purchases</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportRows as $row)
                            <tr>
                                <td>Day {{ $row['day'] }}</td>
                                <td>{{ $row['invoices_count'] }}</td>
                                <td>{{ $row['items_count'] }}</td>
                                <td class="font-weight-bold text-primary">${{ number_format($row['total_purchases'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
