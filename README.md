# BoltShare Rooms Clone - PHP 8+ Edition

Eine vollständige PHP 8+ + MySQL Implementierung der BoltShare Rooms Plattform.

## Features

- 🚀 Raumverwaltung (Public & Private)
- ⏱️ Automatisch ablaufende Räume
- 💬 Echtzeit-Chat
- 📤 Datei-Upload (Drag & Drop)
- 👥 Benutzerregistrierung & Login
- 🔒 Sicherheit (CSRF, SQL Injection Protection)
- 📱 Responsive Design

## Anforderungen

- PHP 8.0+
- MySQL 5.7+
- Webserver (Apache/Nginx)

## Installation

1. **Datenbank erstellen:**
   ```sql
   mysql -u root -p < database/schema.sql
   ```

2. **.env konfigurieren:**
   ```
   cp .env.example .env
   # Datenbank-Credentials anpassen
   ```

3. **Webserver konfigurieren:**
   DocumentRoot: `public/`

4. **Zugriff:**
   http://your-domain.com

## Projektstruktur

```
├── public/
│   ├── index.php          # Entry Point
│   ├── css/
│   ├── js/
│   └── uploads/           # Benutzer-Uploads
├── src/
│   ├── Core/              # Kernel, Router, DB Connection
│   ├── Models/            # Room, User, Message, File
│   ├── Controllers/       # Request Handler
│   ├── Views/             # HTML Templates
│   └── Helpers/           # Utility Functions
├── database/
│   └── schema.sql         # Database Schema
└── config/
    └── config.php         # Configuration
```

## API Endpoints

### Rooms
- `GET /api/rooms` - Alle öffentlichen Räume
- `POST /api/rooms` - Raum erstellen
- `GET /api/rooms/:id` - Raumdetails
- `DELETE /api/rooms/:id` - Raum löschen

### Chat
- `GET /api/rooms/:id/messages` - Chat-Nachrichten
- `POST /api/rooms/:id/messages` - Nachricht senden

### Files
- `POST /api/rooms/:id/upload` - Datei hochladen
- `GET /api/rooms/:id/files` - Dateien auflisten

## Lizenz

MIT
