document.addEventListener("DOMContentLoaded", () => {
  const emailInputLogin = document.querySelector('#login-email-input');
  const emailLabelLogin = document.querySelector('label[for="login-email-input"]');

  const passwordInputLogin = document.querySelector('input[type="password"]');
  const passwordLabelLogin = document.querySelector('label[for="passwd-input"]');
  const showPasswordLogin = document.getElementById('show-password-login');
  const hidePasswordLogin = document.getElementById('hide-password-login');

  const usernameInputRegister = document.getElementById('username-input-register');
  const usernameLabelRegister = document.querySelector('label[for="username-input-register"]');

  const emailInputRegister = document.querySelector('#email-input-register');
  const emailLabelRegister = document.querySelector('label[for="email-input-register"]');

  const passwordInputRegister = document.querySelector('#passwd-input-register');
  const passwordLabelRegister = document.querySelector('label[for="passwd-input-register"]');
  const showPasswordRegister = document.getElementById('show-password-register');
  const hidePasswordRegister = document.getElementById('hide-password-register');

  const passwordConfInputRegister = document.querySelector('#passwd-conf-input-register');
  const passwordConfLabelRegister = document.querySelector('label[for="passwd-conf-input-register"]');
  const showPasswordConfRegister = document.getElementById('show-password-conf-register');
  const hidePasswordConfRegister = document.getElementById('hide-password-conf-register');

  const container = document.querySelector(".container");
  const loginForm = document.querySelector(".login");
  const registerForm = document.querySelector(".register");
  const registerButton = document.querySelector(".register-btn");
  const loginButton = document.querySelector(".login-btn");
  
  loginForm.addEventListener("keydown", event => {
    if (event.key === "Enter") event.preventDefault();
  })

  registerForm.addEventListener("keydown", event => {
    if (event.key === "Enter") event.preventDefault();
  })

  const login = document.querySelector(".login-button");
  const loginSpinner = login.querySelector(".spinner1");
  const loginText = document.querySelector(".login p");

  login.onclick = () => {
    if (passwordInputLogin.value !== "" && emailInputLogin.value !== ""){
      loginSpinner.style.display = "flex";
      loginText.textContent = "Connecting ...";
      login.style.pointerEvents = "none"
    }
  }

  const register = document.querySelector(".register-button");
  const registerSpinner = register.querySelector(".spinner2");
  const registerText = document.querySelector(".register-button p");

  register.onclick = () => {
    if (passwordConfInputRegister.value !== "" && passwordInputRegister.value !== "" && emailInputRegister.value !== "" && usernameInputRegister.value !== ""){
      registerSpinner.style.display = "flex";
      registerText.textContent = "Registering ...";
      register.style.pointerEvents = "none"
    }
  }

  const savedState = localStorage.getItem("authFormState");
  if (savedState === "register"){
    container.classList.add("active");
    document.title = "Register - My Notes"
  };

  function bindFloatingLabel(input, label) {
    input.addEventListener("focus", () => {
      input.classList.add("focus");
      label.classList.add("focus");
    });

    input.addEventListener("blur", () => {
      if (input.value === "") {
        input.classList.remove("focus");
        label.classList.remove("focus");
      }
    });
  }

  passwordInputLogin.addEventListener("focus", () => {
    passwordInputLogin.classList.add("focus");
    passwordLabelLogin.classList.add('focus');
    showPasswordLogin.setAttribute("stroke", "var(--brand-main-color)");
    hidePasswordLogin.setAttribute("stroke", "var(--brand-main-color)");
  });

  passwordInputLogin.addEventListener("blur", () => {
    if (passwordInputLogin.value === "") {
      passwordInputLogin.classList.remove("focus");
      passwordLabelLogin.classList.remove('focus');
      showPasswordLogin.setAttribute("stroke", "var(--foreground-main-color)");
      hidePasswordLogin.setAttribute("stroke", "var(--foreground-main-color)");
    }
  });

  showPasswordLogin.addEventListener("click", () => {
    passwordInputLogin.setAttribute("type", "text");
    showPasswordLogin.style.display = "none";
    hidePasswordLogin.style.display = "block";
  });

  hidePasswordLogin.addEventListener("click", () => {
    passwordInputLogin.setAttribute("type", "password");
    hidePasswordLogin.style.display = "none";
    showPasswordLogin.style.display = "block";
  });

  passwordInputRegister.addEventListener("focus", () => {
    passwordInputRegister.classList.add("focus");
    passwordLabelRegister.classList.add('focus');
    showPasswordRegister.setAttribute("stroke", "var(--brand-main-color)");
    hidePasswordRegister.setAttribute("stroke", "var(--brand-main-color)");
  });

  passwordInputRegister.addEventListener("blur", () => {
    if (passwordInputRegister.value === "") {
      passwordInputRegister.classList.remove("focus");
      passwordLabelRegister.classList.remove('focus');
      showPasswordRegister.setAttribute("stroke", "var(--foreground-main-color)");
      hidePasswordRegister.setAttribute("stroke", "var(--foreground-main-color)");
    }
  });

  passwordConfInputRegister.addEventListener("focus", () => {
    passwordConfInputRegister.classList.add("focus");
    passwordConfLabelRegister.classList.add('focus');
    showPasswordConfRegister.setAttribute("stroke", "var(--brand-main-color)");
    hidePasswordConfRegister.setAttribute("stroke", "var(--brand-main-color)");
  });

  passwordConfInputRegister.addEventListener("blur", () => {
    if (passwordConfInputRegister.value === "") {
      passwordConfInputRegister.classList.remove("focus");
      passwordConfLabelRegister.classList.remove('focus');
      showPasswordConfRegister.setAttribute("stroke", "var(--foreground-main-color)");
      hidePasswordConfRegister.setAttribute("stroke", "var(--foreground-main-color)");
    }
  });

  showPasswordRegister.addEventListener("click", () => {
    passwordInputRegister.setAttribute("type", "text");
    showPasswordRegister.style.display = "none";
    hidePasswordRegister.style.display = "block";
  });

  hidePasswordRegister.addEventListener("click", () => {
    passwordInputRegister.setAttribute("type", "password");
    hidePasswordRegister.style.display = "none";
    showPasswordRegister.style.display = "block";
  });

  showPasswordConfRegister.addEventListener("click", () => {
    passwordConfInputRegister.setAttribute("type", "text");
    showPasswordConfRegister.style.display = "none";
    hidePasswordConfRegister.style.display = "block";
  });

  hidePasswordConfRegister.addEventListener("click", () => {
    passwordConfInputRegister.setAttribute("type", "password");
    hidePasswordConfRegister.style.display = "none";
    showPasswordConfRegister.style.display = "block";
  });

  bindFloatingLabel(emailInputLogin, emailLabelLogin);
  bindFloatingLabel(emailInputRegister, emailLabelRegister);
  bindFloatingLabel(usernameInputRegister, usernameLabelRegister);
  bindFloatingLabel(passwordInputLogin, passwordLabelLogin);
  bindFloatingLabel(passwordInputRegister, passwordLabelRegister);
  bindFloatingLabel(passwordConfInputRegister, passwordConfLabelRegister);

  function triggerAnimating() {
    container.classList.add("animating");
    clearTimeout(container._animTimeout);
    container._animTimeout = setTimeout(() => {
      container.classList.remove("animating");
    }, 1200);
  }


  registerButton.addEventListener("click", () => {
    triggerAnimating()
    container.classList.add("active");
    localStorage.setItem("authFormState", "register");
    document.title = "Register - My Notes";

    emailInputLogin.value = "";
    passwordInputLogin.value = "";

    emailInputLogin.classList.remove("focus");
    emailLabelLogin.classList.remove("focus");
    passwordInputLogin.classList.remove("focus");
    passwordLabelLogin.classList.remove("focus");

    emailInputLogin.blur();
    passwordInputLogin.blur();

    showPasswordLogin.setAttribute("stroke", "var(--foreground-main-color)");
    hidePasswordLogin.setAttribute("stroke", "var(--foreground-main-color)");
  });

  loginButton.addEventListener("click", () => {
    triggerAnimating()
    container.classList.remove("active");
    localStorage.setItem("authFormState", "login");
    document.title = "Login - My Notes";

    emailInputRegister.value = "";
    passwordInputRegister.value = "";
    usernameInputRegister.value = "";
    passwordConfInputRegister.value = "";

    passwordConfInputRegister.classList.remove("focus");
    passwordConfLabelRegister.classList.remove("focus");
    emailInputRegister.classList.remove("focus");
    emailLabelRegister.classList.remove("focus");
    passwordInputRegister.classList.remove("focus");
    passwordLabelRegister.classList.remove("focus");
    usernameInputRegister.classList.remove("focus");
    usernameLabelRegister.classList.remove("focus");

    emailInputRegister.blur();
    passwordInputRegister.blur();
    usernameInputRegister.blur();
    passwordConfInputRegister.blur();

    showPasswordRegister.setAttribute("stroke", "var(--foreground-main-color)");
    hidePasswordRegister.setAttribute("stroke", "var(--foreground-main-color)");
    showPasswordConfRegister.setAttribute("stroke", "var(--foreground-main-color)");
    hidePasswordConfRegister.setAttribute("stroke", "var(--foreground-main-color)");
  });

  emailInputLogin.addEventListener('animationstart', e => {
    if (e.animationName === 'onAutoFillStart') {
      emailLabelLogin.classList.add('focus');
      emailInputLogin.classList.add('focus');
    } else if (e.animationName === 'onAutoFillCancel') {
      if (emailInputLogin.value === '') {
        emailLabelLogin.classList.remove('focus');
        emailInputLogin.classList.remove('focus');
      }
    }
  });

  passwordInputLogin.addEventListener('animationstart', e => {
    if (e.animationName === 'onAutoFillStart') {
      passwordLabelLogin.classList.add('focus');
      passwordInputLogin.classList.add('focus');
    } else if (e.animationName === 'onAutoFillCancel') {
      if (passwordInputLogin.value === '') {
        passwordLabelLogin.classList.remove('focus');
        passwordInputLogin.classList.remove('focus');
      }
    }
  });

  container.addEventListener("transitionend", (e) => {
    if (
      (e.propertyName === "right" || e.propertyName === "bottom") &&
      e.target.classList.contains("form-box")
    ) {
      container.classList.remove("animating");
    }
  });


})