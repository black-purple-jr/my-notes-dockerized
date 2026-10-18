const newNoteTitleInput = document.querySelector("input");
const newNoteContentInput = document.querySelector("textarea");
const submitButton = document.querySelector(".update")

newNoteTitleInput.addEventListener("keydown", e => {
  if (e.key === "Enter"){
    e.preventDefault();
    newNoteContentInput.focus();
    const len = newNoteContentInput.value.length;
    newNoteContentInput.setSelectionRange(len, len);
  }
});