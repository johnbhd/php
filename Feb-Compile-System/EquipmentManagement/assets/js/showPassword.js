const passwordToggle = document.getElementById('passwordToggle');
const passwordInput = document.getElementById('passwordInput');


passwordToggle.addEventListener('click', () => {
    const isPassword = passwordInput.type === "password"
    
    passwordInput.type = isPassword ? "text" : "password";
    passwordToggle.classList.toggle("fa-eye", !isPassword);
    passwordToggle.classList.toggle("fa-eye-slash", isPassword);
})