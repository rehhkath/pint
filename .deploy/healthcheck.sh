#!/bin/sh
set -e

if [ $(curl -o /dev/null -L -s -w "%{http_code}\n" http://localhost/docker-health-check) = "200" ]; then
    exit 0
else
    exit 1
fi
