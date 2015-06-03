#!/bin/sh
#
# This script needs to run on your local machine (Not in vagrant box)
# Before you start coding.
# It will update git hooks and up a vagrant box
#

# Load config values
source $(dirname $0)/config.sh

cd ${REPO_ROOT}

# Update git hooks
sh ${REPO_ROOT}/deployment/update-hooks.sh

