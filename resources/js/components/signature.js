let canvas, ctx, drawing = false;

export function initSignaturePad() {

    canvas = document.getElementById("signatureCanvas");
    if (!canvas) return;

    ctx = canvas.getContext("2d");

    resizeCanvas(); // 🔥 IMPORTANT FIX

    /* ================= DRAW EVENTS ================= */

    canvas.addEventListener("mousedown", startDraw);
    canvas.addEventListener("mousemove", draw);
    canvas.addEventListener("mouseup", endDraw);
    canvas.addEventListener("mouseleave", endDraw);

    canvas.addEventListener("touchstart", startDraw, { passive: false });
    canvas.addEventListener("touchmove", draw, { passive: false });
    canvas.addEventListener("touchend", endDraw);
}

/* ================= FIX: CANVAS SCALING ================= */

function resizeCanvas() {
    const ratio = window.devicePixelRatio || 1;

    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;

    ctx.scale(ratio, ratio);

    ctx.lineWidth = 2;
    ctx.lineCap = "round";
}

/* ================= DRAW FUNCTIONS ================= */

function getPosition(e) {
    const rect = canvas.getBoundingClientRect();

    if (e.touches) {
        return {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top
        };
    }

    return {
        x: e.clientX - rect.left,
        y: e.clientY - rect.top
    };
}

function startDraw(e) {
    e.preventDefault();

    drawing = true;

    const pos = getPosition(e);

    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
}

function draw(e) {
    if (!drawing) return;

    e.preventDefault();

    const pos = getPosition(e);

    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
}

function endDraw() {
    drawing = false;
}

/* ================= MODAL + BUTTON HANDLERS ================= */

export function initSignatureModal() {

    const modal = document.getElementById("signatureModal");

    /* OPEN MODAL */
    document.querySelectorAll("[data-open-signature]").forEach(btn => {
        btn.addEventListener("click", () => {

            modal?.classList.add("active");
            document.body.classList.add("modal-open");

            // 🔥 RE-INIT CANVAS WHEN OPENED (VERY IMPORTANT)
            setTimeout(() => {
                resizeCanvas();
            }, 100);
        });
    });

    /* CLEAR */
    document.querySelectorAll("[data-clear-signature]").forEach(btn => {
        btn.addEventListener("click", () => {
            if (ctx && canvas) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        });
    });

    /* SAVE */
document.querySelectorAll("[data-save-signature]").forEach(btn => {
    btn.addEventListener("click", () => {

        if (!canvas) return;

        const dataURL = canvas.toDataURL("image/png");

        // STORE SIGNATURE
        const input = document.getElementById("digitalSignatureInput");
        if (input) input.value = dataURL;

        // PREVIEW
        const preview = document.getElementById("signaturePreview");
        const placeholder = document.getElementById("signaturePlaceholder");

        if (preview) {
            preview.src = dataURL;
            preview.style.display = "block";
        }

        if (placeholder) {
            placeholder.style.display = "none";
        }

        // CONTRACT SIGNATURE IMAGE
        const contractSig = document.getElementById("contract_signature_img");
        if (contractSig) {
            contractSig.src = dataURL;
            contractSig.style.display = "block";
        }

        // ✅ FIX: SET SUBSCRIBER NAME
        const first = document.querySelector('input[name="first_name"]')?.value || "";
        const middle = document.querySelector('input[name="middle_name"]')?.value || "";
        const last = document.querySelector('input[name="last_name"]')?.value || "";

        const fullName = `${first} ${middle} ${last}`.replace(/\s+/g, ' ').trim();

        const nameEl = document.getElementById("contract_signature_name");

        if (nameEl) {
            nameEl.textContent = fullName || "_________________________";
        }

        // CLOSE MODAL
        modal?.classList.remove("active");
        document.body.classList.remove("modal-open");
    });
});

    /* CLICK OUTSIDE */
    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.remove("active");
                document.body.classList.remove("modal-open");
            }
        });
    }

    /* ESC */
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            modal?.classList.remove("active");
            document.body.classList.remove("modal-open");
        }
    });
}