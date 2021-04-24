let cases;

$.ajax({
    url: "../../backend/prefs/cases.txt",
    success: (data) => {
        cases = data.split(".png");

        cases.pop();

        for (let i = 0; i < cases.length; i++) {

            let img = document.createElement('img');
            let div = document.createElement('div');
            $(div).css('display', 'inline-block');

            $(div).data('caseImg', cases[i]);

            $(div).click(event => {
                selectCaseImage($(event.currentTarget).data('caseImg'));
            });

            $(img).attr('src', '../img/cases/' + cases[i] + '.png');
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

    $('.selectCase').attr('src', '../img/cases/' + caseImgName + '.png');
}