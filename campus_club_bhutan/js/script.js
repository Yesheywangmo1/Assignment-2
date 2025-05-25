document.addEventListener("DOMContentLoaded", function () {
    const questions = document.querySelectorAll(".faq-question");

    questions.forEach(btn => {
        btn.addEventListener("click", () => {
            const answer = btn.nextElementSibling;

            // Collapse other open answers
            document.querySelectorAll(".faq-answer").forEach(el => {
                if (el !== answer) el.style.display = "none";
            });

            // Toggle selected answer
            answer.style.display = (answer.style.display === "block") ? "none" : "block";
        });
    });
});
