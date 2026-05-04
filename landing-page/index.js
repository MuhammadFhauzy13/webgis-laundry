// Map initialization
var map = L.map('map', {
  center: [0.80825206, 127.34063399],
  zoom: 13,
  fullscreenControl: true,
});
map.attributionControl.setPrefix(false);

// Basemaps
var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>',
}).addTo(map);

var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
  attribution: 'Tiles &copy; <a href="https://www.esri.com/">Esri</a>, Maxar, Earthstar Geographics',
});

var baseMaps = {
  OpenStreetMap: osm,
  Satelit: satellite,
};
L.control.layers(baseMaps).addTo(map);

let userLat = null;
let userLng = null;
let userMarker = null;
let userCircle = null;
let routingControl = null;

// ===============================
// GEOLOCATION
// ===============================
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(
    function (pos) {
      userLat = pos.coords.latitude;
      userLng = pos.coords.longitude;
      let accuracy = pos.coords.accuracy;

      if (userMarker) map.removeLayer(userMarker);
      if (userCircle) map.removeLayer(userCircle);

      const redIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
      });

      userMarker = L.marker([userLat, userLng], {
        icon: redIcon,
      }).addTo(map);

      userMarker
        .bindPopup(
          `
      <b>Lokasi Anda</b><br>
      Lat: ${userLat.toFixed(6)}<br>
      Lng: ${userLng.toFixed(6)}<br>
      Akurasi: ${Math.round(accuracy)} m
    `,
        )
        .openPopup();

      userCircle = L.circle([userLat, userLng], {
        radius: accuracy,
        color: 'red',
        fillColor: 'red',
        fillOpacity: 0.15,
      }).addTo(map);

      map.setView([userLat, userLng], 15);
    },
    function (err) {
      console.warn('Gagal ambil lokasi: ' + err.message);
    },
  );
}

// ===============================
// ROUTING
// ===============================
function buatRute(destLat, destLng) {
  if (userLat === null || userLng === null) {
    alert('Lokasi Anda belum tersedia ❌');
    return;
  }

  if (routingControl) {
    map.removeControl(routingControl);
  }

  routingControl = L.Routing.control({
    waypoints: [L.latLng(userLat, userLng), L.latLng(destLat, destLng)],
    routeWhileDragging: false,
    createMarker: () => null,
  }).addTo(map);
}

// ===============================
// MARKER GENERATION
// ===============================
if (typeof laundryData !== 'undefined') {
  laundryData.forEach((l) => {
    const marker = L.marker([l.latitude, l.longitude]).addTo(map);
    const ulasanJSON = JSON.stringify(l.ulasan).replace(/'/g, '&apos;');

    marker.bindPopup(`
      <b>${l.nama_laundry}</b><br><br>
      No Telp: ${l.no_telp}<br>
      Jam: ${l.jam_buka}<br><br>

      <button 
        class="btn btn-sm btn-info openModal"
        data-id="${l.id_laundry}"
        data-nama="${l.nama_laundry}"
        data-khusus="${l.nama_khusus}"
        data-layanan="${l.layanan_text}"
        data-lat="${l.latitude}"
        data-lng="${l.longitude}"
        data-telp="${l.no_telp}"
        data-jam="${l.jam_buka}"
        data-alamat="${l.alamat}"
        data-profile="${l.profile}"
        data-foto="${l.foto_url}"
        data-ulasan='${ulasanJSON}'>
        Detail
      </button>

      <button 
        onclick="buatRute(${l.latitude}, ${l.longitude})" 
        class="btn btn-sm btn-info">
        Rute
      </button>
    `);
  });
}

// ===============================
// MODAL HANDLER
// ===============================
map.on('popupopen', function (e) {
  const btn = e.popup._container.querySelector('.openModal');
  if (btn) {
    btn.addEventListener('click', function () {
      showDetail(this.dataset);
    });
  }
});

function showDetail(data) {
  document.getElementById('m_id_laundry').value = data.id;
  document.getElementById('m_nama').value = data.nama;
  document.getElementById('m_khusus').value = data.khusus;
  document.getElementById('m_layanan').value = data.layanan;
  document.getElementById('m_lat').value = data.lat;
  document.getElementById('m_lng').value = data.lng;
  document.getElementById('m_telp').value = data.telp;
  document.getElementById('m_jam').value = data.jam;
  document.getElementById('m_alamat').value = data.alamat;
  document.getElementById('m_profile').value = data.profile;
  document.getElementById('m_foto').src = data.foto;

  // Load Ulasan
  const ulasan = JSON.parse(data.ulasan || '[]');
  const list = document.getElementById('review-list');
  list.innerHTML = '';

  if (ulasan.length === 0) {
    list.innerHTML = "<p class='text-muted'>Belum ada ulasan.</p>";
  } else {
    ulasan.forEach((u) => {
      let stars = '⭐'.repeat(u.rating);
      let item = document.createElement('div');
      item.className = 'mb-3 p-2 border-bottom';
      item.innerHTML = `
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <strong class="u-nama"></strong> <small class="text-muted">(${u.tanggal})</small>
            <div class="text-warning">${stars}</div>
          </div>
          <div>
            <button class="btn btn-sm btn-outline-primary btn-edit-ulasan" 
              data-id="${u.id_ulasan}" 
              data-nama="" 
              data-rating="${u.rating}" 
              data-komentar="">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-hapus-ulasan" data-id="${u.id_ulasan}">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
        <p class="mt-1 mb-0 u-komentar"></p>
      `;
      item.querySelector('.u-nama').textContent = u.nama_pengguna;
      item.querySelector('.u-komentar').textContent = u.komentar;
      item.querySelector('.btn-edit-ulasan').dataset.nama = u.nama_pengguna;
      item.querySelector('.btn-edit-ulasan').dataset.komentar = u.komentar;
      list.appendChild(item);
    });
  }

  // Add event listeners for Edit/Delete
  document.querySelectorAll('.btn-edit-ulasan').forEach((b) => {
    b.onclick = function () {
      document.getElementById('m_id_ulasan').value = this.dataset.id;
      document.getElementById('m_nama_pengguna').value = this.dataset.nama;
      document.getElementById('m_rating').value = this.dataset.rating;
      document.getElementById('m_komentar').value = this.dataset.komentar;
      document.getElementById('btn-simpan-ulasan').name = 'update_ulasan';
      document.getElementById('btn-simpan-ulasan').innerText = 'Update Ulasan';
      document.getElementById('btn-cancel-edit').classList.remove('d-none');
    };
  });

  document.querySelectorAll('.btn-hapus-ulasan').forEach((b) => {
    b.onclick = function () {
      Swal.fire({
        title: 'Hapus ulasan?',
        text: 'Tindakan ini tidak dapat dibatalkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `proses.php?hapus_ulasan=${this.dataset.id}&id_laundry=${data.id}`;
        }
      });
    };
  });

  let modal = new bootstrap.Modal(document.getElementById('detailModal'));
  modal.show();
}

document.getElementById('btn-cancel-edit').onclick = function () {
  document.getElementById('m_id_ulasan').value = '';
  document.getElementById('m_nama_pengguna').value = '';
  document.getElementById('m_rating').value = '5';
  document.getElementById('m_komentar').value = '';
  document.getElementById('btn-simpan-ulasan').name = 'simpan_ulasan';
  document.getElementById('btn-simpan-ulasan').innerText = 'Kirim Ulasan';
  this.classList.add('d-none');
};

// Auto open modal
window.addEventListener('load', function () {
  const urlParams = new URLSearchParams(window.location.search);
  const idDetail = urlParams.get('id_detail');
  const tab = urlParams.get('tab');
  if (idDetail) {
    const markers = document.querySelectorAll('.openModal');
    markers.forEach((m) => {
      if (m.dataset.id == idDetail) {
        showDetail(m.dataset);
        if (tab === 'ulasan') {
          const ulasanTab = document.querySelector('button[data-bs-target="#tabUlasan"]');
          if (ulasanTab) {
            bootstrap.Tab.getInstance(ulasanTab)?.show() || new bootstrap.Tab(ulasanTab).show();
          }
        }
      }
    });
  }
});
