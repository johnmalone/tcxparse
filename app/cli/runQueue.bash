#!/bin/bash
php `dirname $0`/../../artisan --timeout=600 queue:listen
