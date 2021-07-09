// news input fields
let newsForm = document.querySelector('.news__write');
let press = document.querySelector('input[name="source"]');
let date = document.querySelector('input[name="press_date"]');
let tnCanvas = document.querySelector('.thumbnail-canvas');
let title = document.querySelector('input[name="title"]');
const summernote = document.querySelector('textarea#summernote');
let hiddenContent = document.querySelector('input[name="plaintext"]');

// Buttons
const addImageBtn = document.getElementById('add-image');
const removeImageBtn = document.getElementById('back-to-default');
const addNewsBtn = document.getElementById('add-news');
const hiddenImageInput = document.getElementById('hidden-image-input');

let imageName = '';

addImageBtn.addEventListener('click', (e)=> {
    e.preventDefault();
    hiddenImageInput.click();
});

function readInputFile(event) {
    let reader = new FileReader();

    reader.onload = function (event) {
        const imgSrc = event.target.result;
        tnCanvas.style.backgroundImage = `url('${imgSrc}')`;
    }
    reader.readAsDataURL(event.target.files[0]);
    imageName = event.target.files[0].name;
    console.log(`imageName: ${imageName}`);
}

function verifyInputFields() {
    if (press.value === '') {
        alert('언론사명을 입력해주세요!');
        return false;
    } else if (date.value === '') {
        alert('보도일자를 입력해주세요!');
        return false;
    } else if (imageName === '') {
        alert('썸네일을 등록해주세요!');
        return false;
    } else if (title.value === '') {
        alert('기사명을 입력해주세요!');
        return false;
    } else if (summernote.value === '') {
        alert('기사내용을 입력해주세요!');
        return false;
    }

    // if (summernote.value) {
    //     summernote.value = toCleanText();
    // }
    
    
    return true;
}







