<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - TeamMate</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #172033;
            background: #f5f8fc;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 264px;
            background: #101a30;
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .brand {
            height: 88px;
            padding: 28px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            font-size: 21px;
            font-weight: bold;
            border-bottom: 1px solid #263149;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #2878f9;
            display: grid;
            place-items: center;
            color: white;
            font-size: 20px;
        }

        .sidebar-content {
            padding: 20px 13px;
        }

        .create-button {
            display: block;
            text-align: center;
            text-decoration: none;
            color: white;
            background: #195cf5;
            padding: 13px;
            border-radius: 12px;
            font-weight: bold;
            margin-bottom: 16px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 16px;
            margin: 4px 0;
            border-radius: 12px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 15px;
        }

        .nav-link.active {
            color: white;
            background: #195cf5;
        }

        .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 18px;
        }

        .notification {
            margin-left: auto;
            width: 21px;
            height: 21px;
            border-radius: 50%;
            background: #f43f5e;
            color: white;
            display: grid;
            place-items: center;
            font-size: 12px;
        }

        .sidebar-bottom {
            margin-top: auto;
            border-top: 1px solid #263149;
            padding: 22px 25px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #00a878;
            color: white;
            display: grid;
            place-items: center;
            font-size: 12px;
            font-weight: bold;
        }

        .profile-name {
            color: white;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .profile-id {
            font-size: 12px;
        }

        /* Main content */
        .main {
            flex: 1;
            min-width: 0;
        }

        .page-header {
            background: white;
            padding: 34px 44px 24px;
        }

        .page-header h1 {
            margin: 0 0 7px;
            font-size: 28px;
        }

        .page-header p {
            margin: 0;
            color: #8190a8;
            font-size: 15px;
        }

        .content {
            max-width: 900px;
            margin: 34px auto;
            padding: 0 30px;
        }

        .post-card,
        .status-card {
            background: white;
            border: 1px solid #edf1f6;
            border-radius: 17px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .post-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .post-image {
            width: 84px;
            height: 84px;
            border-radius: 12px;
            object-fit: cover;
            background: #e5eaf1;
            flex-shrink: 0;
        }

        .post-category {
            color: #2563eb;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .post-title {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .post-date {
            color: #94a3b8;
            font-size: 13px;
        }

        .status-card h2 {
            font-size: 15px;
            margin: 4px 0 18px;
        }

        .status-option {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px;
            border: 2px solid #e5ebf4;
            border-radius: 13px;
            margin-bottom: 12px;
            cursor: pointer;
        }

        .status-option.selected {
            border-color: #4388ff;
            background: #eff6ff;
        }

        .status-option input {
            width: 17px;
            height: 17px;
            accent-color: #1680ff;
            flex-shrink: 0;
        }

        .status-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .status-description {
            color: #8190a8;
            font-size: 13px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 22px;
        }

        .button {
            padding: 13px 24px;
            border-radius: 12px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
        }

        .button.primary {
            background: #195cf5;
            color: white;
            border-color: #195cf5;
            min-width: 148px;
        }

        @media (max-width: 760px) {
            .sidebar {
                width: 76px;
            }

            .brand {
                justify-content: center;
                padding: 20px 8px;
            }

            .brand-name,
            .create-text,
            .nav-text,
            .sidebar-bottom .profile-details,
            .sidebar-bottom .logout-text {
                display: none;
            }

            .sidebar-content {
                padding: 14px 8px;
            }

            .create-button {
                font-size: 20px;
                padding: 12px 0;
            }

            .nav-link {
                justify-content: center;
                padding: 14px 8px;
            }

            .notification {
                display: none;
            }

            .sidebar-bottom {
                padding: 18px 20px;
            }

            .page-header {
                padding: 28px 24px 20px;
            }

            .content {
                padding: 0 16px;
                margin-top: 22px;
            }

            .post-card,
            .status-card {
                padding: 16px;
            }

            .post-image {
                width: 64px;
                height: 64px;
            }

            .post-title {
                font-size: 14px;
            }

            .status-option {
                padding: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="layout">

        <aside class="sidebar">
            <div class="brand">
                <div class="brand-icon">♧</div>
                <span class="brand-name">TeamMate</span>
            </div>

            <div class="sidebar-content">
                <a href="#" class="create-button">＋ <span class="create-text">Create Post</span></a>

                <a href="#" class="nav-link">
                    <span class="nav-icon">⌂</span>
                    <span class="nav-text">Home</span>
                </a>

                <a href="#" class="nav-link active">
                    <span class="nav-icon">▤</span>
                    <span class="nav-text">My Posts</span>
                </a>

                <a href="#" class="nav-link">
                    <span class="nav-icon">♧</span>
                    <span class="nav-text">Requests</span>
                </a>

                <a href="#" class="nav-link">
                    <span class="nav-icon">♧</span>
                    <span class="nav-text">Notifications</span>
                    <span class="notification">1</span>
                </a>

                <a href="#" class="nav-link">
                    <span class="nav-icon">♙</span>
                    <span class="nav-text">Profile</span>
                </a>
            </div>

            <div class="sidebar-bottom">
                <div class="profile">
                    <div class="avatar">RA</div>
                    <div class="profile-details">
                        <div class="profile-name">Rizky Aditya Pratama</div>
                        <div class="profile-id">5026211001</div>
                    </div>
                </div>

                <a href="#" class="nav-link">
                    <span class="nav-icon">⇥</span>
                    <span class="logout-text">Logout</span>
                </a>
            </div>
        </aside>

        <main class="main">
            <header class="page-header">
                <h1>Edit Post</h1>
                <p>Update the recruitment status for your post.</p>
            </header>

            <section class="content">
                <div class="post-card">
                    <div class="post-info">
                        <img
                            class="post-image"
                            src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=200&q=80"
                            alt="Business case competition"
                        >

                        <div>
                            <div class="post-category">Business Case</div>
                            <div class="post-title">National Business Case Competition 2025</div>
                            <div class="post-date">Created 2025-05-10</div>
                        </div>
                    </div>
                </div>

                <form class="status-card" onsubmit="event.preventDefault(); alert('This is a static demo. Status has not been saved.');">
                    <h2>Post Status</h2>

                    <label class="status-option">
                        <input type="radio" name="status" value="looking">
                        <div>
                            <div class="status-name">Still Looking</div>
                            <div class="status-description">
                                Your post remains visible and open for applications.
                            </div>
                        </div>
                    </label>

                    <label class="status-option selected">
                        <input type="radio" name="status" value="done" checked>
                        <div>
                            <div class="status-name">Done Accepting</div>
                            <div class="status-description">
                                Stop receiving new applications while keeping the post visible.
                            </div>
                        </div>
                    </label>

                    <label class="status-option">
                        <input type="radio" name="status" value="found">
                        <div>
                            <div class="status-name">Found a TeamMate</div>
                            <div class="status-description">
                                Mark your team as complete. The post will be archived.
                            </div>
                        </div>
                    </label>

                    <div class="actions">
                        <a href="#" class="button">Cancel</a>
                        <button type="submit" class="button primary">Save Changes</button>
                    </div>
                </form>
            </section>
        </main>

    </div>

    <script>
        const statusOptions = document.querySelectorAll('.status-option');

        statusOptions.forEach(option => {
            option.addEventListener('change', () => {
                statusOptions.forEach(item => item.classList.remove('selected'));
                option.classList.add('selected');
            });
        });
    </script>
</body>
</html>