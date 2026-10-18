document.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector(".cards-container");

  function noNotesMarkup(message) {
    return `
      <div class="no-notes">
        <svg xmlns="http://www.w3.org/2000/svg" width="65" height="65" fill="currentColor" class="bi bi-wind"
          viewBox="0 0 16 16">
          <path
            d="M12.5 2A2.5 2.5 0 0 0 10 4.5a.5.5 0 0 1-1 0A3.5 3.5 0 1 1 12.5 8H.5a.5.5 0 0 1 0-1h12a2.5 2.5 0 0 0 0-5m-7 1a1 1 0 0 0-1 1 .5.5 0 0 1-1 0 2 2 0 1 1 2 2h-5a.5.5 0 0 1 0-1h5a1 1 0 0 0 0-2M0 9.5A.5.5 0 0 1 .5 9h10.042a3 3 0 1 1-3 3 .5.5 0 0 1 1 0 2 2 0 1 0 2-2H.5a.5.5 0 0 1-.5-.5" />
        </svg>
        <h1>${message}</h1>
      </div>`;
  }

  function noteCardMarkup(note) {
    return `
      <a class="card-link" href="./src/pages/note.php?note-id=${encodeURIComponent(note.note_id)}" id="${encodeURIComponent(note.note_id)}" title="${note.note_title}">
        <div class="card" id="${note.note_id}">
          <div class="card-title">${note.note_title}</div>
          <div class="card-content">${note.note_content}</div>
          <div class="card-date">${note.note_date}</div>
        </div>
      </a>`;
  }

  function renderNotes(notes, searchMode = false) {
    if (!notes || notes.length === 0) {
      container.innerHTML = noNotesMarkup(
        searchMode
          ? "You don't have any note, that has the text you entered."
          : "You don't have any notes, go to the new note button and save your first note."
      );
      return;
    }
    container.innerHTML = notes.map(noteCardMarkup).join("");
  }

  function loadNotes(searchQuery = "") {
    const url = searchQuery
      ? `./api/get_data.php?search=${encodeURIComponent(searchQuery)}`
      : `./api/get_data.php`;

    fetch(url)
      .then(res => {
        if (res.status === 401) {
          window.location.href = "./auth/auth.php";
          return null;
        }
        return res.json();
      })
      .then(data => {
        if (!data) return;
        if (data.error) {
          console.error(data.error);
          return;
        }
        renderNotes(data, Boolean(searchQuery));
      })
      .catch(err => {
        console.error("Failed to load notes:", err);
      });
  }

  // Initial load
  loadNotes();

  // Hook into the search bar if you want live/AJAX search instead of full page reload
  const searchInput = document.getElementById("search-bar-input");
  const searchForm = searchInput ? searchInput.closest("form") : null;

  if (searchForm) {
    searchForm.addEventListener("submit", (e) => {
      e.preventDefault();
      loadNotes(searchInput.value);
    });
  }
});