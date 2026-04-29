import * as signature from '../components/signature';
import * as modal from '../components/modal';
import { initMap, captureMap, getUserLocation } from '../components/map';
import { initUploads } from '../components/upload';

document.addEventListener('DOMContentLoaded', () => {

    console.log("Filbiz page loaded");

    const form = document.querySelector("#filbizForm");
    if (!form) return;

    /* ================= INIT COMPONENTS ================= */
    signature.initSignaturePad();
    signature.initSignatureModal();

    modal.initModals();
    initUploads();

    const map = initMap();

    /* ================= MAP LOCATION BUTTON ================= */
    document.querySelectorAll("[data-get-location]").forEach(btn => {
        btn.addEventListener("click", () => {
            getUserLocation(map);
        });
    });

    /* ================= ENABLE SUBMIT BUTTON ================= */
    const declaration = document.getElementById("declarationCheck");
    const contract = document.getElementById("contractCheck");
    const submitBtn = document.getElementById("submitBtn");

    function toggleSubmit() {
        if (declaration?.checked && contract?.checked) {
            submitBtn.disabled = false;
            submitBtn.style.background = "#003366";
            submitBtn.style.cursor = "pointer";
        } else {
            submitBtn.disabled = true;
            submitBtn.style.background = "#999";
            submitBtn.style.cursor = "not-allowed";
        }
    }

    declaration?.addEventListener("change", toggleSubmit);
    contract?.addEventListener("change", toggleSubmit);

    /* ================= FORM SUBMIT ================= */
    form.addEventListener("submit", function (e) {

        if (!map) return;

        e.preventDefault();

        captureMap(map).then((img) => {
            document.getElementById("mapImage").value = img;
            form.submit();
        });

    });

});