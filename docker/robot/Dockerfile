FROM python:3.10.0b3-slim

ENV SCREEN_COLOUR_DEPTH 24
ENV SCREEN_HEIGHT 1080
ENV SCREEN_WIDTH 1920
ENV ROBOT_UID 1000
ENV ROBOT_GID 1000
ENV ROBOT_REPORTS_DIR /opt/robotframework/reports
ENV ROBOT_TESTS_DIR /opt/robotframework/tests
ENV ROBOT_WORK_DIR /opt/robotframework/temp

COPY bin/chromedriver.sh /opt/robotframework/bin/chromedriver
COPY bin/chromium-browser.sh /opt/robotframework/bin/chromium
COPY bin/run-tests-in-virtual-screen.sh /opt/robotframework/bin/


ENV VIRTUAL_ENV=${UHOME}/venv
RUN python3 -m venv $VIRTUAL_ENV
ENV PATH="$VIRTUAL_ENV/bin:$PATH"

RUN apt-get update && apt-get install -y \
                postgresql-client \
                libpq-dev \
                gcc \
                xvfb \
                libnss3 \
                libc6-dev \
                glibc-source \
                chromium \
                chromium-driver \
                chromium-l10n \
                locales \
                gnupg \
                wget \
                curl \
                unzip \
                xauth \
                && mv /usr/bin/chromium /usr/lib/chromium/chrome-original \
                && ln -sfv /opt/robotframework/bin/chromium /usr/lib/chromium/chrome 
RUN sed -i -e 's/# pt_BR.UTF-8 UTF-8/pt_BR.UTF-8 UTF-8/' /etc/locale.gen && \
    dpkg-reconfigure --frontend=noninteractive locales && \
    update-locale LANG=pt_BR.UTF-8

ENV LANG pt_BR.UTF-8 

RUN python3 -m pip install --upgrade pip && \
    pip install robotframework \
                robotframework-databaselibrary \
                robotframework-faker \
                robotframework-requests \
                robotframework-seleniumlibrary \
                psycopg2-binary \
                psycopg2 \
                bcrypt


RUN mkdir -p ${ROBOT_REPORTS_DIR} \
  && mkdir -p ${ROBOT_WORK_DIR} \
  && chown ${ROBOT_UID}:${ROBOT_GID} ${ROBOT_REPORTS_DIR} \
  && chown ${ROBOT_UID}:${ROBOT_GID} ${ROBOT_WORK_DIR} \
  && chmod ugo+w ${ROBOT_REPORTS_DIR} ${ROBOT_WORK_DIR}

RUN chmod ugo+w /var/log \
  && chown ${ROBOT_UID}:${ROBOT_GID} /var/log
ENV PATH=/opt/robotframework/bin:/opt/robotframework/drivers:$PATH
VOLUME ${ROBOT_REPORTS_DIR}

USER ${ROBOT_UID}:${ROBOT_GID}
WORKDIR ${ROBOT_WORK_DIR}

# Execute all robot tests
CMD ["run-tests-in-virtual-screen.sh"]
