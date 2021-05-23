#!/bin/bash

{
    swapoff /tmp/swap
    dd if=/dev/zero of=/tmp/swap bs=1M count=512
    mkswap /tmp/swap
    chmod 600 /tmp/swap
    swapon /tmp/swap
} > /dev/null 2>&1
