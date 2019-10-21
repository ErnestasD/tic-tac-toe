const $ = require('jquery');

require('../css/app.css');

$(document).ready(function(){
    var movesCounter = 1;

    $('.button').click(function() {
        if (movesCounter < 10) {
            if (movesCounter % 2 === 0) {
                $(this).html('<i class="fas fa-times"></i>');
            } else {
                $(this).html('<i class="far fa-circle"></i>');
            }
            movesCounter++;
        }
    });

    $('.button').click(function (e) {
        e.preventDefault();

        fetch("/make-move/"+$(this).attr('data-id')+"/"+$(this).attr('id'));
    })
});
