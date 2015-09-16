#!/usr/bin/env bash

PHP="$(dirname "$(realpath "$0")")/application/console/console.php"

php "$PHP" "$@"
