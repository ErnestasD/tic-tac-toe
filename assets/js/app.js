const $ = require('jquery');
require('bootstrap');

require('../css/app.css');

$(document).ready(function(){

    $('.button').click(function(e) {
        $('#player-move, #cpu-move').toggleClass("d-none");
        $(this).attr("disabled", true);
        $(this).html('<i class="fas fa-times"></i>');
        
        e.preventDefault();

        // Fetch request'as userio ejimui atlikti
        fetch("/make-move/"+$('#currentGameId').attr('data-id')+"/"+$(this).attr('id'));
        // Fetch request'as patikrinti zaidimo statusui po zaidejo ejimo
        setTimeout(function(){
            fetch("/check-game-status/"+$('#currentGameId').attr('data-id')).then(function(response){
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
        }, 1500);
        // Fetch request'as kompiuteriui atlikti atsakomaji ejima, kuris grazina langeli kuriame atliktas ejimas, pagal ji frontend'e langelyje atvaizduojamas CPU ejimas
        setTimeout(function(){
            fetch("/cpu-make-move/"+$('#currentGameId').attr('data-id')).then(function(response1){
                response1.json().then(function (json){
                    $('#'+json).html('<i class="far fa-circle"></i>');
                    $('#'+json).attr("disabled", true);
                    $('#player-move, #cpu-move').toggleClass("d-none");
                });
            });
        }, 1500);
        // Fetch request'as patikrinti zaidimo statusa po CPU ejimo
        setTimeout(function(){
            fetch("/check-game-status/"+$('#currentGameId').attr('data-id')).then(function(response2){
                response2.json().then(function (json){
                    if (json == 'Draw') {
                        $('#gameResult').html('Its a draw!');
                        $('#myModal').modal('show');
                    } else if (json == 'user' || json == 'CPU') {
                        $('#gameResult').html('Game winner is: ' + json);
                        $('#myModal').modal('show');
                    }
                });
            });
        }, 1500);
    });
});
