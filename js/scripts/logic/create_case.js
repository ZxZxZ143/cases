let casesImg;
let src;

$.ajax({
    url: "../../prefs/cases.txt",
    success: (data) => {
        casesImg = data.split(".png");

        casesImg.pop();

        for (let i = 0; i < casesImg.length; i++) {

            let img = document.createElement('img');
            let div = document.createElement('div');
            $(div).css('display', 'inline-block');

            $(div).data('caseImg', casesImg[i]);

            $(div).click(event => {
                selectCaseImage($(event.currentTarget).data('caseImg'));
            });

            $(img).attr('src', '../../assets/img/cases/' + casesImg[i] + '.png');
            $(img).addClass('hoverCase');
            $(img).attr('data-dismiss', 'modal');

            $(div).addClass('caseImage');

            $('#caseBody').append(div);
            $(div).append(img);
        }

    },

    error: (error) => {
        console.error('error');
    }
});





function selectCaseImage(caseImgName) {

    $('.plus').addClass('selectCase');

    $('.selectCase').removeClass('plus');

    $('.selectCase').attr('src', '../../assets/img/cases/' + caseImgName + '.png');

    src = caseImgName + '.png';
}