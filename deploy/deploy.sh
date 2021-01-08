#!/bin/bash

set -e

echo "Which environment do you want to deploy to?"
echo "
1.) Staging
2.) Production
3.) Cancel"

read -e -p "Answer: " env

if [ "$env" == "1" ]; then

    ENV="staging"

elif [ "$env" == "2" ]; then

    ENV="production"

elif [ "$env" == "3" ]; then

    clear && exit 0

else

    echo "Please select 1, 2, or 3." && sleep 3
    clear

fi

echo "Got it!"
echo "What kind of release is it?"
echo "
1.) Patch / Fix
2.) Minor version release
3.) Major version release"

read -e -p "Answer: " release_type

if [ "$release_type" == "1" ]; then

    RELEASE_TYPE="patch"

elif [ "$release_type" == "2" ]; then

    RELEASE_TYPE="minor"

elif [ "$release_type" == "3" ]; then

    RELEASE_TYPE="major"

else

    echo "Please select 1, 2, or 3." && sleep 3
    clear

fi

echo "Ok! So you want to do a $RELEASE_TYPE release to the $ENV environment. Hold on, we're taking off!"

if [[ ${ENV} == 'staging' ]]; then
	TAG="release-stg-"

    if [[ `git describe --tags --match "${TAG}*" --abbrev=0` ]]; then
        GIT_TAG=`git describe --tags --match "${TAG}*" --abbrev=0`

        OLD_RELEASE_NUMBER="$(cut -d '-' -f3 <<< $GIT_TAG )"

        MAJOR_DIGIT="$(cut -d '.' -f1 <<< $OLD_RELEASE_NUMBER )"
        MINOR_DIGIT="$(cut -d '.' -f2 <<< $OLD_RELEASE_NUMBER )"
        PATCH_DIGIT="$(cut -d '.' -f3 <<< $OLD_RELEASE_NUMBER )"

        if [[ ${RELEASE_TYPE} == 'patch' ]]; then
        	PATCH_DIGIT="$(($PATCH_DIGIT + 1))"
        fi

        if [[ ${RELEASE_TYPE} == 'minor' ]]; then
        	MINOR_DIGIT="$(($MINOR_DIGIT + 1))"
        	PATCH_DIGIT="0"
        fi

        if [[ ${RELEASE_TYPE} == 'major' ]]; then
        	MAJOR_DIGIT="$(($MAJOR_DIGIT + 1))"
        	MINOR_DIGIT="0"
        	PATCH_DIGIT="0"
        fi

    else
        echo "No previous Git tags for $ENV found! Preparing first release..."

        if [[ ${RELEASE_TYPE} == 'patch' ]]; then
        	PATCH_DIGIT="1"
            MINOR_DIGIT="0"
            MAJOR_DIGIT="0"
        fi

        if [[ ${RELEASE_TYPE} == 'minor' ]]; then
            PATCH_DIGIT="0"
            MINOR_DIGIT="1"
            MAJOR_DIGIT="0"
        fi

        if [[ ${RELEASE_TYPE} == 'major' ]]; then
        	MAJOR_DIGIT="1"
        	MINOR_DIGIT="0"
        	PATCH_DIGIT="0"
        fi
    fi

elif [[ ${ENV} == 'production' ]]; then
	TAG="release-prod-"

    if [[ `git describe --tags --match "${TAG}*" --abbrev=0` ]]; then
        GIT_TAG=`git describe --tags --match "${TAG}*" --abbrev=0`

        OLD_RELEASE_NUMBER="$(cut -d '-' -f3 <<< $GIT_TAG )"

        MAJOR_DIGIT="$(cut -d '.' -f1 <<< $OLD_RELEASE_NUMBER )"
        MINOR_DIGIT="$(cut -d '.' -f2 <<< $OLD_RELEASE_NUMBER )"
        PATCH_DIGIT="$(cut -d '.' -f3 <<< $OLD_RELEASE_NUMBER )"

        if [[ ${RELEASE_TYPE} == 'patch' ]]; then
        	PATCH_DIGIT="$(($PATCH_DIGIT + 1))"
        fi

        if [[ ${RELEASE_TYPE} == 'minor' ]]; then
        	MINOR_DIGIT="$(($MINOR_DIGIT + 1))"
        	PATCH_DIGIT="0"
        fi

        if [[ ${RELEASE_TYPE} == 'major' ]]; then
        	MAJOR_DIGIT="$(($MAJOR_DIGIT + 1))"
        	MINOR_DIGIT="0"
        	PATCH_DIGIT="0"
        fi

    else
        echo "No previous Git tags for $ENV found! Preparing first release..."

        if [[ ${RELEASE_TYPE} == 'patch' ]]; then
        	PATCH_DIGIT="1"
            MINOR_DIGIT="0"
            MAJOR_DIGIT="0"
        fi

        if [[ ${RELEASE_TYPE} == 'minor' ]]; then
            PATCH_DIGIT="0"
            MINOR_DIGIT="1"
            MAJOR_DIGIT="0"
        fi

        if [[ ${RELEASE_TYPE} == 'major' ]]; then
        	MAJOR_DIGIT="1"
        	MINOR_DIGIT="0"
        	PATCH_DIGIT="0"
        fi
    fi
fi

# Update changelog
sh deploy/changelog.sh

# completely unrelated, but required workaround...
cp deploy/recipes/slack.php vendor/deployer/deployer/recipe/slack.php

# Create new tag
printf "===> Create new Git tag...\n"
git tag $TAG$MAJOR_DIGIT.$MINOR_DIGIT.$PATCH_DIGIT
# Push to Origin (triggers deployment)
printf "===> Push tag to remote...\n"
git push origin $TAG$MAJOR_DIGIT.$MINOR_DIGIT.$PATCH_DIGIT
