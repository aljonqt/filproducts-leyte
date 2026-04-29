export function initFormValidation() {

    const declaration = document.getElementById("declarationCheck");
    const contract = document.getElementById("contractCheck");
    const signatureInput = document.getElementById("digitalSignatureInput");
    const submitBtn = document.getElementById("submitBtn");

    if (!submitBtn) return;

    function validate() {

        const isChecked = declaration?.checked && contract?.checked;
        const hasSignature = signatureInput?.value && signatureInput.value.length > 0;

        if (isChecked && hasSignature) {
            submitBtn.disabled = false;
            submitBtn.style.background = "#003366";
            submitBtn.style.cursor = "pointer";
        } else {
            submitBtn.disabled = true;
            submitBtn.style.background = "#999";
            submitBtn.style.cursor = "not-allowed";
        }
    }

    // WATCH CHECKBOXES
    declaration?.addEventListener("change", validate);
    contract?.addEventListener("change", validate);

    // WATCH SIGNATURE (important)
    const observer = new MutationObserver(validate);
    observer.observe(signatureInput, { attributes: true, attributeFilter: ['value'] });

    validate(); // initial check
}