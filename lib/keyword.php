<?php
include '../components/header.html';
include './DB.php';
ALLError();
?>


<body>
    <div id="wrapper">
        <!-- TOPBAR -->
        <?php 
            include '../components/top-bar.html';
        ?>
        <!-- LEFT-SIDEBAR -->
        <?php
            include '../components/left-sidebar.html';
        ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row pt-0">
                        <div class="col-12">
                            <div class="card-box">
                                <header>
                                    <h1 class="home-h1">
                                        뉴스 관리
                                    </h1>
                                    <div class="btn__container">
                                        <button onclick="location.href='/news/write'">작성하기</button>
                                    </div>
                                </header>
                                <div class="menu__banner">
                                    <ul role="tablist" class="menu-tab-list">
                                        <li role="representation" class="menu-tab-item active">
                                            <a href="/components/support-news.php" role="tab" data-key="0" aria-selected="true" class="menu-tab">전체</a>
                                        </li>
                                        <li role="representation" class="menu-tab-item">
                                            <a role="tab" data-key="1" aria-selected="false" class="menu-tab">유스비뉴스</a>
                                        </li>
                                        <li role="representation" class="menu-tab-item">
                                            <a role="tab" data-key="2" aria-selected="false" class="menu-tab">보도자료</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="table__filter">
                                <?php

                                    if(isset($_POST['searchbox'])) {
                                        $keyword = $_POST['searchbox'];
                                        
                                        $sql = "SELECT * 
                                                FROM media 
                                                WHERE title LIKE '%$keyword%' or source LIKE '%$keyword%'
                                        ";
                                        $result = Query($sql);
                                        $count = mysqli_num_rows($result);
                                    }
                                ?>
                                    총 <span class="total-row"><?= $count ?></span>개의 게시물
                                    <form action="/lib/keyword.php" method="post">
                                        <input type="text" name="searchbox" id="searchbox">
                                        <button class="search-btn">
                                            <i class="fas fa-search" id="search-icon"></i>
                                        </button>
                                    </form>
                                </div>
                                <table class="news__table">
                                    <thead>
                                        <tr>
                                            <th><div class="checkbox"></div></th>
                                            <th>번호</th>
                                            <th>제목</th>
                                            <th>언론사</th>
                                            <th>보도일자</th>

                                            <?php

                                            while ($row = Fetch($result)) {
                                                $filtered = array(
                                                    'id' => $row['id'],
                                                    'title' => $row['title'],
                                                    'source' => $row['source'],
                                                    'date' => $row['date']
                                                );
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr onClick="location.href='/lib/update_news.php?id=<?=$filtered['id']?>'">
                                            <td>
                                                <a class="checkbox" id="<?=$filtered['id']?>"></a>
                                            </td>
                                            <td><?= $filtered['id'] ?></td>
                                            <td><?= $filtered['title'] ?></td>
                                            <td><?= $filtered['source'] ?></td>
                                            <td><?= $filtered['date'] ?></td>
                                        </tr>
                                    </tbody>
                                    <?php
                                    }
                                    ?>
                                </table>
                                <nav class="table-footer text-center row align-items-center justify-content-center">
                                    <ul class="pagination">
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

        <!-- Vendor js -->
        <script src="/assets/js/vendor.min.js"></script>
        <!-- Modal -->
        <script src="/assets/libs/custombox/custombox.min.js"></script>
        <!-- App js -->
        <script src="/assets/js/app.min.js"></script>
        <script src="/assets/js/auth.js?v=<?=$today?>"></script>
    </div>
</body>




