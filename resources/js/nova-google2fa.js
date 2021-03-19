function checkAutoSubmit(el) {
    if (el.value.length === 6) {
        document.getElementById('authenticate_form').submit();
    }
}

var callback = function(){
    // Handler when the DOM is fully loaded

    var secretInput = document.getElementById('secret');
    if (secretInput) {
        checkAutoSubmit(secretInput);
    }

    var recoverButton = document.getElementById("recoverButton");
    if (recoverButton) {
        recoverButton.addEventListener("click", function (el) {
            document.getElementById('secret_div').style.display = 'none';
            document.getElementById('error_text').style.display = 'none';
            document.getElementById('recover_div').style.display = 'block';
        });
    }

    var printButton = document.getElementById('printButton');
    if (printButton) {
        printButton.addEventListener('click', function(el) {
            window.print();
            return false;
        })
    }
};

if (
    document.readyState === "complete" ||
    (document.readyState !== "loading" && !document.documentElement.doScroll)
) {
    callback();
} else {
    document.addEventListener("DOMContentLoaded", callback);
}
