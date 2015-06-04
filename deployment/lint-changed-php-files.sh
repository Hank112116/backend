#!/bin/sh
#
# This script will run in pre-commit hook
# It will lint added, copied and modified php files in this commit

# Load config values
source $(dirname $0)/config.sh

# Regular experssion for lintable files
declare -r LINTER="linters/psr2.xml"
declare -r LINTABLE_FILES="^app/.php$"

cd ${REPO_ROOT}

files=$(git diff --cached --name-only --diff-filter=ACM | egrep "${LINTABLE_FILES}")
if [ "$files" = "" ]; then
    exit ${EX_OK}
fi

pass=true

echo 'Start PHP Lint:'

for file in ${files}; do
    phpcs --colors --standard=${LINTER} ${file}
    if [ $? == ${EX_OK} ]; then
        echo "\t\033[32mPHP Lint Passed: ${file}\033[0m"
    else
        echo "\t\033[31mPHP Lint Failed: ${file}\033[0m"
        pass=false
    fi
done

echo 'PHP Lint validation complete'

if [ $pass == false ]; then
    echo "\033[41mCOMMIT FAILED:\033[0m Your commit contains files that should pass php lint but do not. Please fix the php lint errors and try again.\n"
    exit ${EX_DATAERR}
else
    echo "\033[42mPHP Lint SUCCEEDED\033[0m\n"
fi