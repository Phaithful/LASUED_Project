document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('registerModal');
    const registerBtn = document.getElementById('registerBtn');
    const loginModal = document.getElementById('loginModal');
    const loginBtn = document.getElementById('loginBtn');

    registerBtn.addEventListener('click', () => {
        modal.classList.toggle('show');
    });

    loginBtn.addEventListener("click", () => {
        loginModal.classList.toggle("show")
    })

    // Close modal if clicked outside
    document.addEventListener('click', function (event) {
        const modal = document.getElementById('registerModal');
        const registerBtn = document.getElementById('registerBtn');
        const loginModal = document.getElementById('loginModal');
        const loginBtn = document.getElementById('loginBtn');
        const isClickInside = modal.contains(event.target);
        const isClickInsideReg = loginModal.contains(event.target);
        const isButton = event.target === registerBtn;
        const isRegButton = event.target === loginBtn;

        if (!isClickInside && !isButton && modal.classList.contains('show')) {
            modal.classList.remove('show');
        }

        if (!isClickInsideReg && !isRegButton && loginModal.classList.contains('show')) {
            loginModal.classList.remove('show');
        }
    });

});








