document.addEventListener("DOMContentLoaded", function () {
  // Selectează toate formularele de comentarii din pagină
  const commentForms = document.querySelectorAll(".comment-form");

  commentForms.forEach((commentForm) => {
      const commentTextarea = commentForm.querySelector("textarea");
      const commentsSection = commentForm.closest(".event-card").querySelector(".comments-section");

      commentForm.addEventListener("submit", function (event) {
          event.preventDefault();

          const eventId = commentForm.querySelector('input[name="event_id"]').value;
          const comment = commentTextarea.value.trim();

          if (comment) {
              const formData = new FormData();
              formData.append("event_id", eventId);
              formData.append("comment", comment);

              // Trimite cererea AJAX
              fetch("comment.php", {
                  method: "POST",
                  body: formData,
              })
              .then((response) => response.json())
              .then((data) => {
                  if (data.status === "success") {
                      // Golește câmpul de comentarii
                      commentTextarea.value = "";

                      // Reîncarcă comentariile pentru evenimentul respectiv
                      loadComments(eventId);
                  } else {
                      alert("Error: " + data.message);
                  }
              })
              .catch((error) => {
                  console.error("Error:", error);
                  alert("Error adding comment");
              });
          } else {
              alert("Comment cannot be empty");
          }
      });
  });
});

// Funcție pentru a reîncărca secțiunea de comentarii după adăugare
function loadComments(eventId) {
  fetch(`load_comments.php?event_id=${eventId}`)
  .then((response) => response.text())
  .then((data) => {
      document.querySelector(`#comments-${eventId}`).innerHTML = data;
  })
  .catch((error) => console.error("Error loading comments:", error));
}

