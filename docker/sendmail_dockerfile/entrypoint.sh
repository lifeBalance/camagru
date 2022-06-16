#!/bin/bash
service sendmail restart

# keep running
tail -f /dev/null
