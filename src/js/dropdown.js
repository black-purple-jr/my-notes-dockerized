document.addEventListener("DOMContentLoaded", () => {
  const dropdown = document.querySelector(".dropdown-menu");
  const pfp = document.getElementById("profile");
  const backdrop = document.getElementById("backdrop");

  function openDropdown() {
    dropdown.classList.add("open");
    backdrop.classList.add("visible");
  }

  function closeDropdown() {
    dropdown.classList.remove("open");
    backdrop.classList.remove("visible");
  }

  pfp.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdown.classList.contains("open") ? closeDropdown() : openDropdown();
  });

  // click the overlay to close
  backdrop.addEventListener("click", closeDropdown);

  // click anywhere outside the dropdown to close
  document.addEventListener("click", (e) => {
    if (dropdown.classList.contains("open") &&
      !dropdown.contains(e.target) &&
      e.target !== pfp) {
      closeDropdown();
    }
  });
});