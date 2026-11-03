
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | TeamMate</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f7fb;
            color: #252b3b;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #111a30;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 15px;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #b8c2d6;
            padding: 13px;
            margin-bottom: 8px;
            border-radius: 8px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #2463eb;
            color: white;
        }

        .main {
            margin-left: 220px;
            padding: 35px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
        }

        .header h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .header p {
            color: #7b8495;
            font-size: 14px;
        }

        .create-btn {
            background: #2463eb;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
        }

        .filter-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e9f0;
            display: flex;
            gap: 15px;
            align-items: end;
            margin-bottom: 30px;
        }

        .filter-group {
            flex: 1;
        }

        .filter-group label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce1ea;
            border-radius: 8px;
            outline: none;
            background: white;
        }

        .filter-btn {
            padding: 12px 22px;
            background: #2463eb;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .section-heading h2 {
            font-size: 18px;
        }

        .section-heading span {
            color: #8992a2;
            font-size: 13px;
        }

        .post-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .post-card {
            background: white;
            border: 1px solid #e5e9f0;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.2s;
        }

        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        .poster {
            height: 155px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 48px;
            position: relative;
        }

        .poster.business {
            background: linear-gradient(135deg, #dceafe, #a7c5f5);
        }

        .poster.tech {
            background: linear-gradient(135deg, #202b4b, #6479a8);
        }

        .poster.design {
            background: linear-gradient(135deg, #fce7d9, #f4b99e);
        }

        .poster.research {
            background: linear-gradient(135deg, #d9f1e6, #91cbb3);
        }

        .status {
            position: absolute;
            top: 12px;
            left: 12px;
            background: white;
            color: #2463eb;
            padding: 6px 9px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
        }

        .post-content {
            padding: 15px;
        }

        .category {
            color: #2463eb;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 7px;
            text-transform: uppercase;
        }

        .post-content h3 {
            font-size: 15px;
            margin-bottom: 9px;
            line-height: 1.4;
        }

        .description {
            color: #788294;
            font-size: 12px;
            line-height: 1.6;
            min-height: 58px;
        }

        .creator {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 14px 0;
            font-size: 11px;
        }

        .avatar {
            width: 29px;
            height: 29px;
            background: #2463eb;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-weight: bold;
        }

        .criteria {
            background: #f5f7fb;
            padding: 10px;
            border-radius: 7px;
            font-size: 11px;
            color: #697386;
            margin-bottom: 13px;
            line-height: 1.5;
        }

        .detail-btn {
            width: 100%;
            padding: 10px;
            background: white;
            border: 1px solid #2463eb;
            color: #2463eb;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .detail-btn:hover {
            background: #2463eb;
            color: white;
        }

        .empty {
            display: none;
            text-align: center;
            padding: 45px;
            color: #788294;
            background: white;
            border-radius: 10px;
            grid-column: 1 / -1;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            padding: 20px;
            z-index: 10;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 100%;
            max-width: 450px;
        }

        .modal-content h2 {
            margin-bottom: 12px;
        }

        .modal-content p {
            color: #697386;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .close-btn {
            background: #2463eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
        }

        @media (max-width: 950px) {
            .post-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                width: 65px;
                padding: 20px 8px;
            }

            .sidebar .logo {
                font-size: 12px;
            }

            .sidebar a {
                font-size: 0;
                text-align: center;
            }

            .main {
                margin-left: 65px;
                padding: 20px 12px;
            }

            .filter-box {
                flex-direction: column;
                align-items: stretch;
            }

            .post-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">💠 TeamMate</div>

        <a href="{{ route('posts.create') }}">＋ Create Post</a>
        <a href="/posts" class="active">⌂ Home</a>
        <a href="#">▣ My Posts</a>
        <a href="#">♧ Requests</a>
        <a href="#">♧ Notifications</a>
        <a href="#">♙ Profile</a>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <div class="header">
            <div>
                <h1>Find Your Competition Teammate</h1>
                <p>Discover active competition teams looking for members.</p>
            </div>

            <a href="{{ route('posts.create') }}" class="create-btn">
                + Create Post
            </a>
        </div>

        <!-- SEARCH AND FILTER -->
        <section class="filter-box">

            <div class="filter-group">
                <label>Keyword Search</label>
                <input
                    type="text"
                    id="keyword"
                    placeholder="Search competitions..."
                >
            </div>

            <div class="filter-group">
                <label>Competition Category</label>
                <select id="category">
                    <option value="">All Categories</option>
                    <option value="Business">Business</option>
                    <option value="Technology">Technology</option>
                    <option value="Design">Design</option>
                    <option value="Research">Research</option>
                </select>
            </div>

            <button class="filter-btn" onclick="filterPosts()">
                Filter
            </button>

        </section>

        <!-- POST LIST -->
        <section>
            <div class="section-heading">
                <h2>Active Recruitment Posts</h2>
                <span id="postCount">5 posts</span>
            </div>

            <div class="post-grid" id="postGrid">

                <!-- POST 1 -->
                <div class="post-card"
                    data-category="Business"
                    data-title="National Business Case Competition 2025">

                    <div class="poster business">
                        <span class="status">✦ Still Looking</span>
                        💼
                    </div>

                    <div class="post-content">
                        <div class="category">Business Case</div>
                        <h3>National Business Case Competition 2025</h3>

                        <p class="description">
                            We are recruiting passionate and analytical
                            teammates for the National Business Case.
                        </p>

                        <div class="creator">
                            <div class="avatar">R</div>
                            <div>Posted by<br><b>Rizky Aditya Pratama</b></div>
                        </div>

                        <div class="criteria">
                            <b>Criteria:</b><br>
                            Minimum GPA 3.2, experience in business case
                            or similar competitions preferred.
                        </div>

                        <button class="detail-btn"
                            onclick="showDetail(0)">View Details</button>
                    </div>
                </div>

                <!-- POST 2 -->
                <div class="post-card"
                    data-category="Technology"
                    data-title="GEMASTIK XVII Software Development Track">

                    <div class="poster tech">
                        <span class="status">✦ Still Looking</span>
                        💻
                    </div>

                    <div class="post-content">
                        <div class="category">Software Development</div>
                        <h3>GEMASTIK XVII — Software Development Track</h3>

                        <p class="description">
                            Seeking a skilled backend developer and
                            a UI/UX designer to join our team.
                        </p>

                        <div class="creator">
                            <div class="avatar">S</div>
                            <div>Posted by<br><b>Sinta Dewi Rahayu</b></div>
                        </div>

                        <div class="criteria">
                            <b>Criteria:</b><br>
                            Backend proficiency in Node.js or Django,
                            database design experience.
                        </div>

                        <button class="detail-btn"
                            onclick="showDetail(1)">View Details</button>
                    </div>
                </div>

                <!-- POST 3 -->
                <div class="post-card"
                    data-category="Design"
                    data-title="UI/UX Design Hackathon Shopee">

                    <div class="poster design">
                        <span class="status">✦ Done Accepting</span>
                        🎨
                    </div>

                    <div class="post-content">
                        <div class="category">UI/UX Design</div>
                        <h3>UI/UX Design Hackathon — Shopee</h3>

                        <p class="description">
                            Looking for one more UI/UX designer to
                            complete our 3-person team.
                        </p>

                        <div class="creator">
                            <div class="avatar">B</div>
                            <div>Posted by<br><b>Bima Mahendra</b></div>
                        </div>

                        <div class="criteria">
                            <b>Criteria:</b><br>
                            Strong Figma skills and portfolio of
                            at least 2 case studies.
                        </div>

                        <button class="detail-btn"
                            onclick="showDetail(2)">View Details</button>
                    </div>
                </div>

                <!-- POST 4 -->
                <div class="post-card"
                    data-category="Technology"
                    data-title="Data Science Competition">

                    <div class="poster tech">
                        <span class="status">✦ Still Looking</span>
                        📊
                    </div>

                    <div class="post-content">
                        <div class="category">Data Science</div>
                        <h3>Data Science Competition</h3>

                        <p class="description">
                            Looking for members interested in data
                            analytics and machine learning.
                        </p>

                        <div class="creator">
                            <div class="avatar">A</div>
                            <div>Posted by<br><b>Andi Saputra</b></div>
                        </div>

                        <div class="criteria">
                            <b>Criteria:</b><br>
                            Basic Python, statistics and data
                            visualization skills.
                        </div>

                        <button class="detail-btn"
                            onclick="showDetail(3)">View Details</button>
                    </div>
                </div>

                <!-- POST 5 -->
                <div class="post-card"
                    data-category="Research"
                    data-title="National Research Competition">

                    <div class="poster research">
                        <span class="status">✦ Still Looking</span>
                        📚
                    </div>

                    <div class="post-content">
                        <div class="category">Research</div>
                        <h3>National Research Competition</h3>

                        <p class="description">
                            We are looking for research enthusiasts
                            to develop innovative scientific ideas.
                        </p>

                        <div class="creator">
                            <div class="avatar">N</div>
                            <div>Posted by<br><b>Nadia Putri</b></div>
                        </div>

                        <div class="criteria">
                            <b>Criteria:</b><br>
                            Interested in scientific writing and
                            research methodology.
                        </div>

                        <button class="detail-btn"
                            onclick="showDetail(4)">View Details</button>
                    </div>
                </div>

                <div class="empty" id="emptyMessage">
                    <h3>No matching posts found</h3>
                    <p>Try another keyword or category.</p>
                </div>

            </div>
        </section>

    </main>

    <!-- DETAIL MODAL -->
    <div class="modal" id="detailModal">
        <div class="modal-content">
            <h2 id="detailTitle"></h2>
            <p id="detailDescription"></p>
            <button class="close-btn" onclick="closeDetail()">Close</button>
        </div>
    </div>

    <script>
        const posts = [
            {
                title: "National Business Case Competition 2025",
                description: "We are recruiting passionate and analytical teammates for the National Business Case. Criteria: Minimum GPA 3.2 and experience in business case competitions."
            },
            {
                title: "GEMASTIK XVII — Software Development Track",
                description: "Seeking a skilled backend developer and a UI/UX designer to join our team."
            },
            {
                title: "UI/UX Design Hackathon — Shopee",
                description: "Looking for one more UI/UX designer to complete our 3-person team."
            },
            {
                title: "Data Science Competition",
                description: "Looking for members interested in data analytics and machine learning."
            },
            {
                title: "National Research Competition",
                description: "We are looking for research enthusiasts to develop innovative scientific ideas."
            }
        ];

        function filterPosts() {
            const keyword = document.getElementById("keyword")
                .value.toLowerCase().trim();

            const category = document.getElementById("category").value;

            const cards = document.querySelectorAll(".post-card");
            let count = 0;

            cards.forEach(card => {
                const title = card.dataset.title.toLowerCase();
                const cardCategory = card.dataset.category;

                const matchesKeyword = title.includes(keyword);
                const matchesCategory =
                    category === "" || cardCategory === category;

                if (matchesKeyword && matchesCategory) {
                    card.style.display = "block";
                    count++;
                } else {
                    card.style.display = "none";
                }
            });

            document.getElementById("postCount").textContent =
                count + " posts";

            document.getElementById("emptyMessage").style.display =
                count === 0 ? "block" : "none";
        }

        function showDetail(index) {
            document.getElementById("detailTitle").textContent =
                posts[index].title;

            document.getElementById("detailDescription").textContent =
                posts[index].description;

            document.getElementById("detailModal").style.display = "flex";
        }

        function closeDetail() {
            document.getElementById("detailModal").style.display = "none";
        }

        document.getElementById("keyword").addEventListener(
            "keydown", function(event) {
                if (event.key === "Enter") {
                    filterPosts();
                }
            }
        );
    </script>

</body>
</html>
