export function initUploads() {

    document.querySelectorAll(".upload-box").forEach(box => {

        const input = box.querySelector(".file-input");
        const fileName = box.querySelector(".file-name");

        if (!input || !fileName) return;

        /* ================= CLICK TO OPEN ================= */

        box.addEventListener("click", (e) => {
            // prevent double trigger
            if (e.target !== input) {
                input.click();
            }
        });

        /* ================= FILE SELECT ================= */

        input.addEventListener("change", () => {

            if (input.files && input.files.length > 0) {
                fileName.textContent = input.files[0].name;
                fileName.style.display = "block";
            } else {
                fileName.textContent = "";
                fileName.style.display = "none";
            }

        });

        /* ================= DRAG & DROP ================= */

        box.addEventListener("dragover", (e) => {
            e.preventDefault();
            box.classList.add("dragging");
        });

        box.addEventListener("dragleave", () => {
            box.classList.remove("dragging");
        });

        box.addEventListener("drop", (e) => {
            e.preventDefault();
            box.classList.remove("dragging");

            const files = e.dataTransfer.files;

            if (files && files.length > 0) {

                // 🔥 FIX: use DataTransfer for compatibility
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                input.files = dataTransfer.files;

                fileName.textContent = files[0].name;
                fileName.style.display = "block";
            }
        });

        /* ================= RESET SAME FILE ISSUE ================= */

        input.addEventListener("click", () => {
            // allow reselect same file
            input.value = "";
        });

    });
}