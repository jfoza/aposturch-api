#!/bin/sh

cd ${ROBOT_REPORTS_DIR}

rm *.png

HOME=${ROBOT_WORK_DIR}

if [ "${ROBOT_TEST_RUN_ID}" = "" ]
then
    ROBOT_REPORTS_FINAL_DIR="${ROBOT_REPORTS_DIR}"
else
    REPORTS_DIR_HAS_TRAILING_SLASH=`echo ${ROBOT_REPORTS_DIR} | grep '/$'`

    if [ ${REPORTS_DIR_HAS_TRAILING_SLASH} -eq 0 ]
    then
        ROBOT_REPORTS_FINAL_DIR="${ROBOT_REPORTS_DIR}${ROBOT_TEST_RUN_ID}"
    else
        ROBOT_REPORTS_FINAL_DIR="${ROBOT_REPORTS_DIR}/${ROBOT_TEST_RUN_ID}"
    fi
fi
mkdir -p ${ROBOT_REPORTS_FINAL_DIR}

xvfb-run \
        --server-args="-screen 0 ${SCREEN_WIDTH}x${SCREEN_HEIGHT}x${SCREEN_COLOUR_DEPTH} -ac" \
        robot \
        --outputDir $ROBOT_REPORTS_FINAL_DIR \
        --removekeywords name:DatabaseLibrary.connect_to_database -r index.html \
        ${ROBOT_OPTIONS} \
        $ROBOT_TESTS_DIR
