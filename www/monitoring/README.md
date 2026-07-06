# Monitoring check-and-restart

This folder contains a simple host-side monitoring script that checks the application URL and restarts the Docker Compose `app` service when repeated HTTP 504 responses are observed.

Files:
- `check_and_restart.sh` — the monitoring script.

Usage:

1. Make the script executable:

```bash
chmod +x /var/www/tip/www/monitoring/check_and_restart.sh
```

2. Run manually:

```bash
/var/www/tip/www/monitoring/check_and_restart.sh http://127.0.0.1:1100/
```

3. To run periodically, add a cron entry (every 1 minute):

```cron
* * * * * /var/www/tip/www/monitoring/check_and_restart.sh http://127.0.0.1:1100/ >/dev/null 2>&1
```

Notes and security:
- The script must be executed on the Docker host (not inside the `app` container) and by a user with access to Docker (e.g., root or a user in the `docker` group).
- The script restarts the `app` service via `docker compose` when it detects `THRESHOLD` consecutive 504 responses (default 2). Adjust `THRESHOLD` in the script as needed.
- Do not expose this script as a public web endpoint — it runs privileged commands and must remain host-only.
