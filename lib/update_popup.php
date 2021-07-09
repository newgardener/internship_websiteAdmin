<?php
include '../components/header.html';
include './DB.php';
ALLError();

if (isset($_GET['id'])) {
    $popup_id = $_GET['id'];
    
    $sql = "SELECT * FROM new_popup WHERE id='$popup_id'";
    $row = Fetch(Query($sql));
    
    $filtered = array(
        'title' => toHTML($row['title']),
        'img_id' => toHTML($row['img_id']),
        'add_date' => toHTML($row['add_date']),
        'display_start' => toHTML($row['display_start']),
        'display_end' => toHTML($row['display_end']),
        'hide' => toHTML($row['hide'])
    );
    $filtered['display_start'] = toLocalScale($filtered['display_start']);
    $filtered['display_end'] = toLocalScale($filtered['display_end']);
}
?>

<body>
        <div id="wrapper">
            <!-- TOPBAR -->
            <?php 
                include '../components/top-bar.html';
            ?>
            <!-- LEFT-SIDEBAR -->
            <div class="left-side-menu animate-right">
                <div>
                    <div id="sidebar-menu" class="">
                        <ul class="metismenu mt-3 " id="side-menu">
                            <li class="news-tab">
                                <a href="/components/support-news.php">뉴스관리</a>
                            </li>
            
                            <li class="event-tab">
                                <a href="/popup" class="active">이벤트팝업</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content-page">
                <div class="page__nav">
                    <a href="/popup">이벤트팝업</a><a href="/popup/write" class="nav-active"> > 작성하기</a>
                </div>
                <div class="content">
                    <div class="container-fluid">
                        <div class="row pt-0">
                            <div class="col-12">
                                <div class="card-box">
                                    <form class="popup__update" method="POST" enctype="multipart/form-data" action="./post_update_popup.php">
                                        <header>
                                            <h1 class="home-h1">
                                                팝업 수정하기
                                            </h1>
                                            <div class="btn__container">
                                                <button type="submit" name="delete" id="delete-popup" value="<?=$popup_id?>" onclick="return confirm('정말 삭제하시겠습니까?');">삭제하기</button>
                                            </div>
                                        </header>
                                        <input type="hidden" name="id" value="<?=$popup_id?>">
                                        <div class="info">
                                            <div class="input-chunk">
                                                <label for="title">제목</label>
                                                <input type="text" name="title" id="title-popup" value="<?=$filtered['title']?>">
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="input-chunk">
                                                <label for="display_start">시작일</label>
                                                <input type="datetime-local" name="display_start" id="display_start" value="<?=$filtered['display_start']?>"
                                                       min="2021-04-01T00:00" max="2025-12-31T00:00">
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="input-chunk">
                                                <label for="display_end">종료일</label>
                                                <input type="datetime-local" name="display_end" id="display_end" value="<?=$filtered['display_end']?>"
                                                       min="2021-04-01T00:00" max="2025-12-31T00:00">
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="canvas__wrapper" id="popup">
                                                <span class="popup-close-descript">오늘 하루 보지 않기</span>
                                                <div class="popup-close">
                                                    <i class="far fa-times-circle"></i>
                                                </div>
                                                <canvas class="popup-canvas"></canvas>
                                            </div>
                                            <div class="input-chunk">
                                                <label for="popup-img">이미지 첨부</label>
                                                <input  type="file"
                                                        name="popup-image"
                                                        id="popup-hidden-input"      
                                                        accept="image/*"
                                                        onchange="readInputFile(event)"
                                                        onclick="console.log(event);"
                                                >
                                                <span id="blue">*용량은 최대 3MB로 첨부 가능합니다</span>
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="input-chunk">
                                                <label for="popup-hide">팝업설정</label>
                                                <div class="hide__wrapper">
                                                    <span>팝업창 숨김</span>
                                                    <input type="checkbox" name="hide" class="checkbox">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="submit-btn">
                                            <button type="submit" name="update" id="update-popup">수정하기</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/add-popup.js?<?=$today?>"></script>
        <script>
            popupCanvas.style.backgroundImage = `url('../assets/uploaded/popup/<?=$filtered['img_id']?>')`;
            popupCanvas.style.backgroundSize = 'contain';
            popupCanvas.style.backgroundPosition = 'center center';

            if (<?=$filtered['hide']?>) hide.checked = true;
        </script>

    </body>
</html>