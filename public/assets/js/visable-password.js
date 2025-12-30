var isPasswordVisible = false;
var isConfirmPasswordVisible = false;
document.getElementById("togglePassword").addEventListener("click", function() {
    var passwordInput = document.getElementById("password");
    isPasswordVisible = !isPasswordVisible; // Toggle the visibility state
    if (isPasswordVisible) {
        passwordInput.setAttribute("type", "text");
        document.getElementById("togglePassword").classList.remove("fa-eye");
        document.getElementById("togglePassword").classList.add("fa-eye-slash");
    } else {
        passwordInput.setAttribute("type", "password");
        document.getElementById("togglePassword").classList.remove("fa-eye-slash");
        document.getElementById("togglePassword").classList.add("fa-eye");
    }
});
document.getElementById("togglePassword2").addEventListener("click", function() {
    var confirm_passwordInput = document.getElementById("confirm_password");
    isConfirmPasswordVisible = !isConfirmPasswordVisible; // Toggle the visibility state
    if (isConfirmPasswordVisible) {
        confirm_passwordInput.setAttribute("type", "text");
        document.getElementById("togglePassword2").classList.remove("fa-eye");
        document.getElementById("togglePassword2").classList.add("fa-eye-slash");
    } else {
        confirm_passwordInput.setAttribute("type", "password");
        document.getElementById("togglePassword2").classList.remove("fa-eye-slash");
        document.getElementById("togglePassword2").classList.add("fa-eye");
    }
});