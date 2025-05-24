function validateEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function validateForm() {
    var email = document.getElementById('email').value;
    if(!validateEmail(email)) {
        alert('Email invalide!');
        return false;
    }
    return true;
}