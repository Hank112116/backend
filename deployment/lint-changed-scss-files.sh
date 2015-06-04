#!/bin/sh
#
# This script will run in pre-commit hook
# It will lint added, copied and modified scss files in this commit

# Load config values
source $(dirname $0)/config.sh

cd ${REPO_ROOT}

files=$(git diff --cached --name-only --diff-filter=ACM | grep ".scss$")
if [ "$files" = "" ]; then
    exit ${EX_OK}
fi

pass=true

echo 'Start Scss Lint:'

for file in ${files}; do
    scss-lint -c ${REPO_ROOT}/.scss-lint.yml ${file}
    if [ $? == ${EX_OK} ]; then
        echo "\t\033[32mSCSS Lint Passed: ${file}\033[0m"
    else
        echo "\t\033[31mSCSS Lint Failed: ${file}\033[0m"
        pass=false
    fi
done

echo 'Scss Lint validation complete'

if [ $pass == false ]; then
    echo "\033[41mCOMMIT FAILED:\033[0m Your commit contains files that should pass scss lint but do not. Please fix the scss lint errors and try again.\n"
    exit ${EX_DATAERR}
else
    echo "\033[42mScss Lint SUCCEEDED\033[0m\n"
fi
