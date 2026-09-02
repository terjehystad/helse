# helse.terjehystad.com

Privat helse-app for Terje. Auto-deploy fra GitHub til Hostinger.

## ⚠️ Personvern
Dette repoet inneholder (etter fase 2) privat helsedata. Repoet er **privat**, og appen på web ligger **bak passord** (HTTP Basic Auth). Aldri gjør repoet public.

## Infrastruktur
- **Domene:** `helse.terjehystad.com` (subdomene under terjehystad.com)
- **Hosting:** Hostinger Business Web Hosting
- **Web-rot:** `/home/u791129952/domains/terjehystad.com/public_html/helse`
- **DNS:** auto-satt av Hostinger ved subdomene-opprettelse (Hostinger nameservere)

## Auto-deploy
Hostinger native Git-integrasjon (hPanel → Websites → terjehystad.com → Advanced → GIT) kobler `main`-branch til web-roten. Push til `main` → Hostinger auto-pull (~5–15 sek).

**Slik publiserer Terje:**
```bash
git push origin main
```
→ live på https://helse.terjehystad.com innen ~15 sek. Ingen manuell aksjon på Hostinger.

## Passordbeskyttelse (fase 2)
`.htaccess` i web-roten aktiverer Basic Auth. `.htpasswd` ligger KUN på serveren (aldri i git — se `.gitignore`), opprettes av Terje:
```
AuthType Basic
AuthName "Helse - privat"
AuthUserFile /home/u791129952/domains/terjehystad.com/public_html/helse/.htpasswd
Require valid-user
```

## Faser
1. ✅ Plassholder + pipeline (auto-deploy verifisert)
2. Basic Auth aktivert
3. Ekte app (`index.html` = helse-appen) pushet bak passord
