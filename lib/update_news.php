<?php
include '../components/header.html';
include './DB.php';
ALLError();

if (isset($_GET['id'])) {
    $news_id = $_GET['id'];
    
    $sql = "SELECT * FROM media WHERE id='$news_id'";
    $row = Fetch(Query($sql));
    
    $filtered = array(
        'title' => toHTML($row['title']),
        'source' => toHTML($row['source']),
        'date' => toHTML($row['date']),
        'img_id' => toHTML($row['img_id']),
        'content' => toHTML($row['content'])
    );
    $news_content = $filtered['content'];
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
                                <a href="/components/support-news.php" class="active">뉴스관리</a>
                            </li>
            
                            <li class="event-tab">
                                <a href="/popup">이벤트팝업</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content-page">
                <div class="page__nav">
                    <a href="/components/support-news.php">뉴스관리</a><a href="" class="nav-active" onclick="location.reload();"> > 수정하기</a>
                </div>
                <div class="content">
                    <div class="container-fluid">
                        <div class="row pt-0">
                            <div class="col-12">
                                <div class="card-box">
                                    <form class="news__update" action="./post_update_news.php" method="POST" enctype="multipart/form-data">
                                        <header>
                                            <h1 class="home-h1">
                                                뉴스 수정하기
                                            </h1>
                                            <div class="btn__container">
                                                <button type="submit" name="delete" id="delete-news" value="<?=$news_id?>" onclick="return confirm('정말 삭제하시겠습니까?');">삭제하기</button>
                                            </div>
                                        </header>
                                        <!-- POST할 때 id도 함께 전달 -->
                                        <input type="hidden" name="id" value="<?=$_GET['id']?>">
                                        <div class="info">
                                            <div class="input-chunk">
                                                <label for="source">언론사 정보</label>
                                                <input type="text" name="source" id="source" value="<?=$filtered['source']?>">
                                            </div>
                                            <div class="input-chunk">
                                                <label for="press_date">보도 일자</label>
                                                <input type="date" name="press_date" id="press_date" value="<?=$filtered['date']?>">
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="input-chunk">
                                                <label for="thumbnail">썸네일 등록</label>
                                                <div class="thumbnail__wrapper">
                                                    <div class="canvas__wrapper">
                                                        <canvas class="thumbnail-canvas"></canvas>
                                                    </div>
                                                    <div class="descript">
                                                        <p>해당뉴스의 썸네일을 등록하세요.</p>
                                                        <span id="blue">* 용량은 최대 2MB로 첨부가능합니다.</span>
                                                        <input
                                                            type="file"
                                                            id="hidden-image-input"
                                                            name="news-image"
                                                            accept="image/*"
                                                            capture="camera"
                                                            onchange="readInputFile(event)"
                                                            onclick="this.value=null;"
                                                            style="visibility: hidden;"
                                                        />
                                                        <div class="btn__container" id="thumbnail">
                                                            <button id="add-image">사진등록</button>
                                                            <button id="back-to-default">사진삭제</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="input-chunk">
                                                <label for="title">제목</label>
                                                <input type="text" name="title" maxlength="50" value="<?=$filtered['title']?>">
                                            </div>
                                        </div>
                                        <div class="info">
                                            <textarea name="content" id="summernote" cols="30" rows="10"></textarea>
                                        </div>
                                        <div class="submit-btn">
                                            <button type="submit" name="update" id="update-news">수정하기</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/add-news.js?<?=$today?>"></script>
        <script>
            $(document).ready(function() {
                $('#summernote').summernote();
            });
            $('#summernote').summernote({
                height: 300,
                tabsize: 2,
                minHeight: null,
                maxHeight: null,
                focus: false
            });
            $('#summernote').summernote('code', "<?php echo $news_content; ?>");
        </script>
        <script>
            tnCanvas.style.backgroundImage = `url('../assets/uploaded/news/<?=$filtered['img_id']?>')`;
            tnCanvas.style.backgroundSize = 'contain';
            tnCanvas.style.backgroundPosition = 'center center';
        </script>

    </body>
</html>
