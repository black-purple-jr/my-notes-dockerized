document.addEventListener("DOMContentLoaded", () => {
  const input = document.querySelector("input");
  const label = document.querySelector("label");

  if (input.value !== "") {
    label.classList.add('focus');
    input.classList.add('focus');
  }

  if (input.value === "") {
    label.classList.remove('focus');
    input.classList.remove('focus');
  }

  input.addEventListener("focus", () => {
    label.classList.add('focus');
    input.classList.add('focus');
  })

  input.addEventListener('blur', () => {
    if (input.value === '') {
      label.classList.remove('focus');
      input.classList.remove('focus');
    }
  });
})