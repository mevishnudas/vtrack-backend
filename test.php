<?php


if (now()->lt(now()->copy()->setTime(6, 0))) {
    // current time is less than 06:00 AM
    echo "Current time is less than 06:00 AM";
}
