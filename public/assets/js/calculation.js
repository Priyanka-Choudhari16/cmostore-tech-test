
document.getElementById('vat-calculator-form').addEventListener('submit', function (event) {
    event.preventDefault(); 

    $("#value").removeClass("error");
    $("#vat_rate").removeClass("error");
    
    // Get form input values
    var value = document.getElementById('value').value;
    var vat_rate = document.getElementById('vat_rate').value;

    // Perform validation checks
    var isValid = true;

    if (value == '') {
        isValid = false;
        $("#value").addClass("error");
    } else if(vat_rate == '') {
        isValid = false;
        $("#vat_rate").addClass("error");
    }

    // If all validations pass, proceed with form submission
    if (isValid) {
        document.getElementById('vat-calculator-form').submit();
    }
});