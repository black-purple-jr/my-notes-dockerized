document.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll('input:not([type="hidden"])');

  // --- Floating labels (works for however many/few inputs are on the page) ---
  inputs.forEach(input => {
    const label = document.querySelector(`label[for="${input.id}"]`);
    if (!label) return;

    if (input.value !== "") {
      label.classList.add('focus');
      input.classList.add('focus');
    }

    input.addEventListener('focus', () => {
      label.classList.add('focus');
      input.classList.add('focus');
    });

    input.addEventListener('blur', () => {
      if (input.value === '') {
        label.classList.remove('focus');
        input.classList.remove('focus');
      }
    });

    input.addEventListener('animationstart', (e) => {
      if (e.animationName === 'onAutoFillStart') {
        label.classList.add('focus');
        input.classList.add('focus');
      } else if (e.animationName === 'onAutoFillCancel') {
        if (input.value === '') {
          label.classList.remove('focus');
          input.classList.remove('focus');
        }
      }
    });
  });

  // --- Eye icon show/hide toggle (only wired up if the fields actually exist) ---
  function updateEyeIcons(input, showIcon, hideIcon) {
    if (!input || !showIcon || !hideIcon) return;

    if (input.value === '') {
      showIcon.style.display = 'none';
      hideIcon.style.display = 'none';
    } else if (input.type === 'password') {
      showIcon.style.display = 'block';
      hideIcon.style.display = 'none';
    } else {
      showIcon.style.display = 'none';
      hideIcon.style.display = 'block';
    }
  }

  function wireEyeToggle(inputId, showId, hideId) {
    const input = document.querySelector(`#${inputId}`);
    const showIcon = document.querySelector(`#${showId}`);
    const hideIcon = document.querySelector(`#${hideId}`);

    if (!input || !showIcon || !hideIcon) return;

    updateEyeIcons(input, showIcon, hideIcon);

    input.addEventListener('input', () => {
      updateEyeIcons(input, showIcon, hideIcon);
    });

    showIcon.onclick = () => {
      input.setAttribute("type", "text");
      showIcon.style.display = "none";
      hideIcon.style.display = "block";
    };

    hideIcon.onclick = () => {
      input.setAttribute("type", "password");
      hideIcon.style.display = "none";
      showIcon.style.display = "block";
    };
  }

  wireEyeToggle("passwordInput", "showPassword", "hidePassword");
  wireEyeToggle("passwordConfirmationInput", "showPasswordConfirmation", "hidePasswordConfirmation");
});