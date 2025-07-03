// Toggle password visibility
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const toggleConfirmPassword = document.getElementById("toggleCPassword");
const cPasswordInput = document.getElementById("cpassword")

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Toggle icon
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});

// toggleConfirmPassword.addEventListener("click", function(){

//     const type = cPasswordInput.getAttribute("type") === 'password' ? 'text' : 'password';
//     cPasswordInput.setAttribute('type', type);

//     // Toggle icon
//     this.classList.toggle('fa-eye');
//     this.classList.toggle('fa-eye-slash');

// })









