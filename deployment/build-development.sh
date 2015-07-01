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

# Fetch master branch
git fetch origin master:master

# Initial git flow
git flow init -d

# Update environment config
cp .env.dev.example .env

# Create bootstrap/cache Directory
mkdir bootstrap/cache

# composer install
composer install

# npm install
npm install
