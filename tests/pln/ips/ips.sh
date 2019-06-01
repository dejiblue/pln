#!/bin/sh
file="/var/cpanel/mainips/root"

for ip in $(cat /root/pln/ips/ips.txt);
do

if [ -f "$file" ]
then
	echo $ip >> /var/cpanel/mainips/root
else
mkdir /var/cpanel/mainips/
touch /var/cpanel/mainips/root
echo $ip >> /var/cpanel/mainips/root
fi

whmapi1 addips ips=$ip netmask=255.255.255.0

done

