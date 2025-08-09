# 🏥 BPJS Monitoring Dashboard

Sistem monitoring real-time untuk API BPJS Kesehatan (VCLAIM dan ANTROL) dengan interface web yang modern dan responsif.

[![Repository](https://img.shields.io/badge/GitHub-Repository-blue?logo=github)](https://github.com/krisnadwiki/bpjs-monitoring)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Version](https://img.shields.io/badge/Version-2.1.0-orange)](https://github.com/krisnadwiki/bpjs-monitoring/releases)

## 🎯 Overview

Dashboard untuk memantau status dan performa API BPJS Kesehatan secara real-time dengan fitur:

- **Real-time Monitoring**: Response time dan status endpoint
- **BPJS API Validation**: Smart status berdasarkan metaData.code dan consumer ID
- **Multiple Dashboards**: VCLAIM, ANTROL, dan Combined view
- **Visual Analytics**: Chart dan metrics performa
- **Auto Refresh**: Monitoring otomatis dengan interval setting
- **Secure Configuration**: Environment-based credential management
- **Error Detection**: Deteksi error berdasarkan BPJS response code, bukan hanya HTTP status

## 🚀 Quick Start

### 1. Clone Repository
```bash
git clone https://github.com/krisnadwiki/bpjs-monitoring.git
cd bpjs-monitoring
```

### 2. Setup Kredensial
```bash
# Copy environment template
cp .env.example .env

# Edit .env dengan kredensial BPJS Anda
nano .env
```

### 3. Run Setup
```bash
php setup.php
```

### 4. Akses Dashboard
- **Home**: `http://localhost/bpjs-monitoring/index.php`
- **VCLAIM**: `http://localhost/bpjs-monitoring/dashboard_vclaim.php`
- **ANTROL**: `http://localhost/bpjs-monitoring/dashboard_antrol.php`

## ⚙️ Konfigurasi .env

```properties
# BPJS General Configuration
BPJS_CONS_ID=your_consumer_id_here
BPJS_SECRET_KEY=your_secret_key_here

# VCLAIM Configuration
BPJS_VCLAIM_USER_KEY=your_vclaim_user_key_here
BPJS_VCLAIM_BASE_URL=https://apijkn.bpjs-kesehatan.go.id/vclaim-rest/

# ANTROL Configuration
BPJS_ANTROL_USER_KEY=your_antrol_user_key_here
BPJS_ANTROL_BASE_URL=https://apijkn.bpjs-kesehatan.go.id/antreanrs/

# Monitoring Settings
MONITORING_DEFAULT_INTERVAL=5000
MONITORING_TIMEOUT=30
```

## 📋 Persyaratan Sistem

- **PHP**: 7.4+ dengan extensions: cURL, JSON, OpenSSL
- **Web Server**: Apache/Nginx (atau Laragon untuk development)
- **Browser**: Chrome, Firefox, Safari (modern browsers)

## 🔧 Troubleshooting

### Error: "config_secure.php not found"
- Pastikan file `config_secure.php` ada di root directory
- File ini adalah main configuration dengan .env loader

### Error: ".env file not found"
- Copy `.env.example` ke `.env`
- Isi dengan kredensial BPJS yang valid

### API Connection Failed
- Verify kredensial BPJS di `.env`
- Check koneksi internet dan firewall
- Periksa `logs/monitoring_[date].log`

### Dashboard Tidak Loading
- Check PHP error logs
- Verify file permissions: `chmod 755 data/ logs/ cache/`
- Clear browser cache

## 📁 Struktur File

```
bpjs-monitoring/
├── index.php                    # Home Dashboard
├── dashboard_vclaim.php         # VCLAIM Dashboard
├── dashboard_antrol.php         # ANTROL Dashboard
├── monitoring_controller.php    # API Controller
├── bpjs_helper.php             # BPJS API Helper
├── config_secure.php           # Main Configuration (with .env loader)
├── setup.php                   # Setup Script
├── .env.example                # Environment Template
├── .env                        # Environment Variables (ignored)
├── data/                       # Data Storage (ignored)
├── logs/                       # Log Files (ignored)
└── lz/                         # LZ Compression Library
```

## 🔒 Security

**File sensitif yang diabaikan Git:**
- `.env` - Kredensial BPJS
- `data/` - Data monitoring
- `logs/` - Log files
- `backup/` - Backup files

**Configuration System:**
- `config_secure.php` - Main config dengan dynamic .env loader
- Environment-based credential management
- Secure fallback values untuk development

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/krisnadwiki/bpjs-monitoring/issues)
- **Discussions**: [GitHub Discussions](https://github.com/krisnadwiki/bpjs-monitoring/discussions)
- **Documentation**: `README_ENDPOINT.md` untuk detail API

## 📄 License

MIT License - Copyright (c) 2025 Krisna Dwiki

---

**Repository**: [https://github.com/krisnadwiki/bpjs-monitoring](https://github.com/krisnadwiki/bpjs-monitoring)
