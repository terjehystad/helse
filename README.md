# helse.terjehystad.com

Privat helse-app for Terje. Auto-deploy fra GitHub til Hostinger.

## ⚠️ Personvern
Repoet inneholder bare det datatomme app-skallet. Private helsedata hentes etter
innlogging fra Terjes Supabase-prosjekt og beskyttes av RLS. Helse-snapshotet
skal aldri bygges inn i Git-repoet.

## Infrastruktur
- **Domene:** `helse.terjehystad.com` (subdomene under terjehystad.com)
- **Hosting:** Hostinger Business Web Hosting
- **Git install path i Hostinger:** `/` for helse-subdomenets eget nettsted
- **DNS:** auto-satt av Hostinger ved subdomene-opprettelse (Hostinger nameservere)

## Auto-deploy
Hostinger native Git-integrasjon (hPanel → Websites → terjehystad.com → Advanced → GIT) kobler `main`-branch til web-roten. Push til `main` → Hostinger auto-pull (~5–15 sek).

**Slik publiserer Terje:**
```bash
git push origin main
```
→ live på https://helse.terjehystad.com innen ~15 sek. Ingen manuell aksjon på Hostinger.

## Innlogging
Den publiserte appen har to lag:

- Hostinger Basic Auth via en serverlokal, usporet `.htaccess`.
- Supabase Auth + RLS før det private helse-snapshotet kan hentes.

Det statiske skallet inneholder ingen helseverdier. `.htaccess` og tilhørende
passordfil skal aldri legges i Git.

## Faser
1. ✅ Plassholder + pipeline (auto-deploy verifisert)
2. ✅ Datatomt app-skall (`index.html`) publisert
3. ✅ Basic Auth + Supabase Auth + privat snapshot/RLS
