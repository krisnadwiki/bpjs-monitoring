# 📋 Panduan Menambahkan Endpoint Baru

## Struktur Penambahan Endpoint

### 1. 🏥 **Menambahkan Endpoint VCLAIM Baru**

#### A. Update `dashboard_vclaim.php`

**Langkah 1: Tambahkan endpoint ke konfigurasi JavaScript**
```javascript
// Lokasi: Sekitar baris 390-410
const endpoints = {
    production: {
        peserta: "monitoring_controller.php?param=nik&noka=6201052510750001",
        rujukan: "monitoring_controller.php?param=rujukan",
        diagnosa: "monitoring_controller.php?param=diagnosa",
        ppk: "monitoring_controller.php?param=ppk",
        // TAMBAHKAN ENDPOINT BARU DI SINI
        faskes: "monitoring_controller.php?param=faskes",
        spesialist: "monitoring_controller.php?param=spesialist"
    },
    cdn: {
        peserta: "monitoring_controller.php?param=nik&noka=6201052510750001&cdn=1",
        rujukan: "monitoring_controller.php?param=rujukan&cdn=1",
        diagnosa: "monitoring_controller.php?param=diagnosa&cdn=1",
        ppk: "monitoring_controller.php?param=ppk&cdn=1",
        // TAMBAHKAN ENDPOINT CDN BARU DI SINI
        faskes: "monitoring_controller.php?param=faskes&cdn=1",
        spesialist: "monitoring_controller.php?param=spesialist&cdn=1"
    }
};
```

**Langkah 2: Tambahkan status card di HTML**
```html
<!-- Lokasi: Sekitar baris 250-350, tambahkan setelah card PPK -->
<div class="col-md-6">
    <div class="status-card" id="status-faskes">
        <h6><i class="bi bi-building"></i> Get Faskes</h6>
        <div class="status-indicator status-loading">
            <i class="bi bi-hourglass-split"></i> Loading...
        </div>
        <div class="response-time">Response Time: -</div>
        <div class="http-status">HTTP: -</div>
        <div class="last-update">Last Update: Never</div>
    </div>
</div>

<div class="col-md-6">
    <div class="status-card" id="status-spesialist">
        <h6><i class="bi bi-person-heart"></i> Get Spesialist</h6>
        <div class="status-indicator status-loading">
            <i class="bi bi-hourglass-split"></i> Loading...
        </div>
        <div class="response-time">Response Time: -</div>
        <div class="http-status">HTTP: -</div>
        <div class="last-update">Last Update: Never</div>
    </div>
</div>
```

**Langkah 3: Update array services dan chart series**
```javascript
// Lokasi: Sekitar baris 500-520
function runMonitoringCycle() {
    if (!isMonitoring) return;

    const endpointType = document.getElementById('endpointSelect').value;
    const currentEndpoints = endpoints[endpointType];

    // UPDATE ARRAY INI DENGAN ENDPOINT BARU
    const services = ['peserta', 'rujukan', 'diagnosa', 'ppk', 'faskes', 'spesialist'];
    
    // ... rest of the code
}

// Lokasi: Sekitar baris 380-390, update chart series
series: [{
    name: 'Peserta',
    data: [],
    color: '#28a745'
}, {
    name: 'Rujukan',
    data: [],
    color: '#007bff'
}, {
    name: 'Diagnosa',
    data: [],
    color: '#ffc107'
}, {
    name: 'PPK',
    data: [],
    color: '#17a2b8'
}, {
    // TAMBAHKAN SERIES BARU DI SINI
    name: 'Faskes',
    data: [],
    color: '#6f42c1'
}, {
    name: 'Spesialist',
    data: [],
    color: '#e83e8c'
}]
```

**Langkah 4: Update metrics counter**
```javascript
// Lokasi: Sekitar baris 300, update total services count
<div class="metric-value" id="onlineServices">0/6</div>  // Ubah dari 0/4 ke 0/6
```

#### B. Update `monitoring_controller.php`

**Tambahkan case baru untuk parameter**
```php
// Lokasi: Sekitar baris 50-100
switch($param) {
    case 'peserta':
    case 'nik':
        // existing code
        break;
        
    case 'rujukan':
        // existing code
        break;
        
    // TAMBAHKAN CASE BARU DI SINI
    case 'faskes':
        $result = getFaskes();
        break;
        
    case 'spesialist':
        $result = getSpesialist();
        break;
}

// TAMBAHKAN FUNCTION BARU DI BAWAH
function getFaskes() {
    // Implementasi sesuai API BPJS
    $endpoint = "/referensi/faskes";
    return callBPJSAPI($endpoint);
}

function getSpesialist() {
    // Implementasi sesuai API BPJS
    $endpoint = "/referensi/spesialist";
    return callBPJSAPI($endpoint);
}
```

---

### 2. 📅 **Menambahkan Endpoint ANTROL Baru**

#### A. Update `dashboard_antrol.php`

**Langkah 1: Tambahkan endpoint ke konfigurasi**
```javascript
// Lokasi: Sekitar baris 350-370
const endpoints = {
    production: {
        dokter: "monitoring_controller.php?param=antrol",
        poli: "monitoring_controller.php?param=antrol_poli",
        // TAMBAHKAN ENDPOINT BARU DI SINI
        jadwal: "monitoring_controller.php?param=antrol_jadwal",
        quota: "monitoring_controller.php?param=antrol_quota"
    },
    cdn: {
        dokter: "monitoring_controller.php?param=antrol&cdn=1",
        poli: "monitoring_controller.php?param=antrol_poli&cdn=1",
        // TAMBAHKAN ENDPOINT CDN BARU DI SINI
        jadwal: "monitoring_controller.php?param=antrol_jadwal&cdn=1",
        quota: "monitoring_controller.php?param=antrol_quota&cdn=1"
    }
};
```

**Langkah 2: Tambahkan status card**
```html
<!-- Lokasi: Sekitar baris 250-300 -->
<div class="col-md-6">
    <div class="status-card" id="status-jadwal">
        <h6><i class="bi bi-calendar3"></i> Get Jadwal</h6>
        <div class="status-indicator status-loading">
            <i class="bi bi-hourglass-split"></i> Loading...
        </div>
        <div class="response-time">Response Time: -</div>
        <div class="http-status">HTTP: -</div>
        <div class="last-update">Last Update: Never</div>
    </div>
</div>

<div class="col-md-6">
    <div class="status-card" id="status-quota">
        <h6><i class="bi bi-list-check"></i> Get Quota</h6>
        <div class="status-indicator status-loading">
            <i class="bi bi-hourglass-split"></i> Loading...
        </div>
        <div class="response-time">Response Time: -</div>
        <div class="http-status">HTTP: -</div>
        <div class="last-update">Last Update: Never</div>
    </div>
</div>
```

**Langkah 3: Update services array dan chart**
```javascript
// Update services array
const services = ['dokter', 'poli', 'jadwal', 'quota'];

// Update chart series
series: [{
    name: 'Get Dokter',
    data: [],
    color: '#4169E1'
}, {
    name: 'Get Poli',
    data: [],
    color: '#1E90FF'
}, {
    // TAMBAHKAN SERIES BARU
    name: 'Get Jadwal',
    data: [],
    color: '#32CD32'
}, {
    name: 'Get Quota',
    data: [],
    color: '#FF6347'
}]

// Update metrics
<div class="metric-value" id="onlineServices">0/4</div>  // Ubah dari 0/2 ke 0/4
```

#### B. Update `monitoring_controller.php`

**Tambahkan case baru**
```php
// TAMBAHKAN CASE BARU UNTUK ANTROL
case 'antrol_jadwal':
    $result = getAntrolJadwal();
    break;
    
case 'antrol_quota':
    $result = getAntrolQuota();
    break;

// TAMBAHKAN FUNCTION BARU
function getAntrolJadwal() {
    $endpoint = "/jadwaldokter/kodepoli/A01/tanggal/" . date('Y-m-d');
    return callAntrolAPI($endpoint);
}

function getAntrolQuota() {
    $endpoint = "/ref/poli";
    return callAntrolAPI($endpoint);
}
```

---

### 3. ⚡ **Update Combined Dashboard**

Untuk `monitoring_dashboard.php`, tambahkan endpoint baru ke:

1. **Array endpoints** (baris ~150-170)
2. **Status cards HTML** (baris ~250-350)
3. **Services array** dalam `runMonitoringCycle()` (baris ~500)
4. **Chart series** (baris ~380)
5. **Clear data function** (baris ~600)

---

## 🔧 **Template Menambahkan Endpoint**

### File yang perlu diubah:
1. ✅ `dashboard_vclaim.php` atau `dashboard_antrol.php`
2. ✅ `monitoring_controller.php`
3. ✅ `monitoring_dashboard.php` (jika ingin di combined)
4. ✅ `index.php` (jika ingin di home)

### Checklist setiap penambahan:
- [ ] Tambah ke array `endpoints` (production & cdn)
- [ ] Buat status card HTML dengan ID unik
- [ ] Tambah ke array `services` di JavaScript
- [ ] Tambah series baru ke chart
- [ ] Update counter total services
- [ ] Tambah case baru di `monitoring_controller.php`
- [ ] Buat function API call baru
- [ ] Test endpoint baru

### Contoh penamaan konsisten:
- **ID HTML**: `status-namaendpoint`
- **Parameter**: `namaendpoint` atau `antrol_namaendpoint`
- **Function**: `getNamaEndpoint()` atau `getAntrolNamaEndpoint()`
- **Series name**: `'Get NamaEndpoint'`

---

## 📍 **Lokasi File Utama**

```
📁 bpjs-lawang/
├── 🏠 index.php (Dashboard utama)
├── 🏥 dashboard_vclaim.php (VCLAIM monitoring)
├── 📅 dashboard_antrol.php (ANTROL monitoring)
├── ⚡ monitoring_dashboard.php (Combined monitoring)
├── 🔧 monitoring_controller.php (API controller)
└── 📋 README_ENDPOINT.md (Dokumentasi ini)
```

Ikuti panduan ini setiap kali ingin menambahkan endpoint monitoring baru!
