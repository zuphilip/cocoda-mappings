#!/bin/bash

set -e
cd $(dirname $(realpath $0))

make -B properties.ids

# cronjobs run without local $PATH environment
WDMAPPER=/usr/local/bin/wdmapper

source ./wikidata.sh

date -Im

set +e # ignore errors

while read p; do
    echo -n "$p "
    
    # check expected result size
    COUNT=$(wdquerytsv "query=SELECT (COUNT(?x) AS ?c) { ?x wdt:$p ?v }" \
            | awk -F\" 'NR>1{print $2}')
    if (( $COUNT > 200000 )); then
        LANGUAGE=
    else
        LANGUAGE=en
    fi

    # harvest mappings with wdmapper
    timeout 240 $WDMAPPER get $p -o tmp.txt -g "$LANGUAGE"
    if [ $? -ne 0 ]; then
        echo "failed (expected $COUNT mappings)"
    else
        mv tmp.txt $p.txt
        head -2 $p.txt | tail -1

        # convert to JSKOS and CSV
        $WDMAPPER convert $p -i $p.txt -o $p.ndjson
        $WDMAPPER convert $p -i $p.txt -o $p.csv
    fi
done < properties.ids


make stats wikidata-concordances.json

php sparklines.php
