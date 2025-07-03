<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transactions Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Transaction Report</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Payment ID</th>
                <th>Payer ID</th>
                <th>Payer Email</th>
                <th>Currency</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Mode</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $i => $transaction)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $transaction->payment_id }}</td>
                <td>{{ $transaction->payer_id }}</td>
                <td>{{ $transaction->payer_email }}</td>
                <td>{{ $transaction->currency }}</td>
                <td>{{ number_format($transaction->amount, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</td>
                <td>Paid</td>
                <td>{{ $transaction->mode }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
