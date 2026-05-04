import html2canvas from 'html2canvas';

let map;

/* ================= INIT MAP ================= */

export function initMap() {

    if (typeof L === "undefined") return null;

    const mapEl = document.getElementById("map");
    if (!mapEl) return null;

    const defaultLat = 11.224676039909644;
    const defaultLng = 125.00259348222679;

    map = L.map('map', {
        zoomControl: true
    }).setView([defaultLat, defaultLng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    // ✅ SET INITIAL COORDINATES
    updateInputs(defaultLat, defaultLng);

    // ✅ UPDATE COORDS WHEN MAP MOVES (DRAG UX)
    map.on("move", () => {
        const center = map.getCenter();
        updateInputs(center.lat, center.lng);
    });

    // ✅ ADD CENTER MARKER (HTML OVERLAY)
    addCenterMarker();

    return map;
}

/* ================= CENTER MARKER ================= */

function addCenterMarker() {

    const mapContainer = document.getElementById("map");

    // remove old if exists
    const existing = document.getElementById("centerMarker");
    if (existing) existing.remove();

    const marker = document.createElement("div");
    marker.id = "centerMarker";

    marker.style.position = "absolute";
    marker.style.top = "50%";
    marker.style.left = "50%";
    marker.style.transform = "translate(-50%, -100%)"; // tip points center
    marker.style.zIndex = "999";

    marker.innerHTML = `
        <div style="
            width: 24px;
            height: 24px;
            background: #e53935;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            box-shadow: 0 3px 8px rgba(0,0,0,0.3);
            position: relative;
        ">
            <div style="
                width: 10px;
                height: 10px;
                background: white;
                border-radius: 50%;
                position: absolute;
                top: 7px;
                left: 7px;
            "></div>
        </div>
    `;

    mapContainer.appendChild(marker);
}

/* ================= UPDATE LAT LNG ================= */

function updateInputs(lat, lng) {
    const latInput = document.getElementById("latitude");
    const lngInput = document.getElementById("longitude");

    if (latInput) latInput.value = lat;
    if (lngInput) lngInput.value = lng;
}

/* ================= AUTO LOCATION ================= */

export function getUserLocation() {

    if (!navigator.geolocation || !map) {
        alert("Geolocation not supported.");
        return;
    }

    navigator.geolocation.getCurrentPosition(

        // ✅ SUCCESS
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            map.setView([lat, lng], 17);
            updateInputs(lat, lng);
        },

        // ❌ ERROR
        (err) => {
            if (err.code === 1) alert("Permission denied.");
            else if (err.code === 2) alert("Location unavailable.");
            else if (err.code === 3) alert("Timeout.");
            else alert("Error getting location.");
        },

        {
            enableHighAccuracy: true,
            timeout: 10000
        }
    );
}

/* ================= CAPTURE MAP ================= */

export function captureMap(mapInstance) {

    return new Promise((resolve) => {

        mapInstance.whenReady(() => {

            setTimeout(() => {

                html2canvas(document.querySelector("#map"), {
                    useCORS: true,
                    scale: 2
                }).then(canvas => {
                    resolve(canvas.toDataURL("image/png"));
                });

            }, 500);

        });

    });
}