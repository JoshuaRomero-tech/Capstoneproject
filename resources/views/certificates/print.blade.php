<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->type }} - {{ $certificate->resident->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; padding: 1in; color: #333; }
        .certificate {
            border: 3px solid #1b5e20;
            padding: 40px;
            min-height: 8in;
            position: relative;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 5px; left: 5px; right: 5px; bottom: 5px;
            border: 1px solid #4caf50;
        }
        .header { text-align: center; margin-bottom: 30px; }
        .header h3 { font-size: 14px; margin-bottom: 5px; color: #555; }
        .header h1 { font-size: 24px; color: #1b5e20; margin: 10px 0; text-transform: uppercase; letter-spacing: 3px; }
        .header h2 { font-size: 18px; color: #2e7d32; border-bottom: 2px solid #4caf50; display: inline-block; padding-bottom: 5px; }
        .body-text { font-size: 16px; line-height: 2; margin: 30px 20px; text-align: justify; }
        .body-text .name { font-weight: bold; text-decoration: underline; font-size: 18px; }
        .details { margin: 20px; }
        .details table { width: 100%; font-size: 14px; }
        .details table td { padding: 5px 10px; }
        .details table td:first-child { font-weight: bold; width: 40%; }
        .footer { margin-top: 60px; text-align: right; padding-right: 40px; }
        .footer .signatory { border-top: 1px solid #333; display: inline-block; padding-top: 5px; min-width: 250px; text-align: center; }
        .footer .position { font-size: 12px; color: #666; }
        .date-issued { margin-top: 30px; font-size: 14px; }
        .or-number { position: absolute; top: 20px; right: 30px; font-size: 12px; color: #666; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; background: #2e7d32; color: #fff; border: none; border-radius: 5px; cursor: pointer;">
            Print Certificate
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 16px; background: #666; color: #fff; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="certificate">
        @if($certificate->or_number)
        <div class="or-number">OR No.: {{ $certificate->or_number }}</div>
        @endif

        <div class="header">
            <h3>Republic of the Philippines</h3>
            <h3>Province / City / Municipality</h3>
            <h3>BARANGAY ___________</h3>
            <br>
            <h1>Office of the Barangay</h1>
            <br>
            <h2>{{ strtoupper($certificate->type) }}</h2>
        </div>

        <div class="body-text">
            <strong>TO WHOM IT MAY CONCERN:</strong>
            <br><br>

            @if($certificate->type == 'Barangay Clearance')
                This is to certify that <span class="name">{{ strtoupper($certificate->resident->full_name) }}</span>,
                {{ $certificate->resident->age }} years old, {{ $certificate->resident->civil_status }},
                {{ $certificate->resident->nationality }}, and a resident of
                <strong>{{ $certificate->resident->address }}</strong>,
                is known to be of good moral character and has no derogatory record filed in this barangay.
                <br><br>
                This certification is issued upon the request of the above-named person for
                <strong>{{ $certificate->purpose }}</strong> purposes.

            @elseif($certificate->type == 'Certificate of Residency')
                This is to certify that <span class="name">{{ strtoupper($certificate->resident->full_name) }}</span>,
                {{ $certificate->resident->age }} years old, {{ $certificate->resident->civil_status }},
                {{ $certificate->resident->nationality }}, is a bonafide resident of
                <strong>{{ $certificate->resident->address }}</strong>.
                <br><br>
                This certification is issued upon the request of the above-named person for
                <strong>{{ $certificate->purpose }}</strong> purposes.

            @elseif($certificate->type == 'Certificate of Indigency')
                This is to certify that <span class="name">{{ strtoupper($certificate->resident->full_name) }}</span>,
                {{ $certificate->resident->age }} years old, {{ $certificate->resident->civil_status }},
                {{ $certificate->resident->nationality }}, residing at
                <strong>{{ $certificate->resident->address }}</strong>,
                belongs to an indigent family in this barangay.
                <br><br>
                This certification is issued upon the request of the above-named person for
                <strong>{{ $certificate->purpose }}</strong> purposes.

            @elseif($certificate->type == 'Business Clearance')
                This is to certify that <span class="name">{{ strtoupper($certificate->resident->full_name) }}</span>,
                of legal age, {{ $certificate->resident->civil_status }}, {{ $certificate->resident->nationality }},
                and a resident of <strong>{{ $certificate->resident->address }}</strong>,
                has been granted clearance to operate a business within this barangay.
                <br><br>
                This clearance is issued for <strong>{{ $certificate->purpose }}</strong> purposes.

            @else
                This is to certify that <span class="name">{{ strtoupper($certificate->resident->full_name) }}</span>,
                {{ $certificate->resident->age }} years old, {{ $certificate->resident->civil_status }},
                {{ $certificate->resident->nationality }}, is a bonafide resident of
                <strong>{{ $certificate->resident->address }}</strong>.
                <br><br>
                This certification is issued upon the request of the above-named person for
                <strong>{{ $certificate->purpose }}</strong> purposes.
            @endif
        </div>

        <div class="date-issued" style="margin-left: 20px;">
            <p>Issued this <strong>{{ $certificate->date_issued->format('jS') }}</strong> day of
            <strong>{{ $certificate->date_issued->format('F, Y') }}</strong>
            at Barangay _________.</p>
        </div>

        <div class="footer">
            <br><br><br><br>
            <div class="signatory">
                <strong>BARANGAY CAPTAIN</strong>
                <div class="position">Punong Barangay</div>
            </div>
        </div>
    </div>
</body>
</html>
