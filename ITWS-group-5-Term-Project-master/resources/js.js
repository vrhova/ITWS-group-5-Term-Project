/* eslint-disable vars-on-top */
/* eslint-disable no-var */
/* eslint-disable no-unused-vars */
/* eslint-disable yoda */
/* eslint-disable no-undef */
function validate(formObj) {

    if (formObj.total.value === '') {
        alert('Please enter an amount spent');
        formObj.total.focus();
        return false;
    }

    if (formObj.spending_item.value === 'default') {
        alert('Please choose a category');
        formObj.category.focus();
        return false;
    }

    return true;
}

$(document).ready(function() {

    // focus the name field on first load of the page
    $('#total').focus();

    $('.clear').click(function() {
        if (confirm('Are you sure you want to clear this category? (This action cannot be undone.)')) {

            // get the id of the clicked element's row
            var curId = $(this).closest('tr').attr('id');
            // Extract the db id of the actor from the dom id of the clicked element
            var id = curId.substr(curId.indexOf('-') + 1);
            // Build the data to send. 
            var postData = 'id=' + id;
            // we could also format this as json ... jQuery will (by default) 
            // convert it into a query string anyway, e.g. 
            // var postData = { "id" : actorId };

            $.ajax({
                type: 'post',
                url: 'clear.php',
                dataType: 'json',
                data: postData,
                success: function(responseData, status) {
                    if (responseData.errors) {
                        alert(responseData.errno + ' ' + responseData.error);
                    } else {
                        // Uncomment the following line to see the repsonse message from the server
                        // alert(responseData.message);

                        // if a php generated message box is up, hide it:
                        $('.messages').hide();
                        window.location.replace('http://localhost/final/spending.php');
                    }
                },
                error: function(msg) {
                    // there was a problem
                    alert(msg.status + ' ' + msg.statusText);
                }
            });
        }
    });
});