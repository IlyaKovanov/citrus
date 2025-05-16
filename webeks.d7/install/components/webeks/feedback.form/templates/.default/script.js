$(document).ready(function(){
    $('#feedbackForm').submit(function(){
        let formData = new FormData(document.forms.feedbackForm);
        let request = BX.ajax.runComponentAction('webeks:feedback.form', 'send', {
            mode:'class',
            data: formData
        });

        request.then(function(successResult){
            const res = successResult.data;
            $('.result').html(res.MESSAGE);
            if(res.STATUS == 'success'){
                $('#feedbackForm')[0].reset();
            }
            
        }, function (errorResult) {
            console.log(errorResult);
        });

        return false;
    })
})