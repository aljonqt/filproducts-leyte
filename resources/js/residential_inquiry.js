import { initSignaturePad, initSignatureModal } from '../components/signature';
import { initModals } from '../components/modal';
import { initMap, captureMap, getUserLocation } from '../components/map';
import { initUploads } from '../components/upload';
import { initFormValidation } from '../components/form';

document.addEventListener('DOMContentLoaded', () => {

    initUploads();
    initFormValidation();

    console.log("Residential JS Loaded");

    const form = document.querySelector("#residentialForm");
    if (!form) return;

    /* ================= INIT COMPONENTS ================= */

    initSignaturePad();
    initSignatureModal();
    initModals();

    const map = initMap();

    /* ================= GPS BUTTON ================= */

    document.querySelectorAll("[data-get-location]").forEach(btn => {
        btn.addEventListener("click", () => {
            getUserLocation(map);
        });
    });

    /* ================= FORM SUBMIT ================= */

    form.addEventListener("submit", function (e) {

        if (!map) return;

        e.preventDefault();

        captureMap(map).then((img) => {
            const mapInput = document.getElementById("mapImage");
            if (mapInput) mapInput.value = img;

            form.submit();
        });

    });

});