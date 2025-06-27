document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('registerModal');
    const registerBtn = document.getElementById('registerBtn');
    const loginModal = document.getElementById('loginModal');
    const loginBtn = document.getElementById('loginBtn');

    // Toggle modals
    registerBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        modal.classList.toggle('show');
        loginModal.classList.remove('show'); 
    });

    loginBtn.addEventListener('click', (e) => {
        e.stopPropagation(); 
        loginModal.classList.toggle('show');
        modal.classList.remove('show'); 
    });

    // Close login and signup modals if clicked outside
    document.addEventListener('click', (event) => {
        const clickedInsideRegister = modal.contains(event.target) || registerBtn.contains(event.target);
        const clickedInsideLogin = loginModal.contains(event.target) || loginBtn.contains(event.target);

        if (!clickedInsideRegister) {
            modal.classList.remove('show');
        }

        if (!clickedInsideLogin) {
            loginModal.classList.remove('show');
        }
    });
    

    const buttons = document.querySelectorAll('.questions_cont button');
    const answers = document.querySelectorAll('.questions_txt p');

    buttons.forEach((btn, index) => {
        btn.addEventListener('click', () => {
        answers.forEach((ans, i) => {
            if (i === index) {
                ans.classList.toggle('active');
            } else {
                ans.classList.remove('active');
            }
        });
        });
    });

});
