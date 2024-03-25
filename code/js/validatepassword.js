function checkPasswordMatch(e) {
    var password = document.getElementById("new-pass");
    var passwordCheck = document.getElementById("con-pass");

    if (password.value !== passwordCheck.value) {
        makeRed(passwordCheck);
        makeRed(password);
        alert("The password do not match. Please verify your input.");
        e.preventDefault();
    }
}

function isBlank(inputField) {
    return inputField.value=="" || inputField.value==null;
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
    var mainForm = document.getElementById("setting-form");
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
            console.log('checking match');
            checkPasswordMatch(e);
        }
    }
}
