export function initModals() {

    const declarationModal = document.getElementById("declarationModal");
    const contractModal = document.getElementById("contractModal");

    /* ================= OPEN HANDLERS ================= */

    document.querySelectorAll("[data-open-declaration]").forEach(btn => {
        btn.addEventListener("click", () => {
            declarationModal?.classList.add("active");
            document.body.classList.add("modal-open");
        });
    });

    document.querySelectorAll("[data-open-contract]").forEach(btn => {
        btn.addEventListener("click", () => {

            // 🔥 KEEP YOUR OLD BEHAVIOR (AUTO-FILL CONTRACT)
            fillContractDetails();

            contractModal?.classList.add("active");
            document.body.classList.add("modal-open");
        });
    });

    /* ================= CLOSE HANDLERS ================= */

    document.querySelectorAll("[data-close-declaration]").forEach(btn => {
        btn.addEventListener("click", () => {
            declarationModal?.classList.remove("active");
            document.body.classList.remove("modal-open");
        });
    });

    document.querySelectorAll("[data-close-contract]").forEach(btn => {
        btn.addEventListener("click", () => {
            contractModal?.classList.remove("active");
            document.body.classList.remove("modal-open");
        });
    });

    /* ================= CLICK OUTSIDE ================= */

    [declarationModal, contractModal].forEach(modal => {
        if (!modal) return;

        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.classList.remove("active");
                document.body.classList.remove("modal-open");
            }
        });
    });

    /* ================= ESC KEY ================= */

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            declarationModal?.classList.remove("active");
            contractModal?.classList.remove("active");
            document.body.classList.remove("modal-open");
        }
    });
}


/* ================= CONTRACT AUTO-FILL LOGIC ================= */

function fillContractDetails() {

    // GET FORM VALUES
    const first = document.querySelector('[name="first_name"]')?.value || '';
    const middle = document.querySelector('[name="middle_name"]')?.value || '';
    const last = document.querySelector('[name="last_name"]')?.value || '';

    const street = document.querySelector('[name="street"]')?.value || '';
    const brgy = document.querySelector('[name="barangay"]')?.value || '';
    const city = document.querySelector('[name="city"]')?.value || '';

    const branch = document.querySelector('[name="branch"]')?.value || '';

    // FORMAT NAME
    const fullName = `${first} ${middle} ${last}`.replace(/\s+/g, ' ').trim();

    // FORMAT ADDRESS
    const fullAddress = `${street}, ${brgy}, ${city}`
        .replace(/,\s*,/g, '')
        .replace(/^,|,$/g, '')
        .trim();

    // DATE
    const now = new Date();
    const day = now.getDate();
    const month = now.toLocaleString('default', { month: 'long' });
    const year = now.getFullYear();

    /* ================= APPLY TO CONTRACT ================= */

    const setText = (id, value, fallback = "") => {
        const el = document.getElementById(id);
        if (el) el.textContent = value || fallback;
    };

    setText("contract_name", fullName);
    setText("contract_address", fullAddress);
    setText("contract_day", day);
    setText("contract_month", month);
    setText("contract_year", year);
    setText("contract_branch", branch || "");

    /* ================= ACKNOWLEDGEMENT ================= */

    const ack = document.getElementById("ack_name");
    if (ack) ack.textContent = fullName;
}