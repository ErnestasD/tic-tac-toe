const $ = require('jquery');
require('bootstrap');

require('../css/app.css');

$(document).ready(function(){
    var movesCounter = 1;

    $('.button').click(function(e) {
        $(this).attr("disabled", true);
        
        if (movesCounter % 2 === 0) {
            $(this).html('<i class="far fa-circle"></i>');
        } else {
            $(this).html('<i class="fas fa-times"></i>');
        }
        movesCounter++;

        e.preventDefault();

        fetch("/make-move/"+$(this).attr('data-id')+"/"+$(this).attr('id')).then(function(response){
            response.json().then(function (json){
                if (json == 'Draw') {
                    $('#gameResult').html('Its a draw!');
                    $('#myModal').modal('show');
                } else if (json == 'user' || json == 'CPU') {
                    $('#gameResult').html('Game winner is: ' + json);
                    $('#myModal').modal('show');
                }
            });
        });
    });
});
