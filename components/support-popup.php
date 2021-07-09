<?php
  include 'header.html';
  include '../lib/DB.php';
?>

    <body>
        <div id="wrapper">
            <!-- TOPBAR -->
            <?php 
                include 'top-bar.html';
            ?>
            <!-- LEFT-SIDEBAR -->
            <?php
                include 'left-sidebar.html';
            ?>
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row pt-0">
                            <div class="col-12">
                                <div class="card-box">
                                    <header>
                                        <h1 class="home-h1">
                                            이벤트 팝업
                                        </h1>
                                        <div class="btn__container">
                                            <button onclick="location.href='/popup/write'">작성하기</button>
                                        </div>
                                    </header>

                                    <div class="table__filter">
                                        총 <span class="total-row"><?= total_count('new_popup') ?></span>개의 게시물
                                        <input type="text" name="searchbox" id="searchbox">
                                        <span class="search-btn">
                                            <i class="fas fa-search" id="search-icon"></i>
                                        </span>
                                    </div>
                                    <table class="news__table">
                                        <thead>
                                            <tr>
                                                <th><div class="checkbox"></div></th>
                                                <th>번호</th>
                                                <th>제목</th>
                                                <th>팝업기간</th>
                                                <th>등록날짜</th>
                                                
                                                <?php
                                                $total_records = total_count('new_popup');
                                                $per_page_record = 4;
                                                $total_page = ceil($total_records / $per_page_record);

                                                if (isset($_GET["page"])) {
                                                    $curr_page = $_GET["page"];
                                                } else {
                                                    $curr_page = 1;
                                                }

                                                $sql = "SELECT * from new_popup";
                                                $result = Query($sql);
                            
                                                while ($row = Fetch($result)) {
                                                    $filtered = array(
                                                        'id' => $row['id'],
                                                        'title' => $row['title'],
                                                        'add_date' => $row['add_date'],
                                                        'display_start' => $row['display_start'],
                                                        'display_end' => $row['display_end']
                                                    );
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr onClick="location.href='/lib/update_popup.php?id=<?=$filtered['id']?>'">
                                                <td>
                                                    <a class="checkbox" id="<?=$filtered['id']?>"></a>
                                                </td>
                                                <td><?= $filtered['id'] ?></td>
                                                <td><?= $filtered['title'] ?></td>
                                                <td><?= $filtered['display_start'] ?> ~ <?= $filtered['display_end'] ?> </td>
                                                <td><?= $filtered['add_date'] ?></td>
                                            </tr>
                                        </tbody>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <?php 
                                    include 'support-pagination-popup.html';
                                    ?>
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
</html>