#!/bin/bash

if [ -z "$1" ]; then
    ROOT_IN_VENDOR="/rnr1721"
else
    ROOT_IN_VENDOR="/$1"
fi

# Path for local-repository
LOCAL_REPOSITORY="$(realpath ../local-repository)"

# Path for vendor directory in your project
VENDOR_DIRECTORY="$(realpath ../../vendor${ROOT_IN_VENDOR})"

# Check for local repository exist local-repository
if [ -d "$LOCAL_REPOSITORY" ]; then
    echo "Directory local-repository exists"
else
    echo "Directory local-repository not exists"
    exit 1
fi

# Check for exist vendor
if [ -d "$VENDOR_DIRECTORY" ]; then
    echo "Directory vendor exists"
else
    echo "Directory local-repository not exists"
    exit 1
fi

# Get list of packages from local-repository
packages=$(ls "$LOCAL_REPOSITORY")

# Go to vendor directory
cd "$VENDOR_DIRECTORY" || exit

# Traverse package list and create symlinks if not exists
for package in $packages; do
    if [ -d "$VENDOR_DIRECTORY/$package" ]; then
        if [ ! -L "$package" ]; then
            rm -R "$VENDOR_DIRECTORY/$package"
            ln -s "$LOCAL_REPOSITORY/$package" "$VENDOR_DIRECTORY/$package"
            echo "Symlink for $package created"
        else
            echo "Symlink for $package already exists"
        fi
    else
        echo "Package $package not exists in vendor directory"
    fi
done
