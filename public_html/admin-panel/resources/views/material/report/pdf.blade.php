<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Material Incoming Report PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #eee; }
        .header {
            text-align: left;
           position: fixed;
           top: 5px;
           width: 100%;
           background-color: transparent;
           border-bottom: 1px solid #ddd;
           z-index: 1000;
           margin-top: -25%;
           display: flex;
           justify-content: space-between;
           padding: 10px;
        }

        .header-logo img {
            max-width: 80px;
            margin-top: 10px;
            height: auto;
        }

        .header-info {
            text-align: right;
            margin-top: -60px;
        }
        .content {
            margin-top: -40px;
            padding: 20px;
        }

        .walkaround-info-box {
            border: 1.5px solid #cacaca;
            background-color: transparent;
            padding: 15px;
            width: 100%;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .signature {
            width: 100px; /* Adjust the size as needed */
            height: auto;
            margin-top: 20px; /* Add some space above the signature */
        }
        @page {
            margin-top: 15%;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-logo">
            @if (!empty($profileImg))
                <img src="{{ $profileImg }}" alt="Logo"/>
            @else
                <p>No Logo available</p>
            @endif
        </div>
        <div class="header-info">
            <h3>Material Incoming Report</h3>
            <p>
                @php
                    $firstDate = $materialreports->date;
                @endphp
                @if($firstDate)
                    Date: {{ \Carbon\Carbon::parse($firstDate)->format('d-m-Y') }}
                @else
                    Date: N/A
                @endif
            </p>
        </div>
    </div>

    <div class="content">
        <div class="walkaround-info-box">
            <h3>Material Report</h3>
            <table>
                <tr>
                    <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($materialreports->date)->format('d-m-Y') }}</td>
                    <td><strong>Location:</strong> {{ $materialreports->location ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Challan Number:</strong> {{ $materialreports->challan_number }}</td>
                    <td><strong>Bill Number:</strong> {{ $materialreports->bill_number }}</td>
                </tr>
                <tr>
                    <td><strong>Vehicle Number:</strong> {{ $materialreports->vehicle_number }}</td>
                    <td><strong>Uploaded By:</strong> {{ $materialreports->user->name ?? 'N/A' }}</td>
                </tr>
                 <tr>
                    <td><strong>Vendor Name:</strong> {{ $materialreports->vendor_name }}</td>
                    
                </tr>
                <tr>
                    <td colspan="2"><strong>Description:</strong> {{ $materialreports->description }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Remark:</strong> {{ $materialreports->remark }}</td>
                </tr>
            </table>




        </div>
    <div class="walkaround-info-box">
        <h4>Material Stock</h4>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materialreports->stocks as $stock)
                    <tr>
                        <td>{{ optional($stock->subCategory->category)->name ?? 'N/A' }}</td>
                        <td>{{ optional($stock->subCategory)->name ?? 'N/A' }}</td>
                        <td>{{ $stock->stock }} {{ $stock->subCategory->attribute->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

    <!-- Add signature image after the table -->
    <div style="text-align: right; margin-top: 20px;">
        @if(!empty($signatureImg))
        <p><strong>Signature:</strong></p>
        <img src="{{ $signatureImg }}" class="signature">
    @else
        <p><strong>Signature:</strong> Not available</p>
    @endif
    </div>
    </div>
</body>
</html>
