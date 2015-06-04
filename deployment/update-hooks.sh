#!/bin/sh
#
# Scripts for update client side git hook from repo git hook
#

# Load config values
source $(dirname $0)/config.sh

repo_git_hooks_folder="${REPO_ROOT}/.git-hooks"
local_git_hooks_folder="${REPO_ROOT}/.git/hooks"
# use relative path for symbol link, this reference to local_git_hooks_floder
# need to change to that folder before create link
rel_repo_git_hooks_folder="../../.git-hooks"

# Define git hooks
hooks=( 'pre-commit' 'post-merge' )

cd ${local_git_hooks_folder}

# Check local hook and repo hook. If not identical then make a symbol link
for i in "${hooks[@]}"; do
    if [ ! -f ${repo_git_hooks_folder}/${i}  ]; then
        echo >&2 "Given hook [$i] does not exists"
        exit ${EX_SOFTWARE}
    fi

    cmp ${repo_git_hooks_folder}/${i} ${local_git_hooks_folder}/${i}
    if [ $? != 0 ]; then
       ln -svf "${rel_repo_git_hooks_folder}/${i}" "${i}"
    fi
done
