# Setup-Anleitung

## Option 1: Docker (Empfohlen)

```bash
# Starten
docker-compose up -d

# Zugriff
http://localhost:8080
```

## Option 2: Manuell (Webspace)

### 1. Datenbank erstellen
```sql
MySQL > Import database/schema.sql
```

### 2. .env konfigurieren
```bash
cp .env.example .env
```

Anpassen:
- DB_HOST: Dein MySQL Host
- DB_USER: Dein MySQL User
- DB_PASSWORD: Dein Password
- DB_NAME: Dein Datenbankname

### 3. Webserver konfigurieren

**Apache (.htaccess in public/)**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?path=$1 [QSA,L]
</IfModule>
```

**Nginx**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?path=$uri;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### 4. Berechtigungen
```bash
chmod 755 public
chmod 777 public/uploads
```

### 5. Fertig!
Zugriff auf: https://your-domain.com

---

## Nächste Schritte

- [ ] Frontend vollständig implementieren (Room-View mit Chat/Media)
- [ ] WebSocket für Live-Chat hinzufügen
- [ ] File-Download mit automatischem Cleanup
- [ ] Admin-Panel
- [ ] Rate Limiting
- [ ] Email-Benachrichtigungen
