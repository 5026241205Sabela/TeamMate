
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Join Requests | TeamMate</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f9fc;
            color: #202b40;
        }

        /* Header */
        header {
            background: white;
            padding: 25px 7%;
        }

        .back {
            color: #8190a8;
            font-size: 14px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 0;
        }

        .total {
            text-align: right;
        }

        .total strong {
            display: block;
            font-size: 25px;
        }

        .total small {
            color: #8190a8;
        }

        /* Main Content */
        main {
            max-width: 1000px;
            margin: auto;
            padding: 35px 25px;
        }

        h2 {
            font-size: 17px;
            margin-bottom: 18px;
        }

        .count {
            background: #fff0ce;
            color: #bd7a00;
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
        }

        /* Applicant Card */
        .request-card {
            display: flex;
            gap: 18px;
            background: white;
            padding: 25px;
            margin-bottom: 15px;
            border: 1px solid #edf1f5;
            border-radius: 15px;
        }

        .avatar {
            width: 45px;
            height: 45px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #ed0055;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .avatar.blue {
            background: #2563eb;
        }

        .info {
            flex: 1;
            min-width: 0;
        }

        .info strong {
            font-size: 15px;
        }

        .info p {
            color: #7888a0;
            font-size: 13px;
            margin: 8px 0;
            overflow-wrap: anywhere;
        }

        /* Status */
        .status {
            font-size: 12px;
            padding: 7px 12px;
            border-radius: 20px;
            font-weight: bold;
            align-self: flex-start;
        }

        .pending {
            background: #fff7e5;
            color: #c77d00;
        }

        .accepted {
            background: #e6f9ec;
            color: #139447;
        }

        /* Static Buttons */
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn {
            display: inline-block;
            padding: 9px 18px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: bold;
        }

        .btn-decline {
            background: #fff1f1;
            color: #d23c47;
            border: 1px solid #ffcccc;
        }

        .btn-accept {
            background: #08a546;
            color: white;
        }

        .reviewed-title {
            margin-top: 35px;
        }

        @media (max-width: 600px) {
            .request-card {
                flex-wrap: wrap;
                padding: 16px;
            }

            h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="back">&larr; Back</div>

        <div class="header-content">
            <h1>Join Requests</h1>

            <div class="total">
                <strong>3</strong>
                <small>Total applicants</small>
            </div>
        </div>
    </header>

    <main>

        <!-- PENDING REVIEW -->
        <h2>
            <span class="count">2</span>
            Pending Review
        </h2>

        <!-- Applicant 1 -->
        <div class="request-card">

            <div class="avatar">SD</div>

            <div class="info">
                <strong>Sinta Dewi Rahayu</strong>

                <p>
                    sinta@example.test |
                    NIM: 5026211042
                </p>

                <p>
                    📎 CV_Sinta.pdf &nbsp;
                    📅 Applied 2026-11-01
                </p>

                <div class="actions">
                    <span class="btn btn-decline">
                        Decline
                    </span>

                    <span class="btn btn-accept">
                        Accept
                    </span>
                </div>
            </div>

            <span class="status pending">
                ● Pending
            </span>

        </div>

        <!-- Applicant 2 -->
        <div class="request-card">

            <div class="avatar">RP</div>

            <div class="info">
                <strong>Raka Pratama</strong>

                <p>
                    raka@example.test |
                    NIM: 5026211055
                </p>

                <p>
                    📎 CV_Raka.pdf &nbsp;
                    📅 Applied 2026-11-02
                </p>

                <div class="actions">
                    <span class="btn btn-decline">
                        Decline
                    </span>

                    <span class="btn btn-accept">
                        Accept
                    </span>
                </div>
            </div>

            <span class="status pending">
                ● Pending
            </span>

        </div>

        <!-- REVIEWED -->
        <h2 class="reviewed-title">
            Reviewed
        </h2>

        <!-- Applicant 3 -->
        <div class="request-card">

            <div class="avatar blue">AP</div>

            <div class="info">
                <strong>Alya Putri</strong>

                <p>
                    alya@example.test |
                    NIM: 5026211060
                </p>

                <p>
                    📎 CV_Alya.pdf &nbsp;
                    📅 Applied 2026-10-30
                </p>
            </div>

            <span class="status accepted">
                ● Accepted
            </span>

        </div>

    </main>

</body>
</html>
