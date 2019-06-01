#!/bin/sh
APIKEY=afdfdxsdsd
APIPASS=afdfdxsdsd
cat /root/pln/internebs/internetbs.txt | while read domips
do

	NDD=$(echo $domips | cut -d';' -f1)
	IP=$(echo $domips | cut -d';' -f2)

wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Remove?apiKey=$APIKEY&password=$APIPASS&fullrecordname=www.$NDD&type=A"
sleep 1
wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Remove?apiKey=$APIKEY&password=$APIPASS&fullrecordname=$NDD&type=A"
sleep 1
wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Add?apiKey=$APIKEY&password=$APIPASS&fullrecordname=$NDD&type=A&value=$IP"
sleep 1
wget -O - -q "https://api.internet.bs/Domain/DnsRecord/Add?apiKey=$APIKEY&password=$APIPASS&fullrecordname=www.$NDD&type=A&value=$IP"

done
