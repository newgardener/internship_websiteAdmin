// popup input fields
let title = document.querySelector('input[name="title"]');
let displayStart = document.querySelector('input[name="display_start"]');
let displayEnd = document.querySelector('input[name="display_end"]');
let popupCanvas = document.querySelector('.popup-canvas');
let hide = document.querySelector('input[name="hide"]');

let imageName = '';
const closeDescript = document.querySelector('.popup-close-descript');
const closeIcon = document.querySelector('.popup-close');


function readInputFile(event) {
    let reader = new FileReader();

    reader.onload = function (event) {
        const imgSrc = event.target.result;
        popupCanvas.style.backgroundImage = `url('${imgSrc}')`;
        closeDescript.style.display = 'block';
        closeIcon.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
    imageName = event.target.files[0].name;
    console.log(`imageName: ${imageName}`);
}

function verifyInputFields() {
    if (title.value === '') {
        alert('제목을 입력해주세요!');
        return false;
    } else if (displayStart.value === '') {
        alert('시작일을 입력해주세요!');
        return false;
    } else if (displayEnd.value === '') {
        alert('종료일을 입력해주세요!');
        return false;
    } else if (imageName === '') {
        alert('팝업 이미지를 첨부해주세요!');
        return false;
    } 
    return true;
}