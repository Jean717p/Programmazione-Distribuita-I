var cell1 = "", cell2 = "";
var isAlive = false;
var msg_box_help = false,msg_box_remove = false,
    msg_box_remove_no = false,msg_box_cancel = false,
    msg_box_cancel_no = false,msg_box_invalid_cell = false,
    msg_box_submit_err = false;

// Check for cookies
/* Function called when the page is loaded */
function onload_handler() {
    // I perform the check unless i'm already on nocookie page (to avoid loops)
    if (!navigator.cookieEnabled) {
        if (window.location.toString().indexOf("nocookie.php") == -1) {
            window.location = "./nocookie.php";
        }

    }
    else {
        // If i am in nocookie.php and cookies are enabled, I go back to index.php
        if (window.location.toString().indexOf("nocookie.php") != -1) {
            window.location = "./index.php";
        }
        else if(window.location.toString().indexOf("moves.php") != -1){
            isAlive = false;
            check_alive();
        }
    }
}

function check_alive(){
    if(isAlive){
        isAlive = false;
        $.ajax({
            type: "POST",
            url:  "./php/functions/isAlive.php",
            success: function(result){
                if(window.location.toString().indexOf("moves.php") != -1){
                    if(result=='-1'){
                        display_errors_rect_insert(result);
                    }
                    else if(result=='0'){
                        setTimeout(function() {
                            check_alive();
                        },60*1000);
                    }
                    else{
                        setTimeout(function() {
                            check_alive();
                        },5*1000);
                    }
                }
            }
        });
    }
    else{
        setTimeout(function() {
            check_alive();
        },20*1000);
    }
}

/* When the document is ready register these functions */
$(document).ready(function () {

    // Remove all errors from login when register is focused, to clean the view
    $('#register_panel').focusin(function () {
        $('#login_panel .alert').remove();
    });
    // Remove all errors from register when login is focused, to clean the view
    $('#login_panel').focusin(function () {
        $('#register_panel .alert').remove();
    });
    // Remove any error message from the input when i focus in
    $('input[name=username_login]').focusin(function () {
        $('#login_panel .alert').remove();
    });
    // Remove any error message from the input when i focus in
    $('input[name=username_register]').focusin(function () {
        $('#register_panel .alert').remove();
    });
    //table
    $('#remove_last_move').click(function () {
        isAlive = true;
        if($('td.bg-success').length==0){
            display_errors_rect_remove('-4');
            return;
        }
        $.ajax({
            type: "POST",
            url:  "./php/functions/remove_rect.php",
            success: function(result){
                if(result.length<7){
                    display_errors_rect_remove(result);
                }
                let splitted1 = result.split(/[_]/);
                let splitted = splitted1[0].split(/-/);
                let x = parseInt(splitted[0]);
                let y = parseInt(splitted[1]);
                splitted = splitted1[1].split(/-/);
                let x0 = parseInt(splitted[0]);
                let y0 = parseInt(splitted[1]);
                let th = $('#gameboard').attr('data-tab-h');
                let tw = $('#gameboard').attr('data-tab-w');
                if(Number.isNaN(x) || Number.isNaN(y)
                    || Number.isNaN(x0) || Number.isNaN(y0)
                    || x0<0 || y0<0 || x0>= tw || y0>=th
                    || x<0 || y<0 || x>= tw || y>=th){
                    display_errors_rect_remove(result);
                    return;
                }
                let x1 = Math.max(x,x0);
                x0 = Math.min(x,x0);
                let y1 = Math.max(y,y0);
                y0 = Math.min(y,y0);
                if(x0===x1){ //vertical
                    for(let i = y0;i<=y1;i++){
                        let str = '#'+i+'-'+x0;
                        $(str).addClass('table-white avaible_td').removeClass('bg-success');
                    }
                    display_errors_rect_remove('0');
                }
                else if(y0===y1){ //horizontal
                    for(let i = x0;i<=x1;i++){
                        let str = '#'+y0+'-'+i;
                        $(str).addClass('table-white avaible_td').removeClass('bg-success');
                    }
                    display_errors_rect_remove('0');
                }
                else{
                    display_errors_rect_remove('-5');
                }
            }
        });
    });

    $('#cancel_move').click(function () {
        isAlive = true;
        if($('.grey').length==0 && $('.red').length==0){
            if(msg_box_cancel){
                return;
            }
            msg_box_cancel = true;
            $('#cancel_move').after(
                "<div id='cancel_rect_move_no' class='alert alert-info'>" +
                "<p class='alert alert-info'>Nothing to clear.</p>" +
                "</div>");
            setTimeout(function() {
                $('#cancel_rect_move_no').remove();
                msg_box_cancel = false;
            },3*1000);
        }
        else{
            clear_grey_from_table();
            if(msg_box_cancel_no){
                return;
            }
            msg_box_cancel_no = true;
            $('#cancel_move').after(
                "<div id='cancel_rect_move' class='alert alert-success'>" +
                "<p class='alert alert-success'>All Cleared!</p>" +
                "</div>");
            setTimeout(function() {
                $('#cancel_rect_move').remove();
                msg_box_cancel_no = false;
            },3*1000);
        }
    });

    $('#submit_move').click(function () {
        isAlive = true;
        let rect_lenght = $('#gameboard').attr('data-rect_lenght');
        if(cell1==="" || cell1.data('state')!='second') {
            display_errors_rect_insert('-10');
            return;
        }
        else if(rect_lenght==1){
            $.ajax({
                type: "POST",
                url:  "./php/functions/insert_rect.php",
                data: {y0: Number(cell1.data('x')), y1: -1, x0: Number(cell1.data('y')), x1: -1},
                success: function(result){
                    if(Number.isNaN(result)){
                        return;
                    }
                    display_errors_rect_insert(result);
                }
            });
        }
        else if(rect_lenght>1 && cell2!=="" && cell2.data('state')=='second'){
            $.ajax({
                type: "POST",
                url:  "./php/functions/insert_rect.php",
                data: {y0: Number(cell1.data('x')), y1: Number(cell2.data('x')), x0: Number(cell1.data('y')), x1: Number(cell2.data('y'))},
                success: function(result){
                    if(Number.isNaN(result)){
                        return;
                    }
                    display_errors_rect_insert(result);
                }
            });
        }
        else{
            display_errors_rect_insert('-10');
        }
    });

    $('#game_help').click(function () {
        isAlive = true;
        if(msg_box_help){
            return;
        }
        display_errors_rect_insert('-12');
    });

    $('#the_game').on('click','td.avaible_td',function () {
    //$('td.avaible_td').click(function () {
        isAlive = true;
        let cell = $(this),
            state = cell.data('state') || 'first';
        let x = cell.attr('id').split(/[-]/)[0];
        let y = cell.attr('id').split(/[-]/)[1];
        let num_items = $('.grey').length + $('.red').length;
        let rect_lenght = $('#gameboard').attr('data-rect_lenght');
        if(rect_lenght==1 && cell1!=="" && x!=cell1.data('x') && y!=cell1.data('y')){
            return;
        }
        if(num_items>=2){
            if(cell.attr('id') !== cell1.attr('id') && cell.attr('id')!== cell2.attr('id')) {
                return;
            }
        }
        switch (state) {
            case 'first':
                if($('.red').length>0){
                    return;
                }
                cell.addClass('grey');
                cell.data('state', 'second');
                cell.data('x',x);
                cell.data('y',y);
                if(num_items===1){ //color the rectangle
                    cell2 = cell;
                    let x0 = cell1.data('x');
                    let y0 = cell1.data('y');
                    if(x === x0){
                        //controlla se tutti bianchi/grigi e colora grigio verticale da min(cell.y,cell1.y) a max(cell.y,cell1.y)
                        let start = Math.min(y0,y);
                        let end = Math.max(y0,y);
                        let diff = (end-start+1);
                        let i;
                        if(diff != rect_lenght){
                            cell2.addClass('red');
                            cell2.removeClass('grey');
                            invalidCellError();
                        }
                        else{
                            for(i = start+1;i<end;i++){
                                let str = '#'+x+'-'+i;
                                if(!$(str).hasClass('avaible_td')){
                                    break;
                                }
                            }
                            if(i !== end){
                                cell2.addClass('red');
                                cell2.removeClass('grey');
                                invalidCellError();
                            }
                            else{
                                for(i = start+1;i<end;i++){
                                    let str = '#'+x+'-'+i;
                                    $(str).addClass('grey');
                                }
                            }
                        }
                    }
                    else if(y===y0){
                        //controlla se tutti bianchi e colora grigio orizzontale da min(cell.x,cell1.x) a max(cell.x,cell1.x)
                        let start = Math.min(x0,x);
                        let end = Math.max(x0,x);
                        let i;
                        let diff = (end-start+1);
                        if(diff != rect_lenght){
                            cell2.addClass('red');
                            cell2.removeClass('grey');
                            invalidCellError();
                        }
                        else{
                            for(i = start+1;i<end;i++){
                                let str = '#'+i+'-'+y;
                                if(!$(str).hasClass('avaible_td')){
                                    break;
                                }
                            }
                            if(i !== end){
                                cell2.addClass('red');
                                cell2.removeClass('grey');
                                invalidCellError();
                            }
                            else{
                                for(i = start+1;i<end;i++){
                                    let str = '#'+i+'-'+y;
                                    $(str).addClass('grey');
                                }
                            }
                        }
                    }
                    else{
                        //not a rectangle
                        cell.removeClass('grey');
                        cell.addClass('red');
                        invalidCellError();
                    }
                }
                else{
                    cell1 = cell;
                }
                break;
            case 'second':
                if(cell.hasClass('red')){
                    cell.removeClass('red');
                }
                else{
                    cell.removeClass('grey');
                }
                if(num_items>2){
                    let x0 = cell1.data('x');
                    let y0 = cell1.data('y');
                    let x1 = cell2.data('x');
                    let y1 = cell2.data('y');
                    if(x1 === x0){
                        let start = Math.min(y0,y1);
                        let end = Math.max(y0,y1);
                        let i;
                        for(i = start+1;i<end;i++){
                            let str = '#'+x1+'-'+i;
                            $(str).removeClass('grey');
                        }
                    }
                    else if(y===y0){
                        //controlla se tutti bianchi e colora grigio orizzontale da min(cell.x,cell1.x) a max(cell.x,cell1.x)
                        let start = Math.min(x0,x1);
                        let end = Math.max(x0,x1);
                        let i;
                        for(i = start+1;i<end;i++){
                            let str = '#'+i+'-'+y1;
                            $(str).removeClass('grey');
                        }
                    }
                }
                if(cell1.data('x')===x && cell1.data('y')===y){
                    cell1 = cell2;
                    cell2 = "";
                }
                else{
                    cell2 = "";
                }
                cell.data('state', 'first');
                break;
            default:
                break;
        }
    });
});

function invalidCellError() {
    if(msg_box_invalid_cell){
        return;
    }
    msg_box_invalid_cell = true;
    $('#gameboard').before(
        "<div id='invalid_cell_error' class='alert alert-danger'>" +
        "<strong class='alert alert-danger'>Error</strong>" +
        "<p class='alert alert-danger'>Invalid cell.</p>" +
        "</div>");
    setTimeout(function() {
        $('#invalid_cell_error').remove();
        msg_box_invalid_cell = false;
    },3*1000);
}

function display_errors_rect_insert(code) {
    let success = false;
    switch (code) {
        case "0":
            $('.grey').removeClass('grey avaible_td table-light').removeData('x y state').addClass('bg-success');
            cell1="";
            cell2="";
            $('#submit_move').after(
                "<div id='success_rect_sub' class='alert alert-success'>" +
                "<strong class='alert alert-success'>Success!</strong>" +
                "<p class='alert alert-success'>Rectangle successfully positioned.</p>" +
                "</div>");
            setTimeout(function() {
                $('#success_rect_sub').remove();
            },3*1000);
            success = true;
            break;
        case "-1":
            $('#game_situation').after(
                "<div id='error_rect_sub' class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>Session expired. Please login again.</p>" +
                "</div>");
            setTimeout(function() {
                $('#error_rect_sub').remove();
                window.location.replace('./index.php');
            },3*1000);
            break;
        case "-3":
            $('#submit_move').after(
                "<div id='error_rect_sub' class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>Failed submisssion.</p>" +
                "</div>");
            setTimeout(function() {
                $('#error_rect_sub').remove();
            },3*1000);
            break;
        case "-5":
        case "-6":
        case "-7":
        case "-8":
        case "-9":
        case "-10":
            if(msg_box_submit_err){
                break;
            }
            msg_box_submit_err = true;
            $('#submit_move').after(
                "<div id='error_rect_sub' class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>Not a valid rectangle.</p>" +
                "</div>");
            setTimeout(function() {
                $('#error_rect_sub').remove();
                msg_box_submit_err = false;
            },3*1000);
            clear_grey_from_table();
            break;
        case "-11":
            $('#submit_move').after(
                "<div id='error_rect_sub' class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>Rectangle doesn't fit with game's rules.</p>" +
                "</div>");
            clear_grey_from_table();
            $('#game_table').remove();
            $('#the_game').load('./php/fragments/table.php?ajax=true');
            setTimeout(function() {
                $('#error_rect_sub').remove();
            },3*1000);
            break;
        case '-12':
            msg_box_help = true;
            $('#game_help').after(
                "<div id='guide_rect_sub' class='alert alert-info'>" +
                "<strong class='alert alert-info'>Rules</strong>" +
                "<p class='alert alert-info'>Rectangles lenght: "+Number($('#gameboard').attr('data-rect_lenght'))+
                "<br>One white space between each rectangle, in every direction."+
                "</p>" +
                "</div>");
            setTimeout(function() {
                $('#guide_rect_sub').remove();
                msg_box_help = false;
            },10*1000);
            break;
    }
    return success;
}

function display_errors_rect_remove(code) {
    let success = false;
    switch (code) {
        case "0":
            if(msg_box_remove){
                break;
            }
            msg_box_remove = true;
            $('#remove_last_move').after(
                "<div id='success_rect_remove' class='alert alert-success'>" +
                "<strong class='alert alert-success'>Success!</strong>" +
                "<p class='alert alert-success'>Last inserted rectangle successfully removed.</p>" +
                "</div>");
            setTimeout(function() {
                $('#success_rect_remove').remove();
                msg_box_remove = false;
            },3*1000);
            success = true;
            break;
        case '-1':
            display_errors_rect_insert('-1');
            break;
        case '-3':
            $('#remove_last_move').after(
                "<div id='error_rect_remove' class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>Failed, corrupted session.</p>" +
                "</div>");
            setTimeout(function() {
                $('#error_rect_remove').remove();
            },3*1000);
            break;
        case '-4':
            if(msg_box_remove_no){
                break;
            }
            msg_box_remove_no = true;
            $('#remove_last_move').after(
                "<div id='error_rect_remove_no' class='alert alert-info'>" +
                "<strong class='alert alert-info'>No more moves.</strong>" +
                "<p class='alert alert-info'>Nothing to delete. Try playing a move first.</p>" +
                "</div>");
            setTimeout(function() {
                $('#error_rect_remove_no').remove();
                msg_box_remove_no = false;
            },3*1000);
            break;
        case "-6":
        case "-7":
        case "-8":
        case "-9":
        case "-10":
        case '-11':
            $('#remove_last_move').after(
                "<div id='error_rect_remove' class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>Removal failed.</p>" +
                "</div>");
            clear_grey_from_table();
            $('#game_table').remove();
            $('#the_game').load('./php/fragments/table.php?ajax=true');
            setTimeout(function() {
                $('#error_rect_remove').remove();
            },3*1000);
            break;
    }
    return success;
}

function clear_grey_from_table() {
    cell1="";
    cell2="";
    $('.grey').removeClass('grey').removeData('x y state');
    $('.red').removeClass('red').removeData('x y state');
}

function validateEmail(email) {
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    let re_less_strong = /^\S+@\S+[.][\w\d]+$/;
    return re_less_strong.test(String(email).toLowerCase());
}

function validate_login() {
    $('#login_panel .alert').remove();
    let $username = $('input[name=username_login]').val();
    let $pass = $('#password_login').val();
    let $psw_regex = /^(?=.*[^A-Za-z0-9])(.{3,})*$/;
    if ($pass.match($psw_regex)) {
        if(validateEmail($username)){
            return true;
        }
        else{
            $('#login').prepend(
                "<div class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>Username is not a valid email address.</p>" +
                "</div>");
            return false;
        }
    } else {
        $('#login').prepend(
            "<div class='alert alert-danger'>" +
            "<strong class='alert alert-danger'>Error</strong>" +
            "<p class='alert alert-danger'>The password lenght must be at least 3 and must contain at least one non alphanumeric character.</p>" +
            "</div>");
        return false;
    }
}

function validate_register() {
    $('#register_panel .alert').remove();
    let $username = $('input[name=username_register]').val();
    let $pass = $('#password_register').val();
    let $repeat = $('#password_register_confirm').val();
    let $psw_regex = /^(?=.*[^A-Za-z0-9])(.{3,})*$/;
    if ($pass === $repeat) {
        if ($pass.match($psw_regex)) {
            if(validateEmail($username)){
                return true;
            }
            else{
                $('#register').prepend(
                    "<div class='alert alert-danger'>" +
                    "<strong class='alert alert-danger'>Error</strong>" +
                    "<p class='alert alert-danger'>Username is not a valid email address.</p>" +
                    "</div>");
                return false;
            }
        } else {
            $('#register').prepend(
                "<div class='alert alert-danger'>" +
                "<strong class='alert alert-danger'>Error</strong>" +
                "<p class='alert alert-danger'>The password lenght must be at least 3 and must contain at least one non alphanumeric character.</p>" +
                "</div>");
            return false;
        }
    } else {
        $('#register').prepend(
            "<div class='alert alert-danger'>" +
            "<strong class='alert alert-danger'>Error</strong>" +
            "<p class='alert alert-danger'>The passwords you entered do not match</p>" +
            "</div>");
        return false;
    }
}
