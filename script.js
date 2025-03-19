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
                // Adaugă comentariul nou în secțiunea de comentarii
                const newComment = document.createElement("div");
                newComment.classList.add("comment");
                newComment.innerHTML = `<p><strong>User ${data.user_id}:</strong> ${data.comment}</p>`;
                commentsSection.appendChild(newComment);
  
                // Golește câmpul de comentarii
                commentTextarea.value = "";
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
  