document.addEventListener("DOMContentLoaded", () => {
  const body = document.body;

  const newNoteButtonDesktop = document.querySelector("header div button");
  const newNote = document.querySelector(".new-note");
  const newNoteTitle = document.querySelector("input#note-title")
  const newNoteContent = document.querySelector("textarea#note-content")
  const cancelButton = document.querySelector(".cancel-btn");
  const saveButton = document.querySelector("button.save-btn");

  // const noteTitleInput = document.querySelector("form #note-title");
  // const noteContentInput = document.querySelector("form #note-content");

  const buttonToTop = document.querySelector("#top-btn");

  // const mobileNavbar = document.querySelector(".mobile-navbar");

  const searchInput = document.querySelector("header .search-bar input");
  const searchBar = document.querySelector(".search-bar");
  const searchIcon = document.querySelector(".search-bar svg");

  const backdrop = document.querySelector("#backdrop");

  searchInput.addEventListener("focus", () => {
    searchBar.classList.add("focus");
    searchIcon.classList.add("focus");
    document.title = "Search - My Notes";
  });

  searchInput.addEventListener("blur", () => {
    searchBar.classList.remove("focus");
    searchIcon.classList.remove("focus");
    document.title = "Home - My Notes";
  });

  let newNoteIsOpen = false;

  function openNewNote() {
    newNote.classList.remove("closed");
    backdrop.classList.add("visible");
    newNoteIsOpen = true;
    document.title = "New Note - My Notes";
    body.classList.add("no-scroll");
  }

  function closeNewNote() {
    newNote.classList.add("closed");
    backdrop.classList.remove("visible");
    newNoteIsOpen = false;
    document.title = "Home - My Notes";
    body.classList.remove("no-scroll");
    // noteTitleInput.value = "";
    // noteContentInput.value = "";
  }

  newNoteButtonDesktop.onclick = openNewNote;
  // newNoteButtonMobile.onclick = openNewNote;
  cancelButton.onclick = closeNewNote;
  backdrop.onclick = closeNewNote;

  if (window.innerWidth <= 535) {
    newNote.style.height = `${window.innerHeight}px`;
    newNote.style.width = `${window.innerWidth}px`;
  }

  // if (window.innerWidth <= 700) {
  //   mobileNavbar.style.width = `${window.innerWidth}px`;
  // }

  document.addEventListener("scroll", () => {
    if (window.pageYOffset > 250) {
      buttonToTop.classList.add("visible");
      buttonToTop.classList.remove("hidden");
    } else {
      buttonToTop.classList.add("hidden");
      buttonToTop.classList.remove("visible");
    }
  });

  // const mobileMenu = document.querySelector(".mobile-options");
  // const mobileMenuTrigger = document.querySelector(".menu svg");

  // if (window.pageXOffset <= 700) {
  //   mobileMenu.style.width = `${window.innerWidth}px`;
  // }

  // let menuIsOpen = false;

  // mobileMenu.style.bottom = `-${window.innerHeight}px`;
  // mobileMenuTrigger.onclick = () => {
  //   if (mobileMenu.style.bottom == `-${window.innerHeight}px`) {
  //     mobileMenu.style.bottom = `0px`;
  //     menuIsOpen = true;
  //   } else {
  //     mobileMenu.style.bottom = `-${window.innerHeight}px`;
  //     menuIsOpen = false;
  //   }
  // };

  // document.addEventListener("click", (event) => {
  //   if (
  //     menuIsOpen &&
  //     !mobileMenu.contains(event.target) &&
  //     !mobileMenuTrigger.contains(event.target)
  //   ) {
  //     mobileMenu.style.bottom = `-${window.innerHeight}px`;
  //     menuIsOpen = false;
  //   }
  // });
});