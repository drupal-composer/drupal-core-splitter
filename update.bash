#!/bin/bash
ssh -T git@github.com
BASEDIR=$(dirname $0)

cd $BASEDIR
./console split 8.3.x
./console split 8.4.x
