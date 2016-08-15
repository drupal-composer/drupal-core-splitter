#!/bin/bash
ssh -T git@github.com
BASEDIR=$(dirname $0)

function update_subtree_split {
  cd $BASEDIR
 ./subtree-split fetch
  cd $BASEDIR/upstream
  git checkout -f $1
  git reset --hard origin/$1
  git clean -fd
  cd $BASEDIR
  ./subtree-split push branch $1
  ./console push-tags-legacy $1
  rm -rf ./upstream/.git/subtree-cache
}

update_subtree_split 8.0.x
update_subtree_split 8.1.x
update_subtree_split 8.2.x
