// Ajax_Install.js
// (C) 2011 AVOIR

var UpdateProgress_deleteprogressfile = false;
var UpdateProgress_response = '';
function UpdateProgress()
{
    ///ajax_progress/
    //UpdateProgress_deleteprogress
    var deleteprogressfile_ =UpdateProgress_deleteprogressfile;
    new Ajax.Request('progress.php', {
        asynchronous: false,
        method:'get',
        parameters: {deleteprogressfile: deleteprogressfile_?'true':'false'},
        onSuccess: function(transport){
            var response = transport.responseText || "no response text";
            UpdateProgress_response = response;
            response = '<pre>' + response + '</pre>';
            $('output').innerHTML = response;
            $('output').scrollTop=$('output').scrollHeight; //-$('output').height
        },
        onFailure: function(){
            var response = 'Something went wrong...';
            UpdateProgress_response = response;
            response = '<pre>' + response + '</pre>';
            $('output').innerHTML += response;
            $('output').scrollTop=$('output').scrollHeight;
        }
    });
}

var timerId = null;
var login_url = null;

function ajax_install(register_url, register_url_params, login_url_)
{
    login_url = login_url_;
    ///ajax_progress/
    new Ajax.Request(register_url, {
        asynchronous: true,
        method:'get',
        parameters: encodeURIComponent(register_url_params),
        onSuccess: function(transport){
            if (timerId != null) {
                window.clearInterval(timerId);
                timerId = null;
            }
            UpdateProgress_deleteprogressfile = true;
            UpdateProgress();
            //var response = transport.responseText || "no response text";
            var s = '<pre>' + UpdateProgress_response + "\nSuccess! " /* + response */ + ' Please wait while you are redirected...' + '</pre>';
            $('output').innerHTML = s;
            $('output').scrollTop=$('output').scrollHeight;
            ///ajax_progress/
            if (login_url !== null) {
                window.location.replace(login_url); //?message='+encodeURI(response)
            }
        },
        onFailure: function(){
            if (timerId != null) {
                window.clearInterval(timerId);
                timerId = null;
            }
            var response = '\nSomething went wrong...';
            //response
            var s = '<pre>' + UpdateProgress_response + response + '</pre>';
            $('output').innerHTML = s; //response
            $('output').scrollTop=$('output').scrollHeight;
        }
    });
    timerId = window.setInterval('UpdateProgress()', 1000);
    return false;
}