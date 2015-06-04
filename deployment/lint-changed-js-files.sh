#!/bin/sh
#
# This script will run in pre-commit hook
# It will lint added, copied and modified js files in this commit

# Load config values
source $(dirname $0)/config.sh

cd ${REPO_ROOT}

files=$(git diff --cached --name-only --diff-filter=ACM | grep ".js$")
if [ "$files" = "" ]; then
    exit ${EX_OK}
fi

gulp jshint
