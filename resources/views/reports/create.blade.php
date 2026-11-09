
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Report Form | TeamMate</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #202b40;
        }

        .header {
            background: white;
            padding: 25px 7%;
        }

        .header a {
            color: #8190a8;
            text-decoration: none;
            font-size: 14px;
        }

        .container {
            min-height: 85vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
        }

        .form-card {
            width: 100%;
            max-width: 460px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
            overflow: hidden;
        }

        .form-header {
            padding: 22px 25px;
            border-bottom: 1px solid #edf1f5;
        }

        .form-header h2 {
            margin: 0;
            font-size: 21px;
        }

        .form-body {
            padding: 25px;
        }

        .description {
            color: #8190a8;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .post-title {
            background: #f5f7fb;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 9px;
        }

        select, textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #e0e6ef;
            border-radius: 9px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
            background: white;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        select:focus, textarea:focus {
            outline: none;
            border-color: #2563eb;
        }

        .error {
            color: #dc3545;
            font-size: 12px;
            margin-top: -14px;
            margin-bottom: 15px;
        }

        .success {
            background: #e6f9ec;
            color: #139447;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .actions {
            display: flex;
            gap: 12px;
        }

        button, .cancel {
            flex: 1;
            padding: 13px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
        }

        .cancel {
            color: #65748b;
            background: white;
            border: 1px solid #e0e6ef;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
        }

        button:disabled {
            background: #a8c3fb;
            cursor: not-allowed;
        }

        @media(max-width: 500px) {
            .form-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <a href="{{ route('home') }}">← Back to Home</a>
    </header>

    <main class="container">

        <div class="form-card">

            <div class="form-header">
                <h2>Report Form</h2>
            </div>

            <div class="form-body">

                <p class="description">
                    Help keep TeamMate safe by reporting posts
                    that violate our community guidelines.
                </p>

                <div class="post-title">
                    Reporting post:
                    <strong>{{ $post->title }}</strong>
                </div>

                @if(session('success'))
                    <div class="success">
                        {{ session('success') }}
                    </div>
                @endif

                <form
                    action="{{ route('reports.store', $post->post_id) }}"
                    method="POST"
                >

                    @csrf

                    <label for="reason">Report Reason *</label>

                    <select name="reason" id="reason" required>
                        <option value="">Select a reason...</option>

                        <option value="Spam or misleading"
                            @selected(old('reason') == 'Spam or misleading')>
                            Spam or misleading
                        </option>

                        <option value="Inappropriate content"
                            @selected(old('reason') == 'Inappropriate content')>
                            Inappropriate content
                        </option>

                        <option value="Harassment or bullying"
                            @selected(old('reason') == 'Harassment or bullying')>
                            Harassment or bullying
                        </option>

                        <option value="Scam or fraud"
                            @selected(old('reason') == 'Scam or fraud')>
                            Scam or fraud
                        </option>

                        <option value="Other"
                            @selected(old('reason') == 'Other')>
                            Other
                        </option>
                    </select>

                    @error('reason')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <label for="description">Description *</label>

                    <textarea
                        name="description"
                        id="description"
                        placeholder="Explain why you are reporting this post..."
                        maxlength="2000"
                        required
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <div class="actions">

                        <a href="{{ route('home') }}" class="cancel">
                            Cancel
                        </a>

                        <button type="submit" id="submitBtn" disabled>
                            Submit Report
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </main>

    <script>
        const reason = document.getElementById('reason');
        const description = document.getElementById('description');
        const submitBtn = document.getElementById('submitBtn');

        function checkForm() {
            submitBtn.disabled = !(
                reason.value &&
                description.value.trim()
            );
        }

        reason.addEventListener('change', checkForm);
        description.addEventListener('input', checkForm);

        checkForm();
    </script>

</body>
</html>
