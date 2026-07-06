#!/usr/bin/env bash
set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG="$DIR/monitor.log"
COUNT_FILE="$DIR/fail_count"
THRESHOLD=2
URL="${1:-http://127.0.0.1:1100/}"
COMPOSE_FILE="/var/www/tip/docker-compose.yml"
PROJECT_NAME="tip"
SERVICE="app"
TIMESTAMP="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"

# Get HTTP status (timeout 10s). On curl failure, return 000.
status=$(curl -s -o /dev/null -w '%{http_code}' --max-time 10 "$URL" || echo "000")

if [ "$status" = "504" ]; then
  count=$(cat "$COUNT_FILE" 2>/dev/null || echo 0)
  count=$((count+1))
  echo "$count" > "$COUNT_FILE"
  echo "$TIMESTAMP - HTTP $status - fail #$count" >> "$LOG"

  if [ "$count" -ge "$THRESHOLD" ]; then
    echo "$TIMESTAMP - Threshold reached; restarting $SERVICE" >> "$LOG"
    if docker compose --file "$COMPOSE_FILE" --project-name "$PROJECT_NAME" restart "$SERVICE" >>"$LOG" 2>&1; then
      echo "$TIMESTAMP - Restarted $SERVICE" >> "$LOG"
      echo 0 > "$COUNT_FILE"
    else
      echo "$TIMESTAMP - Restart failed" >> "$LOG"
    fi
  fi
else
  echo "$TIMESTAMP - HTTP $status - OK" >> "$LOG"
  echo 0 > "$COUNT_FILE"
fi
