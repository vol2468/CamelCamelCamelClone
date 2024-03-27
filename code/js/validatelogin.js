function isBlank(inputField) {
    return inputField.value=="";
}

function makeRed(inputDiv){
    inputDiv.style.borderColor="#F6453D";
    inputDiv.style.borderWidth = "3px";
}

function makeClean(inputDiv){
    inputDiv.style.borderColor="black";
    inputDiv.style.borderWidth = "1px";
}

window.onload = function()
{
    var mainForm = document.getElementById("signin-form");
    var requiredInputs = document.querySelectorAll(".required");

    mainForm.onsubmit = function(e) {
        var requiredInputs = document.querySelectorAll(".required");
        var err = false;

        for (var i = 0; i < requiredInputs.length; i++) {
            if( isBlank(requiredInputs[i])) {
                err |= true;
                makeRed(requiredInputs[i]);
            } else {
                makeClean(requiredInputs[i]);
            }
        }
        if (err == true) {
            e.preventDefault();
        } else {
            // console.log('checking match');
            // checkPasswordMatch(e);
        }
    }
}
