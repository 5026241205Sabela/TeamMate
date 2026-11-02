
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Competition Post | TeamMate</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f6fa;
            color: #252b3b;
        }

        .navbar {
            background: #ffffff;
            padding: 20px 8%;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            color: #3157a4;
        }

        .container {
            max-width: 850px;
            margin: 55px auto;
            padding: 0 20px;
        }

        .page-heading {
            margin-bottom: 28px;
        }

        .page-heading h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .page-heading p {
            color: #777f8d;
            line-height: 1.6;
        }

        .post-card {
            background: white;
            padding: 35px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .required {
            color: #e34b4b;
        }

        .field {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #d9dde5;
            border-radius: 8px;
            background: #fafbfc;
            color: #9299a6;
            font-size: 14px;
        }

        .description {
            height: 130px;
            display: flex;
            align-items: flex-start;
        }

        .upload-area {
            border: 2px dashed #d9dde5;
            border-radius: 9px;
            padding: 30px;
            text-align: center;
            color: #8992a2;
            background: #fafbfc;
            font-size: 14px;
        }

        .upload-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-cancel {
            background: #f0f1f4;
            color: #555d6b;
        }

        .btn-publish {
            background: #3157a4;
            color: white;
        }

        @media (max-width: 600px) {
            .container {
                margin: 35px auto;
            }

            .post-card {
                padding: 22px;
            }

            .page-heading h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="logo">TeamMate.</div>
    </nav>

    <main class="container">

        <div class="page-heading">
            <h1>Create Competition Post</h1>
            <p>
                Share your competition opportunity and find
                the perfect teammates to achieve your goals.
            </p>
        </div>

        <section class="post-card">

            <div class="form-group">
                <label>
                    Competition Title <span class="required">*</span>
                </label>
                <div class="field">Enter competition title</div>
            </div>

            <div class="form-group">
                <label>
                    Category <span class="required">*</span>
                </label>
                <div class="field">Select competition category</div>
            </div>

            <div class="form-group">
                <label>
                    Description <span class="required">*</span>
                </label>
                <div class="field description">
                    Describe your competition...
                </div>
            </div>

            <div class="form-group">
                <label>Competition Poster</label>
                <div class="upload-area">
                    <div class="upload-icon">🖼️</div>
                    <p>Upload your competition poster</p>
                    <small>PNG, JPG or JPEG</small>
                </div>
            </div>

            <div class="form-group">
                <label>Team Member Criteria</label>
                <div class="field description">
                    Describe the teammates you are looking for...
                </div>
            </div>

            <div class="button-group">
                <div class="btn btn-cancel">Cancel</div>
                <div class="btn btn-publish">Publish Post</div>
            </div>

        </section>

    </main>

</body>
</html>
