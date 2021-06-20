#!/usr/bin/env bash
# Helps you to upload your page via ssh.
#
# Copy this file to upload.sh or something and edit the target location below,
# to whatever server and file location your page is held in.

scp -r output/* server:/home/page/public_html/
